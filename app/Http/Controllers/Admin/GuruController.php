<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $guru = Guru::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('bagian', 'like', "%{$search}%")
                        ->orWhere('lulusan', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();
        $totalGuru = Guru::count();

        return view('pages.admin.guru.index', compact('guru', 'search', 'totalGuru'));
    }

    public function create(): View
    {
        return view('pages.admin.guru.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('guru', 'nip')],
            'lulusan' => ['nullable', 'string', 'max:5000'],
            'bagian' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('guru', 'email')],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah digunakan oleh guru lain.',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('guru', 'public');
        }

        Guru::create($validated);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru): RedirectResponse
    {
        return redirect()->route('admin.guru.edit', $guru);
    }

    public function edit(Guru $guru): View
    {
        return view('pages.admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $rules = [
            'nama' => ['required', 'string', 'max:100'],
            'nip' => ['nullable', 'string', 'max:50'],
            'lulusan' => ['nullable', 'string', 'max:5000'],
            'bagian' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($request->input('nip') !== $guru->nip) {
            $rules['nip'][] = Rule::unique('guru', 'nip')->ignore($guru->id);
        }
        if ($request->input('email') !== $guru->email) {
            $rules['email'][] = Rule::unique('guru', 'email')->ignore($guru->id);
        }

        $validated = $request->validate($rules, [
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah digunakan oleh guru lain.',
        ]);

        $oldPhoto = $guru->foto;
        if ($request->hasFile('foto')) {
            $newPhoto = $request->file('foto')->store('guru', 'public');
            $validated['foto'] = $newPhoto;
        }

        $guru->update($validated);
        if ($request->hasFile('foto') && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        $photo = $guru->foto;
        $guru->delete();
        if ($photo) {
            Storage::disk('public')->delete($photo);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
