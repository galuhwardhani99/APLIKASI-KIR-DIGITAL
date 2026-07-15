<?php

namespace App\Http\Controllers;

use App\Models\Kir;
use Illuminate\Http\Request;

class PicController extends Controller
{
    /**
     * Riwayat PIC — dikelompokkan berdasarkan pengguna_barang dari KIR,
     * menampilkan aset apa saja yang dipegang oleh masing-masing orang.
     */
    public function history()
    {
        $kirs = Kir::with(['items.aset', 'ruangan'])
            ->whereNotNull('pengguna_barang')
            ->where('pengguna_barang', '!=', '')
            ->get();

        $riwayatPic = $kirs
            ->groupBy('pengguna_barang')
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
                    'nama'          => $namaPengguna,
                    'ruangan_list'  => $ruanganList,
                    'jumlah_aset'   => $asets->count(),
                    'asets'         => $asets,
                ];
            })
            ->sortBy('nama')
            ->values();

        return view('pic.history', compact('riwayatPic'));
    }

    /**
     * Update nama pengguna barang — mengubah pengguna_barang
     * di SEMUA baris KIR yang memakai nama lama tersebut.
     */

    public function editNama(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
    ]);

    return view('pic.update-nama', ['nama' => $request->nama]);
}
    public function updateNama(Request $request)
    {
        $request->validate([
            'nama_lama' => 'required|string|max:255',
            'nama_baru' => 'required|string|max:255',
        ]);

        Kir::where('pengguna_barang', $request->nama_lama)
            ->update(['pengguna_barang' => $request->nama_baru]);

        return redirect()
            ->route('pic.history')
            ->with('success', "Nama pengguna barang berhasil diubah menjadi \"{$request->nama_baru}\".");
    }

    /**
     * Hapus dari Riwayat PIC — mengosongkan pengguna_barang
     * di semua KIR terkait nama tersebut. Dokumen KIR & data
     * aset tidak ikut terhapus, hanya "label" pengguna barangnya.
     */
    public function destroyNama(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Kir::where('pengguna_barang', $request->nama)
            ->update(['pengguna_barang' => null]);

        return redirect()
            ->route('pic.history')
            ->with('success', "\"{$request->nama}\" berhasil dihapus dari Riwayat PIC.");
    }
}