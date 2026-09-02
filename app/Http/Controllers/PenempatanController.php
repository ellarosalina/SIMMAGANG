<?php

namespace App\Http\Controllers;

use App\Exports\PenempatanExport;
use App\Models\GuruPamong;
use App\Models\Mahasiswa;
use App\Models\Penempatan;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class PenempatanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $penempatans = Penempatan::with([
            'mahasiswa.user',
            'sekolah',
            'guruPamong.user'
        ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('mahasiswa.user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('sekolah', function ($query) use ($search) {
                        $query->where('nama_sekolah', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('guruPamong.user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when($status && in_array($status, [
                'menunggu',
                'berjalan',
                'selesai',
                'dibatalkan'
            ]), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Penempatan/Index', [
            'penempatans' => $penempatans,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $namaFile = 'Data_Penempatan_Magang';

        if ($status) {
            $namaFile .= '_' . ucfirst($status);
        }

        if ($search) {
            $namaFile .= '_Pencarian';
        }

        $namaFile .= '.xlsx';

        return Excel::download(
            new PenempatanExport($search, $status),
            $namaFile
        );
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::with('user')->get();

        /*
         * Jangan gunakan where('status', 'aktif')
         * karena status sekolah pada data Sekolah
         * menggunakan nilai seperti negeri dan swasta.
         */
        $sekolahs = Sekolah::latest()->get();

        $guruPamongs = GuruPamong::with('user')->get();

        return Inertia::render('Admin/Penempatan/Create', [
            'mahasiswas' => $mahasiswas,
            'sekolahs' => $sekolahs,
            'guruPamongs' => $guruPamongs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'sekolah_id' => 'required|exists:sekolahs,id',
            'guru_pamong_id' => 'required|exists:guru_pamongs,id',
            'periode' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:menunggu,berjalan,selesai,dibatalkan',
        ]);

        $guruPamong = GuruPamong::find($request->guru_pamong_id);

        if ($guruPamong->sekolah_id != $request->sekolah_id) {
            return back()
                ->withInput()
                ->withErrors([
                    'guru_pamong_id' => 'Guru pamong yang dipilih tidak mengajar di sekolah yang dipilih.',
                ]);
        }

        Penempatan::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'sekolah_id' => $request->sekolah_id,
            'guru_pamong_id' => $request->guru_pamong_id,
            'periode' => $request->periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.penempatan.index')
            ->with('success', 'Data penempatan berhasil ditambahkan.');
    }

    public function show(Penempatan $penempatan)
    {
        $penempatan->load([
            'mahasiswa.user',
            'sekolah',
            'guruPamong.user'
        ]);

        return Inertia::render('Admin/Penempatan/Show', [
            'penempatan' => $penempatan,
        ]);
    }

    public function edit(Penempatan $penempatan)
    {
        $penempatan->load([
            'mahasiswa.user',
            'sekolah',
            'guruPamong.user'
        ]);

        $mahasiswas = Mahasiswa::with('user')->get();

        /*
         * Sama seperti Create:
         * semua data sekolah ditampilkan.
         */
        $sekolahs = Sekolah::latest()->get();

        $guruPamongs = GuruPamong::with('user')->get();

        return Inertia::render('Admin/Penempatan/Edit', [
            'penempatan' => $penempatan,
            'mahasiswas' => $mahasiswas,
            'sekolahs' => $sekolahs,
            'guruPamongs' => $guruPamongs,
        ]);
    }

    public function update(Request $request, Penempatan $penempatan)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'sekolah_id' => 'required|exists:sekolahs,id',
            'guru_pamong_id' => 'required|exists:guru_pamongs,id',
            'periode' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:menunggu,berjalan,selesai,dibatalkan',
        ]);

        $guruPamong = GuruPamong::find($request->guru_pamong_id);

        if ($guruPamong->sekolah_id != $request->sekolah_id) {
            return back()
                ->withInput()
                ->withErrors([
                    'guru_pamong_id' => 'Guru pamong yang dipilih tidak mengajar di sekolah yang dipilih.',
                ]);
        }

        $penempatan->update([
            'mahasiswa_id' => $request->mahasiswa_id,
            'sekolah_id' => $request->sekolah_id,
            'guru_pamong_id' => $request->guru_pamong_id,
            'periode' => $request->periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.penempatan.index')
            ->with('success', 'Data penempatan berhasil diperbarui.');
    }

    public function destroy(Penempatan $penempatan)
    {
        $penempatan->delete();

        return redirect()
            ->route('admin.penempatan.index')
            ->with('success', 'Data penempatan berhasil dihapus.');
    }
}