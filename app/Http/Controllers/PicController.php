<?php

namespace App\Http\Controllers;

use App\Models\Kir;
use Illuminate\Http\Request;

class PicController extends Controller
{
    /**
     * Riwayat PIC — berdasarkan pengguna barang dari tabel ruangans
     */
    public function history()
    {
        $kirs = Kir::with([
                'items.aset',
                'ruangan'
            ])
            ->whereHas('ruangan', function ($query) {
                $query->whereNotNull('pengguna_barang')
                      ->where('pengguna_barang', '!=', '');
            })
            ->get();


        $riwayatPic = $kirs
            ->groupBy(function ($kir) {
                return $kir->ruangan->pengguna_barang;
            })
            ->map(function ($kirGroup, $namaPengguna) {

                $asets = $kirGroup
                    ->flatMap(fn ($kir) => $kir->items->pluck('aset'))
                    ->filter()
                    ->unique('id')
                    ->values();


                $ruanganList = $kirGroup
                    ->pluck('ruangan.nama_ruangan')
                    ->filter()
                    ->unique()
                    ->values();


                return [
                    'nama'         => $namaPengguna,
                    'ruangan_list' => $ruanganList,
                    'jumlah_aset'  => $asets->count(),
                    'asets'        => $asets,
                ];

            })
            ->sortBy('nama')
            ->values();


        return view('pic.history', compact('riwayatPic'));
    }



    public function editNama(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        return view('pic.update-nama', [
            'nama' => $request->nama
        ]);
    }



    /**
     * Update nama pengguna barang
     * Sekarang update dilakukan di tabel ruangans
     */
    public function updateNama(Request $request)
    {
        $request->validate([
            'nama_lama' => 'required|string|max:255',
            'nama_baru' => 'required|string|max:255',
        ]);


        \App\Models\Ruangan::where('pengguna_barang', $request->nama_lama)
            ->update([
                'pengguna_barang' => $request->nama_baru
            ]);


        return redirect()
            ->route('pic.history')
            ->with(
                'success',
                "Nama pengguna barang berhasil diubah menjadi \"{$request->nama_baru}\"."
            );
    }



    /**
     * Hapus pengguna barang dari riwayat PIC
     */
    public function destroyNama(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);


        \App\Models\Ruangan::where('pengguna_barang', $request->nama)
            ->update([
                'pengguna_barang' => null
            ]);


        return redirect()
            ->route('pic.history')
            ->with(
                'success',
                "\"{$request->nama}\" berhasil dihapus dari Riwayat PIC."
            );
    }
}