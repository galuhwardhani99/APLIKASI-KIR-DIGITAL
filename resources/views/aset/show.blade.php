@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page_title', 'Detail Aset')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Data Aset</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="card card-primary card-outline">

    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-box mr-1"></i> {{ $aset->nama_barang }}
        </h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped table-hover">
            <tbody>

                {{-- INFORMASI DASAR --}}
                <tr class="table-secondary">
                    <th colspan="2" class="text-uppercase"><i class="fas fa-id-card mr-1"></i> Identitas Aset</th>
                </tr>

                <tr>
                    <th style="width: 250px;">NIBAR</th>
                    <td>{{ $aset->nibar ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Nomor Register</th>
                    <td>{{ $aset->nomor_register ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Kode Barang Lengkap</th>
                    <td>
                        <span class="badge badge-dark p-2" style="font-size: 0.9rem;">
                            {{ $aset->kode_barang_lengkap ?? $aset->kode_barang ?? '-' }}
                        </span>
                    </td>
                </tr>

                {{-- HIERARKI KLASIFIKASI BARANG --}}
                <tr class="table-secondary">
                    <th colspan="2" class="text-uppercase"><i class="fas fa-sitemap mr-1"></i> Klasifikasi Barang</th>
                </tr>

                <tr>
                    <th>Level 3 (Kelompok)</th>
                    <td>{{ $aset->level3_label ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Level 4 (Sub Kelompok)</th>
                    <td>{{ $aset->level4_label ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Level 5 (Sub Sub Kelompok)</th>
                    <td>{{ $aset->level5_label ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Level 6 (Kode Klasifikasi)</th>
                    <td>{{ $aset->level6_label ?? '-' }}</td>
                </tr>

                {{-- SPESIFIKASI & FISIK --}}
                <tr class="table-secondary">
                    <th colspan="2" class="text-uppercase"><i class="fas fa-info-circle mr-1"></i> Spesifikasi & Detail Fisik</th>
                </tr>

                <tr>
                    <th>Spesifikasi</th>
                    <td>{{ $aset->spesifikasi_nama_barang ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Merk / Tipe</th>
                    <td>{{ $aset->merk_tipe ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Tahun Perolehan</th>
                    <td>{{ $aset->tahun_perolehan ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Jumlah & Satuan</th>
                    <td>{{ $aset->jumlah }} {{ $aset->satuan }}</td>
                </tr>

                <tr>
                    <th>Ruangan</th>
                    <td>{{ $aset->ruangan->nama_ruangan ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Kondisi</th>
                    <td>
                        @php
                            $badgeClass = match($aset->kondisi) {
                                'baik' => 'success',
                                'rusak_ringan' => 'warning',
                                'rusak_berat' => 'danger',
                                'hilang' => 'secondary',
                                default => 'info',
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeClass }} px-2 py-1 text-uppercase">
                            <i class="fas fa-check-circle mr-1"></i> {{ str_replace('_', ' ', $aset->kondisi) }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Keterangan</th>
                    <td>{{ $aset->keterangan ?? '-' }}</td>
                </tr>

            </tbody>
        </table>

    </div>

    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('aset.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>

        {{-- Tombol Edit HANYA Tampil untuk Admin --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('aset.edit', $aset->id) }}" class="btn btn-warning btn-sm text-white">
                <i class="fas fa-edit mr-1"></i> Edit Data Aset
            </a>
        @endif
    </div>

</div>
@endsection