@extends('layouts.app')

@section('title', 'Riwayat PIC')
@section('page_title', 'Riwayat PIC')

@section('breadcrumb')
    <li class="breadcrumb-item active">Riwayat PIC</li>
@endsection

@push('styles')
<style>
    tr.row-pic { cursor: pointer; }
    tr.row-detail td { background: #f8f9fa; padding: 0 !important; }
    tr.row-detail { display: none; }
    .icon-toggle { transition: transform .2s; }
    .icon-toggle.open { transform: rotate(180deg); }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card card-primary card-outline">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-history mr-1"></i>
            Riwayat PIC — Pengguna Barang
        </h3>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped table-hover" style="width:100%">

            <thead class="thead-light">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Pengguna Barang</th>
                    <th>Ruangan</th>
                    <th width="140" class="text-center">Jumlah Aset</th>
                    <th width="60" class="text-center"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($riwayatPic as $index => $row)

                <tr>
                    <td class="row-pic" data-target="#detail-{{ $index }}">{{ $index + 1 }}</td>
                    <td class="row-pic" data-target="#detail-{{ $index }}"><strong>{{ $row['nama'] }}</strong></td>
                    <td class="row-pic" data-target="#detail-{{ $index }}">{{ $row['ruangan_list']->implode(', ') ?: '-' }}</td>
                    <td class="row-pic text-center" data-target="#detail-{{ $index }}">
                        <span class="badge badge-primary">{{ $row['jumlah_aset'] }}</span>
                    </td>
                    <td class="text-center">
                        <i class="fas fa-chevron-down icon-toggle row-pic" data-target="#detail-{{ $index }}"></i>
                    </td>
                </tr>

                <tr class="row-detail" id="detail-{{ $index }}">
                    <td colspan="5">
                        <div class="p-3">
                            <table class="table table-sm table-bordered mb-0 bg-white">
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
                                    @forelse($row['asets'] as $i => $aset)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $aset->kode_barang ?? '-' }}</td>
                                        <td><strong>{{ $aset->nama_barang }}</strong></td>
                                        <td>{{ $aset->spesifikasi_nama_barang ?? '-' }}</td>
                                        <td>{{ $aset->merk_tipe ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ rtrim(rtrim(number_format($aset->jumlah, 2, '.', ''), '0'), '.') }}
                                        </td>
                                        <td>{{ $aset->satuan ?? '-' }}</td>
                                        <td>{{ $aset->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            @php
                                                $badgeMap = [
                                                    'baik'         => 'success',
                                                    'rusak_ringan' => 'warning',
                                                    'rusak_berat'  => 'danger',
                                                    'hilang'       => 'dark',
                                                ];
                                                $labelMap = [
                                                    'baik'         => 'Baik',
                                                    'rusak_ringan' => 'Rusak Ringan',
                                                    'rusak_berat'  => 'Rusak Berat',
                                                    'hilang'       => 'Hilang',
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $badgeMap[$aset->kondisi] ?? 'secondary' }}">
                                                {{ $labelMap[$aset->kondisi] ?? $aset->kondisi }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-3">
                                            Tidak ada aset.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center">
                        Belum ada data pengguna barang dari KIR.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

@push('scripts')
<script>
$(function () {
    $(document).on('click', '.row-pic', function () {
        const target = $(this).data('target');
        const $detail = $(target);
        const $icon = $('.icon-toggle[data-target="' + target + '"]');

        $detail.toggle();
        $icon.toggleClass('open');
    });
});
</script>
@endpush