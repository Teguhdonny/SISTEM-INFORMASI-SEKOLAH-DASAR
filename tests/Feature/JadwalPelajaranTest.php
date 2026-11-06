<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalPelajaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_requires_teacher_competency(): void
    {
        $guru = Guru::query()->create([
            'nama' => 'Guru Matematika',
            'bagian' => 'Guru Mata Pelajaran',
            'kompetensi' => "Matematika\nIPAS",
        ]);

        $response = $this->actingAs($this->admin())->post(route('admin.jadwal.store'), $this->schedulePayload($guru, [
            'mata_pelajaran' => 'Bahasa Indonesia',
        ]));

        $response->assertSessionHasErrors('id_guru');
        $this->assertDatabaseCount('jadwal_pelajaran', 0);
    }

    public function test_schedule_rejects_teacher_time_overlap(): void
    {
        $guru = Guru::query()->create([
            'nama' => 'Guru PJOK',
            'bagian' => 'Guru Mata Pelajaran',
            'kompetensi' => 'PJOK',
        ]);

        JadwalPelajaran::query()->create($this->schedulePayload($guru));

        $response = $this->actingAs($this->admin())->post(route('admin.jadwal.store'), $this->schedulePayload($guru, [
            'kelas' => 'Kelas 2',
            'jam_mulai' => '08:30',
            'jam_selesai' => '09:30',
        ]));

        $response->assertSessionHasErrors('id_guru');
        $this->assertDatabaseCount('jadwal_pelajaran', 1);
    }

    public function test_two_teachers_can_share_same_class_subject_time_for_co_teaching(): void
    {
        $guruA = Guru::query()->create([
            'nama' => 'Guru Kelas A',
            'bagian' => 'Guru Kelas',
            'kompetensi' => 'Matematika',
        ]);
        $guruB = Guru::query()->create([
            'nama' => 'Guru Kelas B',
            'bagian' => 'Guru Kelas',
            'kompetensi' => 'Matematika',
        ]);

        $this->actingAs($this->admin())->post(route('admin.jadwal.store'), $this->schedulePayload($guruA));
        $response = $this->actingAs($this->admin())->post(route('admin.jadwal.store'), $this->schedulePayload($guruB));

        $response->assertRedirect(route('admin.jadwal.index'));
        $this->assertDatabaseCount('jadwal_pelajaran', 2);
    }

    /** @return array<string, mixed> */
    private function schedulePayload(Guru $guru, array $overrides = []): array
    {
        return array_merge([
            'kelas' => 'Kelas 1',
            'hari' => 'Senin',
            'tanggal' => '2026-11-09',
            'mata_pelajaran' => 'Matematika',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
            'id_guru' => $guru->id,
        ], $overrides);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }
}
