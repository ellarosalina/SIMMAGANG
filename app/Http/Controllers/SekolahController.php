<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $sekolahs = Sekolah::when($search, function ($query) use ($search) {
            $query->where('nama_sekolah', 'like', "%{$search}%")
                ->orWhere('npsn', 'like', "%{$search}%")
                ->orWhere('jenjang', 'like', "%{$search}%")
                ->orWhere('kecamatan', 'like', "%{$search}%")
                ->orWhere('kabupaten', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Sekolah/Index', [
            'sekolahs' => $sekolahs,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Sekolah/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'npsn' => 'required|unique:sekolahs,npsn',
            'nama_sekolah' => 'required',
            'kepala_sekolah' => 'nullable',
            'jenjang' => 'required|in:SMA,SMK,SLB',
            'kecamatan' => 'nullable',
            'kabupaten' => 'nullable',
            'alamat' => 'required',
            'status' => 'required|in:negeri,swasta',
            'kuota_magang' => 'nullable|integer',
        ]);

        Sekolah::create($request->all());

        return redirect()
            ->route('admin.sekolah.index')
            ->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function show(Sekolah $sekolah)
    {
        return Inertia::render('Admin/Sekolah/Show', [
            'sekolah' => $sekolah,
        ]);
    }

    public function edit(Sekolah $sekolah)
    {
        return Inertia::render('Admin/Sekolah/Edit', [
            'sekolah' => $sekolah,
        ]);
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $request->validate([
            'npsn' => 'required|unique:sekolahs,npsn,' . $sekolah->id,
            'nama_sekolah' => 'required',
            'kepala_sekolah' => 'nullable',
            'jenjang' => 'required|in:SMA,SMK,SLB',
            'kecamatan' => 'nullable',
            'kabupaten' => 'nullable',
            'alamat' => 'required',
            'status' => 'required|in:negeri,swasta',
            'kuota_magang' => 'nullable|integer',
        ]);

        $sekolah->update($request->all());

        return redirect()
            ->route('admin.sekolah.index')
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        $sekolah->delete();

        return redirect()
            ->route('admin.sekolah.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }
}