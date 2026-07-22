<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Aset;
use App\Models\Ruangan;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class MutasiController extends Controller
{

    public function index()
    {
        $mutasis = Mutasi::with([
            'aset',
            'ruanganAsal',
            'ruanganTujuan',
            'pemohon'
        ])
        ->latest()
        ->get();


        return view('mutasi.index', compact('mutasis'));
    }



    public function create()
    {
        return view('mutasi.create', [

            'asets' => Aset::with('ruangan')
                ->orderBy('nama_barang')
                ->get(),


            'ruangans' => Ruangan::orderBy('nama_ruangan')
                ->get(),


            'pegawais' => Pegawai::where('is_active', true)
                ->orderBy('nama')
                ->get()

        ]);
    }



    public function store(Request $request)
    {

        $data = $request->validate([

            'aset_id' => [
                'required',
                'exists:asets,id'
            ],


            'ruangan_tujuan_id' => [
                'required',
                'exists:ruangans,id'
            ],


            'pemohon_id' => [
                'required',
                'exists:pegawais,id'
            ],


            'tanggal_pengajuan' => [
                'required',
                'date'
            ],


            'alasan' => [
                'nullable',
                'string'
            ]

        ]);



        $aset = Aset::findOrFail($data['aset_id']);



        // simpan permintaan saja
        // aset belum berpindah

        Mutasi::create([

            'aset_id' => $aset->id,


            'ruangan_asal_id' => $aset->ruangan_id,


            'ruangan_tujuan_id' => $data['ruangan_tujuan_id'],


            'pemohon_id' => $data['pemohon_id'],


            'tanggal_pengajuan' => $data['tanggal_pengajuan'],


            'alasan' => $data['alasan'] ?? null,


            'status' => 'pending'

        ]);



        return redirect()
            ->route('mutasi.index')
            ->with('success', 'Permintaan mutasi berhasil dibuat.');
    }
    public function validasi(Request $request, Mutasi $mutasi)
    {
        $data = $request->validate([
            'status'           => 'required|in:disetujui,ditolak',
            'catatan_validasi' => 'nullable|string',
        ], [
            'status.required' => 'Status validasi wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
        ]);

        // Cegah validasi ganda kalau mutasi sudah diproses sebelumnya
        if ($mutasi->status !== 'pending') {
            return redirect()
                ->route('mutasi.index')
                ->with('success', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $mutasi->update([
            'status'            => $data['status'],
            'penerima_id'       => auth()->id(),
            'tanggal_validasi'  => now(),
            'catatan_validasi'  => $data['catatan_validasi'] ?? null,
        ]);

        // Kalau disetujui, pindahkan aset ke ruangan tujuan
        if ($data['status'] === 'disetujui') {
            $mutasi->aset->update([
                'ruangan_id' => $mutasi->ruangan_tujuan_id,
            ]);
        }

        $label = $data['status'] === 'disetujui' ? 'disetujui' : 'ditolak';

        return redirect()
            ->route('mutasi.index')
            ->with('success', "Permintaan mutasi berhasil {$label}.");
    }

}