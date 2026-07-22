<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\KlasifikasiBarang;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index()
    {
        $asets = Aset::with('klasifikasiBarang.parent.parent.parent')->get();

        $asets = $asets->map(function ($aset) {

            $level6 = $aset->klasifikasiBarang;
            $level5 = $level6?->parent;
            $level4 = $level5?->parent;
            $level3 = $level4?->parent;


            $aset->level3_label = $level3
                ? "{$level3->kode} — {$level3->nama}"
                : 'BELUM DIKATEGORIKAN';


            $aset->level4_label = $level4
                ? "{$level4->kode} — {$level4->nama}"
                : 'BELUM DIKATEGORIKAN';


            $aset->level5_label = $level5
                ? "{$level5->kode} — {$level5->nama}"
                : 'BELUM DIKATEGORIKAN';


            $aset->level6_label = $level6
                ? "{$level6->kode} — {$level6->nama}"
                : 'BELUM DIKATEGORIKAN';


            $aset->kode_barang_lengkap = $level6
                ? "{$level6->kode}.{$aset->kode_barang}"
                : $aset->kode_barang;


            return $aset;

        });


        // Sorting berdasarkan kode klasifikasi + kode barang
        $asets = $asets->sortBy(function ($aset) {

            $kode = optional($aset->klasifikasiBarang)->kode ?? '';

            $paddedKode = collect(explode('.', $kode))
                ->filter(fn ($s) => $s !== '')
                ->map(fn ($s) => str_pad($s, 2, '0', STR_PAD_LEFT))
                ->implode('.');


            $itemKode = str_pad(
                $aset->kode_barang ?? '000',
                3,
                '0',
                STR_PAD_LEFT
            );


            return $paddedKode . '.' . $itemKode;

        })->values();


        return view('aset.index', compact('asets'));
    }

    /**
     * Menampilkan detail data aset (Bisa diakses oleh Admin & Auditor)
     */
    public function show(Aset $aset)
    {
        // Load relasi hirarki klasifikasi barang
        $aset->load('klasifikasiBarang.parent.parent.parent');

        $level6 = $aset->klasifikasiBarang;
        $level5 = $level6?->parent;
        $level4 = $level5?->parent;
        $level3 = $level4?->parent;

        $aset->level3_label = $level3 ? "{$level3->kode} — {$level3->nama}" : 'BELUM DIKATEGORIKAN';
        $aset->level4_label = $level4 ? "{$level4->kode} — {$level4->nama}" : 'BELUM DIKATEGORIKAN';
        $aset->level5_label = $level5 ? "{$level5->kode} — {$level5->nama}" : 'BELUM DIKATEGORIKAN';
        $aset->level6_label = $level6 ? "{$level6->kode} — {$level6->nama}" : 'BELUM DIKATEGORIKAN';

        $aset->kode_barang_lengkap = $level6
            ? "{$level6->kode}.{$aset->kode_barang}"
            : $aset->kode_barang;

        return view('aset.show', compact('aset'));
    }

    public function create()
    {
        $klasifikasiList = KlasifikasiBarang::orderBy('kode')->get();

        return view('aset.create', compact(
            'klasifikasiList'
        ));
    }

    public function getAsetByKlasifikasi($id)
    {
        $asets = Aset::where('klasifikasi_barang_id', $id)
            ->orderBy('kode_barang')
            ->get([
                'kode_barang',
                'nama_barang'
            ]);

        return response()->json($asets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'nibar' => [
                'required',
                'string',
                'max:50'
            ],

            'nomor_register' => [
                'required',
                'string',
                'max:50'
            ],

            'klasifikasi_barang_id' => [
                'required',
                'exists:klasifikasi_barangs,id'
            ],

            'kode_barang' => [
                'required',
                'regex:/^[0-9]{3}$/'
            ],

            'nama_barang' => 'required|string|max:255',

            'spesifikasi_nama_barang' => 
                'nullable|string|max:255',

            'merk_tipe' => 
                'nullable|string|max:255',

            'tahun_perolehan' => 
                'nullable|integer|min:1900|max:' . (date('Y') + 1),

            'jumlah' => 
                'required|numeric|min:0.01',

            'satuan' => 
                'nullable|string|max:50',

            'keterangan' => 
                'nullable|string',

            'kondisi' => 
                'required|in:baik,rusak_ringan,rusak_berat,hilang',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        Aset::create($validated);

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(Aset $aset)
    {
        $klasifikasiList = KlasifikasiBarang::orderBy('kode')->get();

        return view('aset.edit', compact(
            'aset',
            'klasifikasiList'
        ));
    }

    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([

            'nibar' => [
                'required',
                'string',
                'max:50'
            ],

            'nomor_register' => [
                'required',
                'string',
                'max:50'
            ],

            'klasifikasi_barang_id' => [
                'required',
                'exists:klasifikasi_barangs,id'
            ],

            'kode_barang' => [
                'required',
                'regex:/^[0-9]{3}$/'
            ],

            'nama_barang' => 
                'required|string|max:255',

            'spesifikasi_nama_barang' => 
                'nullable|string|max:255',

            'merk_tipe' => 
                'nullable|string|max:255',

            'tahun_perolehan' => 
                'nullable|integer|min:1900|max:' . (date('Y') + 1),

            'jumlah' => 
                'required|numeric|min:0.01',

            'satuan' => 
                'nullable|string|max:50',

            'keterangan' => 
                'nullable|string',

            'kondisi' => 
                'required|in:baik,rusak_ringan,rusak_berat,hilang',
        ]);

        $validated['updated_by'] = auth()->id();

        $aset->update($validated);

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        $aset->delete();

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}