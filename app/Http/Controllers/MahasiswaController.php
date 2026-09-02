<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $mahasiswas = Mahasiswa::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('universitas', 'like', "%{$search}%")
                    ->orWhere('prodi', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Mahasiswa/Index', [
            'mahasiswas' => $mahasiswas,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Mahasiswa/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nim' => 'required|unique:mahasiswas,nim',
            'universitas' => 'nullable',
            'fakultas' => 'nullable',
            'prodi' => 'nullable',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('mahasiswa');

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'universitas' => $request->universitas,
                'fakultas' => $request->fakultas,
                'prodi' => $request->prodi,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Akun dan data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');

        return Inertia::render('Admin/Mahasiswa/Edit', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'password' => 'nullable|min:8',
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'universitas' => 'nullable',
            'fakultas' => 'nullable',
            'prodi' => 'nullable',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $mahasiswa) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $mahasiswa->user->update($userData);

            $mahasiswa->update([
                'nim' => $request->nim,
                'universitas' => $request->universitas,
                'fakultas' => $request->fakultas,
                'prodi' => $request->prodi,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->user->delete();

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}