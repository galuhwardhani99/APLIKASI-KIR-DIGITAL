<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PicHistory;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PicController extends Controller
{
    /**
     * Halaman Update PIC
     */
    public function index()
    {
        $ruangans = Ruangan::with([
            'pengurusBarang',
            'penanggungJawab'
        ])->orderBy('nama_ruangan')->get();

        return view('pic.index', compact('ruangans'));
    }

    /**
     * Form Update PIC
     */
    public function create()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        $pegawai = Pegawai::where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('pic.create', compact(
            'ruangans',
            'pegawai'
        ));
    }

    /**
     * Simpan Update PIC
     */
    public function store(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangans,id',
            'pengguna_barang_baru' => 'required|string|max:255',
            'pengurus_barang_baru_id' => 'required|exists:pegawais,id',
            'penanggung_jawab_baru_id' => 'required|exists:pegawais,id',
            'keterangan' => 'nullable|string'
        ]);

        $ruangan = Ruangan::findOrFail($request->ruangan_id);

        // Simpan ke riwayat
        PicHistory::create([

            'ruangan_id' => $ruangan->id,

            'pengguna_barang_lama' =>
                $ruangan->pengguna_barang,

            'pengguna_barang_baru' =>
                $request->pengguna_barang_baru,

            'pengurus_barang_lama_id' =>
                $ruangan->pengurus_barang_id,

            'pengurus_barang_baru_id' =>
                $request->pengurus_barang_baru_id,

            'penanggung_jawab_lama_id' =>
                $ruangan->penanggung_jawab_id,

            'penanggung_jawab_baru_id' =>
                $request->penanggung_jawab_baru_id,

            'tanggal' => today(),

            'keterangan' =>
                $request->keterangan,
        ]);

        // Update data ruangan
        $ruangan->update([

            'pengguna_barang' =>
                $request->pengguna_barang_baru,

            'pengurus_barang_id' =>
                $request->pengurus_barang_baru_id,

            'penanggung_jawab_id' =>
                $request->penanggung_jawab_baru_id,
        ]);

        return redirect()
            ->route('pic.index')
            ->with('success', 'PIC berhasil diperbarui.');
    }

    public function history()
    {
        $histories = PicHistory::with([
            'ruangan',
            'pengurusBarangLama',
            'pengurusBarangBaru',
            'penanggungJawabLama',
            'penanggungJawabBaru'
        ])
        ->latest()
        ->get();

        return view('pic.history', compact('histories'));
    }
}