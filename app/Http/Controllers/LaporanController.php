<?php

namespace App\Http\Controllers;

use App\Models\Kir;
use App\Models\Pegawai;
use App\Exports\KirExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // ── Form inventarisasi (setting parameter + tanda tangan) ────────────
    public function cetakKirForm(Kir $kir)
    {
        $kir->load([
            'ruangan.pengurusBarang',
            'ruangan.penanggungJawab',
            'items.aset',
        ]);

        $pegawaiList = Pegawai::where('is_active', true)
            ->orderBy('nama')
            ->get();

        // Cari ID pegawai berdasarkan nama pengguna_barang yang tersimpan
        $selectedPenggunaId = null;
        if ($kir->pengguna_barang) {
            $pg = Pegawai::where('nama', $kir->pengguna_barang)->first();
            $selectedPenggunaId = $pg?->id;
        }

        return view('laporan.cetak-kir', compact('kir', 'pegawaiList', 'selectedPenggunaId'));
    }

    // ── Simpan perubahan data inventarisasi ke tabel kirs ────────────────
    public function simpanInventarisasi(Request $request, Kir $kir)
    {
        $request->validate([
            'pengguna_barang_id'      => 'required|exists:pegawais,id',
            'pengguna_barang'         => 'required|string|max:255',
            'periode'                 => 'required|string|max:100',
            'kode_lokasi'             => 'nullable|string|max:100',
            'tanggal_ttd'             => 'required|date',
            'penandatangan_kiri_id'   => 'required|exists:pegawais,id',
            'penandatangan_kanan_id'  => 'required|exists:pegawais,id',
        ], [
            'pengguna_barang_id.required'     => 'Pengguna Barang wajib dipilih.',
            'periode.required'                => 'Periode wajib diisi.',
            'tanggal_ttd.required'            => 'Tanggal TTD wajib diisi.',
            'penandatangan_kiri_id.required'  => 'Penandatangan kiri wajib dipilih.',
            'penandatangan_kanan_id.required' => 'Penandatangan kanan wajib dipilih.',
        ]);

        $kir->update([
            'pengguna_barang'     => $request->pengguna_barang,       // nama dari hidden field
            'pengurus_barang_id'  => $request->penandatangan_kiri_id,
            'penanggung_jawab_id' => $request->penandatangan_kanan_id,
            'keterangan'          => $request->periode,               // simpan periode di keterangan
        ]);

        return redirect()
            ->route('laporan.cetak-kir.form', $kir->id)
            ->with('success', 'Data inventarisasi berhasil disimpan.');
    }

    // ── Validasi form cetak (shared) ─────────────────────────────────────
    private function validateCetak(Request $request): array
    {
        return $request->validate([
            'periode'                 => 'required|string|max:100',
            'pengguna_barang'         => 'required|string|max:255',
            'kode_lokasi'             => 'nullable|string|max:100',
            'tanggal_ttd'             => 'required|date',
            'penandatangan_kiri_id'   => 'required|exists:pegawais,id',
            'penandatangan_kanan_id'  => 'required|exists:pegawais,id',
        ], [
            'periode.required'                => 'Periode wajib diisi.',
            'pengguna_barang.required'        => 'Pengguna Barang wajib diisi.',
            'tanggal_ttd.required'            => 'Tanggal TTD wajib diisi.',
            'penandatangan_kiri_id.required'  => 'Penandatangan kiri wajib dipilih.',
            'penandatangan_kanan_id.required' => 'Penandatangan kanan wajib dipilih.',
        ]);
    }

    // ── Generate & download PDF ──────────────────────────────────────────
    public function cetakKirPdf(Request $request, Kir $kir)
    {
        $data = $this->validateCetak($request);
        $kir->load(['ruangan', 'items.aset']);

        $penandatanganKiri  = Pegawai::find($data['penandatangan_kiri_id']);
        $penandatanganKanan = Pegawai::find($data['penandatangan_kanan_id']);

        $pdf = Pdf::loadView('laporan.kir-pdf', [
            'kir'                => $kir,
            'periode'            => $data['periode'],
            'penggunaBarang'     => $data['pengguna_barang'],
            'kodeLokasi'         => $data['kode_lokasi'] ?? '-',
            'tanggalTtd'         => $data['tanggal_ttd'],
            'penandatanganKiri'  => $penandatanganKiri,
            'penandatanganKanan' => $penandatanganKanan,
        ])->setPaper('a4', 'landscape');

        $namaFile = 'KIR-' . str_replace('/', '-', $kir->nomor_kir) . '.pdf';
        return $pdf->download($namaFile);
    }

    // ── Generate & download Excel ─────────────────────────────────────────
    public function cetakKirExcel(Request $request, Kir $kir)
    {
        $data = $this->validateCetak($request);
        $kir->load(['ruangan', 'items.aset']);

        $penandatanganKiri  = Pegawai::find($data['penandatangan_kiri_id']);
        $penandatanganKanan = Pegawai::find($data['penandatangan_kanan_id']);

        $namaFile = 'KIR-' . str_replace('/', '-', $kir->nomor_kir) . '.xlsx';

        return Excel::download(new KirExport(
            $kir,
            $data['periode'],
            $data['pengguna_barang'],
            $data['kode_lokasi'] ?? '-',
            $data['tanggal_ttd'],
            $penandatanganKiri,
            $penandatanganKanan
        ), $namaFile);
    }
}