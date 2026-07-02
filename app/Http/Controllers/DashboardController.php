<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Ruangan;
use App\Models\MutasiAset;
use App\Models\Notifikasi;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistik utama ────────────────────────────────────────────────
        $totalAset      = Aset::count();
        $asetBaik       = Aset::where('kondisi', 'baik')->count();
        $asetRusakRingan= Aset::where('kondisi', 'rusak_ringan')->count();
        $asetRusakBerat = Aset::where('kondisi', 'rusak_berat')->count();
        $asetRusak      = $asetRusakRingan + $asetRusakBerat;
        $asetHilang     = Aset::where('kondisi', 'hilang')->count();

        // ── Tabel rekapitulasi per ruangan ─────────────────────────────────
        $ruangans = Ruangan::withCount([
            'asets as total_aset',
            'asets as aset_baik'  => fn($q) => $q->where('kondisi', 'baik'),
            'asets as aset_rusak' => fn($q) => $q->whereIn('kondisi', ['rusak_ringan', 'rusak_berat']),
            'asets as aset_hilang'=> fn($q) => $q->where('kondisi', 'hilang'),
        ])->get();

        // ── Data grafik bar – aset per ruangan ─────────────────────────────
        $chartLabels = $ruangans->pluck('nama_ruangan');
        $chartData   = $ruangans->pluck('total_aset');

        // ── Mutasi terakhir (5 data) ────────────────────────────────────────
        $mutasiTerakhir = MutasiAset::with(['aset', 'ruanganAsal', 'ruanganTujuan'])
            ->latest()
            ->take(5)
            ->get();

        // ── Notifikasi belum dibaca (10 data) ──────────────────────────────
        $notifikasis = Notifikasi::where('is_read', false)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'totalAset',
            'asetBaik',
            'asetRusakRingan',
            'asetRusakBerat',
            'asetRusak',
            'asetHilang',
            'ruangans',
            'chartLabels',
            'chartData',
            'mutasiTerakhir',
            'notifikasis'
        ));
    }
}
