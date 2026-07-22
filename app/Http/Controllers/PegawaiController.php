<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::orderBy('nama')->paginate(15);
        return view('pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        $request->validate([
            'nip'        => 'required|unique:pegawais,nip|max:30',
            'nama'       => 'required|max:255',
            'jabatan'    => 'nullable|max:255',
            'unit_kerja' => 'nullable|max:255',
        ], [
            'nip.required'  => 'NIP wajib diisi.',
            'nip.unique'    => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
        ]);

        Pegawai::create([
            'nip'        => $request->nip,
            'nama'       => $request->nama,
            'jabatan'    => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'is_active'  => true,
        ]);

        return redirect()->route('pegawai.index')
                         ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        $request->validate([
            'nip'        => 'required|max:30|unique:pegawais,nip,' . $pegawai->id,
            'nama'       => 'required|max:255',
            'jabatan'    => 'nullable|max:255',
            'unit_kerja' => 'nullable|max:255',
            'is_active'  => 'nullable|in:0,1',  
        ], [
            'nip.required'  => 'NIP wajib diisi.',
            'nip.unique'    => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
        ]);

        $pegawai->update([
            'nip'        => $request->nip,
            'nama'       => $request->nama,
            'jabatan'    => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'is_active'  => $request->input('is_active', 1), 
        ]);

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        $pegawai->delete();
        return redirect()->route('pegawai.index')
                         ->with('success', 'Pegawai berhasil dihapus.');
    }

    // API endpoint – auto-load NIP di dropdown form ruangan
    public function getNip(Pegawai $pegawai)
    {
        return response()->json([
            'nip'        => $pegawai->nip,
            'nama'       => $pegawai->nama,
            'jabatan'    => $pegawai->jabatan,
            'unit_kerja' => $pegawai->unit_kerja,
        ]);
    }
}