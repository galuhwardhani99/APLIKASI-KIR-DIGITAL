@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- ── BARIS 1: KARTU STATISTIK ─────────────────────────────────────── --}}
<div class="row">

    {{-- Total Aset --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalAset ?? 0 }}</h3>
                <p>Total Aset</p>
            </div>
            <div class="icon"><i class="fas fa-box"></i></div>
            <a href="{{ route('aset.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Aset Kondisi Baik --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $asetBaik ?? 0 }}</h3>
                <p>Aset Kondisi Baik</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('aset.index', ['kondisi' => 'baik']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Aset Rusak --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $asetRusak ?? 0 }}</h3>
                <p>Aset Rusak</p>
            </div>
            <div class="icon"><i class="fas fa-tools"></i></div>
            <a href="{{ route('aset.index', ['kondisi' => 'rusak']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Aset Hilang --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $asetHilang ?? 0 }}</h3>
                <p>Aset Hilang</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="{{ route('aset.index', ['kondisi' => 'hilang']) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>

{{-- ── BARIS 2: GRAFIK + TABEL RUANGAN ─────────────────────────────── --}}
<div class="row">

    {{-- Grafik Aset per Ruangan --}}
    <div class="col-lg-7">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Grafik Aset per Ruangan
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartRuangan" style="min-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Aset per Kondisi --}}
    <div class="col-lg-5">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Kondisi Aset
                </h3>
            </div>
            <div class="card-body">
                <canvas id="chartKondisi" style="min-height: 250px;"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ── BARIS 3: TABEL ASET PER RUANGAN ─────────────────────────────── --}}
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-door-open mr-1"></i>
                    Rekapitulasi Aset per Ruangan
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Ruangan</th>
                            <th>Kode Lokasi</th>
                            <th>Pengguna Barang</th>
                            <th class="text-center">Baik</th>
                            <th class="text-center">Rusak</th>
                            <th class="text-center">Hilang</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ruangans ?? [] as $i => $r)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $r->nama_ruangan }}</td>
                            <td><small class="text-muted">{{ $r->kode_lokasi }}</small></td>
                            <td>{{ $r->pengguna_barang ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge badge-success">{{ $r->aset_baik ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">{{ $r->aset_rusak ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-danger">{{ $r->aset_hilang ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ $r->total_aset ?? 0 }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Belum ada data ruangan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── BARIS 4: MUTASI TERAKHIR + NOTIFIKASI ───────────────────────── --}}
<div class="row">

    {{-- Mutasi Terakhir --}}
    <div class="col-lg-6">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exchange-alt mr-1"></i>
                    Mutasi Aset Terakhir
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Aset</th>
                            <th>Dari</th>
                            <th>Ke</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mutasiTerakhir ?? [] as $m)
                        <tr>
                            <td>{{ $m->aset->nama_barang ?? '-' }}</td>
                            <td><small>{{ $m->ruanganAsal->nama_ruangan ?? '-' }}</small></td>
                            <td><small>{{ $m->ruanganTujuan->nama_ruangan ?? '-' }}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($m->tanggal_mutasi)->format('d/m/Y') }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                Belum ada mutasi aset.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
    <div class="col-lg-6">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-1"></i>
                    Notifikasi Sistem
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($notifikasis ?? [] as $n)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <span>
                            @switch($n->jenis)
                                @case('aset_rusak')
                                    <i class="fas fa-tools text-warning mr-1"></i> @break
                                @case('aset_hilang')
                                    <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @break
                                @case('aset_pindah_ruangan')
                                    <i class="fas fa-exchange-alt text-info mr-1"></i> @break
                                @case('perubahan_pic')
                                    <i class="fas fa-user-edit text-primary mr-1"></i> @break
                                @default
                                    <i class="fas fa-bell text-secondary mr-1"></i>
                            @endswitch
                            {{ $n->pesan }}
                        </span>
                        <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-3">
                        Tidak ada notifikasi.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection

{{-- ── CHART JS ──────────────────────────────────────────────────────── --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Data dari controller (JSON)
const labelRuangan = @json($chartLabels ?? []);
const dataRuangan  = @json($chartData ?? []);

// Grafik Bar – Aset per Ruangan
new Chart(document.getElementById('chartRuangan'), {
    type: 'bar',
    data: {
        labels: labelRuangan,
        datasets: [{
            label: 'Jumlah Aset',
            data: dataRuangan,
            backgroundColor: 'rgba(60, 141, 188, 0.8)',
            borderColor: 'rgba(60, 141, 188, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Grafik Pie – Kondisi Aset
new Chart(document.getElementById('chartKondisi'), {
    type: 'doughnut',
    data: {
        labels: ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang'],
        datasets: [{
            data: [
                {{ $asetBaik ?? 0 }},
                {{ $asetRusakRingan ?? 0 }},
                {{ $asetRusakBerat ?? 0 }},
                {{ $asetHilang ?? 0 }}
            ],
            backgroundColor: ['#28a745','#ffc107','#fd7e14','#dc3545']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endpush
