<?php

namespace App\Http\Controllers;

use App\Models\Kir;
use App\Models\Aset;
use App\Models\Pegawai;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class KirController extends Controller
{
    public function index()
    {
        $kirs = Kir::with([
            'ruangan',
            'pengurusBarang',
            'penanggungJawab'
        ])->latest()->get();

        return view('kir.index', compact('kirs'));
    }

    public function create()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        $pegawai = Pegawai::orderBy('nama')->get();

        return view('kir.create', compact(
            'ruangans',
            'pegawai'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangans,id',
            'pengguna_barang' => 'required|string|max:255',
            'pengurus_barang_id' => 'required|exists:pegawais,id',
            'penanggung_jawab_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'aset' => 'required|array'
        ]);

        $kir = Kir::create([
            'ruangan_id' => $validated['ruangan_id'],
            'pengguna_barang' => $validated['pengguna_barang'],
            'pengurus_barang_id' => $validated['pengurus_barang_id'],
            'penanggung_jawab_id' => $validated['penanggung_jawab_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $kir->asets()->sync($validated['aset']);

        return redirect()
            ->route('kir.index')
            ->with('success', 'KIR berhasil dibuat.');
    }
}