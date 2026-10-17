<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $username = config('admin.username');
        $password = config('admin.password');

        if (! is_string($username) || $username === '' || ! is_string($password) || $password === '') {
            throw new RuntimeException('ADMIN_USERNAME dan ADMIN_PASSWORD harus diatur sebelum menjalankan seeder admin.');
        }

        User::updateOrCreate(
            ['username' => $username],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.test',
                'password' => $password,
                'role' => 'admin',
            ],
        );
    }
}
