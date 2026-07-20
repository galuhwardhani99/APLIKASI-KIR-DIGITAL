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
    // ── Form inventarisasi ────────────────────────────────────────────────
    public function cetakKirForm(Kir $kir)
    {
        $kir->load([
            'ruangan',
            'items.aset',
        ]);

        $pegawaiList = Pegawai::where('is_active', true)
            ->orderBy('nama')
            ->get();

        $selectedPenggunaId = null;
        if ($kir->pengguna_barang) {
            $pg = Pegawai::where('nama', $kir->pengguna_barang)->first();
            $selectedPenggunaId = $pg?->id;
        }

        return view('laporan.cetak-kir', compact('kir', 'pegawaiList', 'selectedPenggunaId'));
    }

    // ── Simpan inventarisasi → redirect ke Daftar KIR ─────────────────────
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
            'pengguna_barang'     => $request->pengguna_barang,
            'pengurus_barang_id'  => $request->penandatangan_kiri_id,
            'penanggung_jawab_id' => $request->penandatangan_kanan_id,
            'keterangan'          => $request->periode,
        ]);

        // Redirect ke DAFTAR KIR -> tombol Cetak PDF/Excel muncul di
        // kolom Aksi tiap baris (tidak dipaksa cetak langsung saat itu juga).
        return redirect()
            ->route('kir.list', $kir->ruangan_id)
            ->with('success', 'Data inventarisasi berhasil disimpan. Silahkan cetak PDF atau Excel dari kolom Aksi.');
    }

    // ── Validasi cetak ────────────────────────────────────────────────────
    private function validateCetak(Request $request): array
    {
        return $request->validate([
            'periode'                 => 'required|string|max:100',
            'pengguna_barang'         => 'required|string|max:255',
            'kode_lokasi'             => 'nullable|string|max:100',
            'tanggal_ttd'             => 'required|date',
            'penandatangan_kiri_id'   => 'required|exists:pegawais,id',
            'penandatangan_kanan_id'  => 'required|exists:pegawais,id',
        ]);
    }

    // ── Cetak PDF ─────────────────────────────────────────────────────────
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

    // ── Cetak Excel ───────────────────────────────────────────────────────
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