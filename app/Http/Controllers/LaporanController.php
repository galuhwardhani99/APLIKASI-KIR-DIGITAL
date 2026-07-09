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
    /**
     * Form setting parameter & tanda tangan sebelum cetak
     */
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

        return view('laporan.cetak-kir', compact('kir', 'pegawaiList'));
    }

    /**
     * Validasi parameter form cetak (dipakai untuk PDF & Excel)
     */
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
            'pengguna_barang.required'         => 'Pengguna Barang wajib diisi.',
            'tanggal_ttd.required'             => 'Tanggal TTD wajib diisi.',
            'penandatangan_kiri_id.required'   => 'Penandatangan sisi kiri wajib dipilih.',
            'penandatangan_kanan_id.required'  => 'Penandatangan sisi kanan wajib dipilih.',
        ]);
    }

    /**
     * Generate & download PDF
     */
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

    /**
     * Generate & download Excel
     */
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