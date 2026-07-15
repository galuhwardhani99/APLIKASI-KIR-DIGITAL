<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\KlasifikasiBarang;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index()
    {
        // Ambil klasifikasi (level 6) + seluruh rantai induknya
        // (level 5 -> level 4 -> level 3) sekaligus, biar bisa bikin
        // header grup bertingkat walau level 3-5 tidak punya aset langsung.
        $asets = Aset::with('klasifikasiBarang.parent.parent.parent')->get();

        $asets = $asets->map(function ($aset) {
            $level6 = $aset->klasifikasiBarang;
            $level5 = $level6?->parent;
            $level4 = $level5?->parent;
            $level3 = $level4?->parent;

            $aset->level3_label = $level3 ? "{$level3->kode} — {$level3->nama}" : 'BELUM DIKATEGORIKAN';
            $aset->level4_label = $level4 ? "{$level4->kode} — {$level4->nama}" : 'BELUM DIKATEGORIKAN';
            $aset->level5_label = $level5 ? "{$level5->kode} — {$level5->nama}" : 'BELUM DIKATEGORIKAN';
            $aset->level6_label = $level6 ? "{$level6->kode} — {$level6->nama}" : 'BELUM DIKATEGORIKAN';

            // Kode barang LENGKAP persis seperti tampilan Excel:
            // kode klasifikasi (1.3.2.02.01.01) + kode jenis (003)
            // -> "1.3.2.02.01.01.003"
            $aset->kode_barang_lengkap = $level6
                ? "{$level6->kode}.{$aset->kode_barang}"
                : $aset->kode_barang;

            return $aset;
        });

        // Urutkan PERSIS seperti urutan di Excel: berdasarkan kode
        // hierarki klasifikasi (1.3.2 -> 1.3.2.02 -> 1.3.2.02.01 -> ...),
        // lalu kode_barang di dalamnya (001, 002, 003, ...).
        // Setiap segmen kode di-pad 2 digit supaya "10" tidak dianggap
        // lebih kecil dari "2" secara alfabet (sorting string biasa).
        $asets = $asets->sortBy(function ($aset) {
            $kode = optional($aset->klasifikasiBarang)->kode ?? '';
            $paddedKode = collect(explode('.', $kode))
                ->filter(fn ($s) => $s !== '')
                ->map(fn ($s) => str_pad($s, 2, '0', STR_PAD_LEFT))
                ->implode('.');

            $itemKode = str_pad($aset->kode_barang ?? '000', 3, '0', STR_PAD_LEFT);

            return $paddedKode . '.' . $itemKode;
        })->values();

        return view('aset.index', compact('asets'));
    }

    public function create()
    {
        $previewNibar    = $this->generateNibar();
        $previewRegister = $this->generateNomorRegister();
        $klasifikasiList = KlasifikasiBarang::orderBy('kode')->get();

        return view('aset.create', compact('previewNibar', 'previewRegister', 'klasifikasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'                   => 'required|in:peralatan_mesin,aset_tetap_lainnya',
            'klasifikasi_barang_id'   => 'nullable|exists:klasifikasi_barangs,id',
            'kode_barang'             => 'nullable|string|max:50',
            'nama_barang'             => 'required|string|max:255',
            'spesifikasi_nama_barang' => 'nullable|string|max:255',
            'merk_tipe'               => 'nullable|string|max:255',
            'tahun_perolehan'         => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah'                  => 'required|numeric|min:0.01',
            'satuan'                  => 'nullable|string|max:50',
            'keterangan'              => 'nullable|string',
            'kondisi'                 => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
        ]);

        $validated['nibar']          = $this->generateNibar();
        $validated['nomor_register'] = $this->generateNomorRegister();
        $validated['created_by']     = auth()->id();
        $validated['updated_by']     = auth()->id();

        Aset::create($validated);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(Aset $aset)
    {
        $klasifikasiList = KlasifikasiBarang::orderBy('kode')->get();
        return view('aset.edit', compact('aset', 'klasifikasiList'));
    }

    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'jenis'                   => 'required|in:peralatan_mesin,aset_tetap_lainnya',
            'klasifikasi_barang_id'   => 'nullable|exists:klasifikasi_barangs,id',
            'kode_barang'             => 'nullable|string|max:50',
            'nama_barang'             => 'required|string|max:255',
            'spesifikasi_nama_barang' => 'nullable|string|max:255',
            'merk_tipe'               => 'nullable|string|max:255',
            'tahun_perolehan'         => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah'                  => 'required|numeric|min:0.01',
            'satuan'                  => 'nullable|string|max:50',
            'keterangan'              => 'nullable|string',
            'kondisi'                 => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
        ]);

        $validated['updated_by'] = auth()->id();
        $aset->update($validated);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        $aset->delete();
        return redirect()->route('aset.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function show(Aset $aset)
    {
        $aset->load(['ruangan', 'klasifikasiBarang']);
        return view('aset.show', compact('aset'));
    }

    private function generateNibar(): string
    {
        $last = Aset::whereNotNull('nibar')->orderBy('id', 'desc')->first();
        $next = $last ? ((int) $last->nibar) + 1 : 1;
        return str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    private function generateNomorRegister(): string
    {
        $last = Aset::whereNotNull('nomor_register')->orderBy('id', 'desc')->first();
        $next = $last ? ((int) $last->nomor_register) + 1 : 1;
        return str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}
