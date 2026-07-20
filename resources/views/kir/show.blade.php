@extends('layouts.app')

@section('title', 'Detail KIR – ' . $kir->tanggal->format('d/m/Y'))
@section('page_title', 'Detail KIR')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kir.index') }}">KIR</a></li>
    <li class="breadcrumb-item">
        <a href="{{ route('kir.list', $kir->ruangan_id) }}">{{ $kir->ruangan->nama_ruangan }}</a>
    </li>
    <li class="breadcrumb-item active">{{ $kir->tanggal->format('d/m/Y') }}</li>
@endsection

@push('styles')
<style>
    #tableDetailKir {
        table-layout: fixed;
    }

    #tableDetailKir th,
    #tableDetailKir td {
        word-wrap: break-word;
        vertical-align: middle;
    }

    /* Kolom No */
    #tableDetailKir th:nth-child(1),
    #tableDetailKir td:nth-child(1) {
        width: 45px;
        max-width: 45px;
        text-align: center;
        white-space: nowrap;
        word-wrap: normal;
    }

    /* Kolom NIBAR & Nomor Register */
    #tableDetailKir th:nth-child(2),
    #tableDetailKir th:nth-child(3),
    #tableDetailKir td:nth-child(2),
    #tableDetailKir td:nth-child(3) {
        width: 170px;
        max-width: 170px;
        word-break: break-all;
        white-space: normal;
        text-align: center;
        line-height: 1.5;
        font-size: 13px;
        padding: 8px 6px;
    }

    /* Kolom Kode Barang */
    #tableDetailKir th:nth-child(4),
    #tableDetailKir td:nth-child(4) {
        min-width: 110px;
        text-align: center;
    }
</style>
@endpush

@section('content')

{{-- HEADER KIR --}}
<div class="card card-outline card-info mb-3">
    <div class="card-body py-3">
        <div class="row">
            <div class="col-md-8">
                <h5 class="mb-2">
                    <i class="fas fa-clipboard-list mr-1 text-info"></i>
                    Kartu Inventaris Ruangan
                </h5>
                <table class="table table-sm table-borderless mb-0" style="width:auto;">
                    <tr>
                        <td class="text-muted py-0 pl-0" width="160">Ruangan</td>
                        <td class="py-0">: <strong>{{ $kir->ruangan->nama_ruangan }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0 pl-0">Kode Lokasi</td>
                        <td class="py-0">: {{ $kir->ruangan->kode_lokasi ?? '-' }}</td>
                    </tr>
                    @if($kir->pengguna_barang)
                    <tr>
                        <td class="text-muted py-0 pl-0">Pengguna Barang</td>
                        <td class="py-0">: {{ $kir->pengguna_barang }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted py-0 pl-0">Tanggal KIR</td>
                        <td class="py-0">: <strong>{{ $kir->tanggal->format('d F Y') }}</strong></td>
                    </tr>
                    @if($kir->keterangan)
                    <tr>
                        <td class="text-muted py-0 pl-0">Keterangan</td>
                        <td class="py-0">: {{ $kir->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('laporan.cetak-kir.form', $kir->id) }}"
                   class="btn btn-primary btn-sm mb-1">
                    <i class="fas fa-print mr-1"></i> Inventarisasi
                </a>
                <a href="{{ route('kir.list', $kir->ruangan_id) }}"
                   class="btn btn-secondary btn-sm mb-1">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- TABEL ASET DALAM KIR — flat, kolom sesuai PDF/Excel Cetak KIR --}}
<div class="card card-primary card-outline">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mr-auto">
            <i class="fas fa-boxes mr-1"></i>
            Data Aset dalam KIR
            <span class="badge badge-primary ml-1">{{ $kir->items->count() }} aset</span>
        </h3>
    </div>

    <div class="table-responsive">
        <table id="tableDetailKir" class="table table-bordered table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th class="text-center">No</th>
                    <th>NIBAR</th>
                    <th>Nomor Register</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Spesifikasi Nama Barang</th>
                    <th>Merk/Tipe</th>
                    <th class="text-center">Tahun Perolehan</th>
                    <th class="text-center">Jumlah</th>
                    <th>Satuan</th>
                    <th>Ket</th>
                    <th class="text-center">Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kir->items as $j => $item)
                @php
                    $a = $item->aset;
                    $badge = [
                        'baik'         => 'success',
                        'rusak_ringan' => 'warning',
                        'rusak_berat'  => 'danger',
                        'hilang'       => 'dark',
                    ][$a->kondisi] ?? 'secondary';
                @endphp
                <tr>
                    <td class="text-center">{{ $j + 1 }}</td>
                    <td>{{ $a->nibar ?? '-' }}</td>
                    <td>{{ $a->nomor_register ?? '-' }}</td>
                    <td>{{ $a->kode_barang ?? '-' }}</td>
                    <td><strong>{{ $a->nama_barang }}</strong></td>
                    <td>{{ $a->spesifikasi_nama_barang ?? '-' }}</td>
                    <td>{{ $a->merk_tipe ?? '-' }}</td>
                    <td class="text-center">{{ $a->tahun_perolehan ?? '-' }}</td>
                    <td class="text-center">
                        {{ rtrim(rtrim(number_format($a->jumlah, 2, '.', ''), '0'), '.') }}
                    </td>
                    <td>{{ $a->satuan ?? '-' }}</td>
                    <td>{{ $a->keterangan ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $badge }}">
                            {{ str_replace('_', ' ', ucfirst($a->kondisi)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center text-muted py-3">
                        Belum ada aset dalam KIR ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tanda tangan --}}
    <div class="card-footer">
        <p class="text-center text-muted mb-3">
            Kediri, {{ $kir->tanggal->format('d F Y') }}
        </p>

    </div>
</div>

@endsection