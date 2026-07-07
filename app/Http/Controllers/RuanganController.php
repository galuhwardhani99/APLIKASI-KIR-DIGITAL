<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Ruangan;
use App\Models\Aset;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
{
    $ruangans = Ruangan::with([
        'pengurusBarang',
        'penanggungJawab',
        'asets'
    ])->latest()->get();

    return view('ruangan.index', compact('ruangans'));
}

    public function create()
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        return view('ruangan.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Ruangan::create($validated);

        return redirect()->route('ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Ruangan $ruangan)
    {
        //
    }

    public function edit(Ruangan $ruangan)
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        return view('ruangan.edit', compact('ruangan', 'pegawai'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $ruangan->update($validated);

        return redirect()->route('ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function kelolaAset(Ruangan $ruangan)
{
    $asets = Aset::orderBy('nama_barang')->get();

    return view('ruangan.kelola-aset', compact(
        'ruangan',
        'asets'
    ));
}

public function simpanAset(Request $request, Ruangan $ruangan)
{
    $asetDipilih = $request->aset ?? [];

    // kosongkan semua aset dari ruangan ini
    Aset::where('ruangan_id', $ruangan->id)
        ->update([
            'ruangan_id' => null
        ]);

    // masukkan aset yang dipilih
    Aset::whereIn('id', $asetDipilih)
        ->update([
            'ruangan_id' => $ruangan->id
        ]);

    return redirect()
        ->route('ruangan.index')
        ->with('success','Aset berhasil ditempatkan ke ruangan.');
}

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();

        return redirect()->route('ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}