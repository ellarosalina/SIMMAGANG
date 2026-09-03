<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MahasiswaProfilController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        return Inertia::render('Mahasiswa/Profil/Index', [
            'mahasiswa' => $mahasiswa->load('user'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'dosen_pembimbing' => 'nullable',
            'password' => 'nullable|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;

        $mahasiswa->update([
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'dosen_pembimbing' => $request->dosen_pembimbing,
        ]);

        // Upload foto profil
        if ($request->hasFile('foto')) {

            // Simpan path foto lama
            $fotoLamaMahasiswa = $mahasiswa->foto;
            $fotoLamaUser = $user->foto;

            // Hapus foto lama mahasiswa jika ada
            if (
                $fotoLamaMahasiswa &&
                Storage::disk('public')->exists($fotoLamaMahasiswa)
            ) {
                Storage::disk('public')->delete($fotoLamaMahasiswa);
            }

            // Jika user mempunyai foto lama yang berbeda,
            // hapus juga file tersebut
            if (
                $fotoLamaUser &&
                $fotoLamaUser !== $fotoLamaMahasiswa &&
                Storage::disk('public')->exists($fotoLamaUser)
            ) {
                Storage::disk('public')->delete($fotoLamaUser);
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('profile', 'public');

            // Simpan foto ke tabel mahasiswa
            $mahasiswa->update([
                'foto' => $fotoPath,
            ]);

            // Simpan foto yang sama ke tabel users
            // agar foto navbar ikut berubah
            $user->update([
                'foto' => $fotoPath,
            ]);
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()
            ->route('mahasiswa.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}