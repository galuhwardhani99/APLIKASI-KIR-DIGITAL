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

}