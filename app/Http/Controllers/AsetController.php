<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index()
    {
        $asets = Aset::with('ruangan')->oldest()->get();
        return view('aset.index', compact('asets'));
    }

    public function create()
    {
        // Preview nomor yang akan digenerate (hanya untuk ditampilkan di form)
        $previewNibar    = $this->generateNibar();
        $previewRegister = $this->generateNomorRegister();

        return view('aset.create', compact('previewNibar', 'previewRegister'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'                   => 'required|in:peralatan_mesin,aset_tetap_lainnya',
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

        // ── Auto-generate field yang tidak diinput manual ──────────────
        $validated['nibar']          = $this->generateNibar();
        $validated['nomor_register'] = $this->generateNomorRegister();
        $validated['created_by']     = auth()->id();
        $validated['updated_by']     = auth()->id();
        // ruangan_id & no_urut sengaja dibiarkan null — diisi nanti lewat fitur Ruangan

        Aset::create($validated);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(Aset $aset)
    {
        return view('aset.edit', compact('aset'));
    }

    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'jenis'                   => 'required|in:peralatan_mesin,aset_tetap_lainnya',
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
        $aset->load('ruangan');
        return view('aset.show', compact('aset'));
    }

    // ────────────────────────────────────────────────────────────────────
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