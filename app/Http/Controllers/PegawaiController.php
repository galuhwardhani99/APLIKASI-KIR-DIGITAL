<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::orderBy('nama')->paginate(15);
        return view('pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'       => 'required|unique:pegawais,nip|max:30',
            'nama'      => 'required|max:255',
            'jabatan'   => 'nullable|max:255',
            'unit_kerja'=> 'nullable|max:255',
        ], [
            'nip.required'  => 'NIP wajib diisi.',
            'nip.unique'    => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
        ]);

        Pegawai::create($request->only('nip', 'nama', 'jabatan', 'unit_kerja') + ['is_active' => true]);

        return redirect()->route('pegawai.index')
                         ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nip'       => 'required|max:30|unique:pegawais,nip,' . $pegawai->id,
            'nama'      => 'required|max:255',
            'jabatan'   => 'nullable|max:255',
            'unit_kerja'=> 'nullable|max:255',
        ]);

        $pegawai->update($request->only('nip', 'nama', 'jabatan', 'unit_kerja', 'is_active'));

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('pegawai.index')
                         ->with('success', 'Pegawai berhasil dihapus.');
    }

    // ── API endpoint: dipanggil JS saat dropdown nama dipilih ─────────────
    public function getNip(Pegawai $pegawai)
    {
        return response()->json([
            'nip'       => $pegawai->nip,
            'nama'      => $pegawai->nama,
            'jabatan'   => $pegawai->jabatan,
            'unit_kerja'=> $pegawai->unit_kerja,
        ]);
    }
}
