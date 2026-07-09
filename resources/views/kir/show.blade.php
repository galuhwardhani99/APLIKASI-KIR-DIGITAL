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
                        <td class="py-0">
                            : <strong>{{ $kir->ruangan->nama_ruangan }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0 pl-0">Kode Lokasi</td>
                        <td class="py-0">: {{ $kir->ruangan->kode_lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0 pl-0">Pengguna Barang</td>
                        <td class="py-0">: {{ $kir->pengguna_barang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0 pl-0">Tanggal KIR</td>
                        <td class="py-0">: <strong>{{ $kir->tanggal->format('d F Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted py-0 pl-0">Keterangan</td>
                        <td class="py-0">: {{ $kir->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('kir.list', $kir->ruangan_id) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- TABEL ASET DALAM KIR --}}
<div class="card card-primary card-outline">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mr-auto">
            <i class="fas fa-boxes mr-1"></i>
            Data Aset dalam KIR
            <span class="badge badge-primary ml-1">{{ $kir->items->count() }} aset</span>
        </h3>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="40">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Spesifikasi</th>
                    <th>Merk/Tipe</th>
                    <th class="text-center">Jumlah</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th class="text-center">Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kir->items as $i => $item)
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
                    <td>{{ $i + 1 }}</td>
                    <td><small>{{ $a->kode_barang ?? '-' }}</small></td>
                    <td><strong>{{ $a->nama_barang }}</strong></td>
                    <td><small>{{ $a->spesifikasi_nama_barang ?? '-' }}</small></td>
                    <td>{{ $a->merk_tipe ?? '-' }}</td>
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
                    <td colspan="9" class="text-center text-muted py-3">
                        Belum ada aset dalam KIR ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection