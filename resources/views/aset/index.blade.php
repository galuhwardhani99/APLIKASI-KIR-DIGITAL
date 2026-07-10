@extends('layouts.app')

@section('title', 'Daftar Aset')
@section('page_title', 'Daftar Aset')

@section('breadcrumb')
    <li class="breadcrumb-item active">Data Aset</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<style>
    #tableAset thead th {
        vertical-align: middle;
        text-align: center;
    }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-box mr-1"></i> Daftar Aset
        </h3>

        <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Aset
        </a>
    </div>

    <div class="card-body table-responsive">
        <table id="tableAset" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">NIBAR</th>
                    <th rowspan="2">Nomor Register</th>
                    <th rowspan="2">Kode Barang</th>
                    <th rowspan="2">Nama Barang</th>
                    <th rowspan="2">Spesifikasi Nama Barang</th>
                    <th colspan="2">Spesifikasi Barang</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Satuan</th>
                    <th rowspan="2">Keterangan</th>
                    <th rowspan="2">Kondisi</th>
                    <th rowspan="2" style="width:110px">Aksi</th>
                </tr>
                <tr>
                    <th>Merk/Tipe</th>
                    <th>Tahun Perolehan</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($asets as $aset)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $aset->nibar }}</td>
                        <td>{{ $aset->nomor_register }}</td>
                        <td>{{ $aset->kode_barang }}</td>
                        <td>{{ $aset->nama_barang }}</td>
                        <td>{{ $aset->spesifikasi_nama_barang }}</td>
                        <td>{{ $aset->merk_tipe }}</td>
                        <td>{{ $aset->tahun_perolehan }}</td>
                        <td>{{ rtrim(rtrim(number_format($aset->jumlah, 2, '.', ''), '0'), '.') }}</td>
                        <td>{{ $aset->satuan }}</td>
                        <td>{{ $aset->keterangan ?? '-' }}</td>

                        <td>
                            @php
                                $badge = [
                                    'baik' => 'success',
                                    'rusak_ringan' => 'warning',
                                    'rusak_berat' => 'danger',
                                    'hilang' => 'dark',
                                ][$aset->kondisi] ?? 'secondary';
                            @endphp

                            <span class="badge badge-{{ $badge }}">
                                {{ str_replace('_', ' ', ucfirst($aset->kondisi)) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('aset.edit', $aset->id) }}"
                               class="btn btn-warning btn-sm"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <button
                                type="button"
                                class="btn btn-danger btn-sm btn-hapus"
                                data-id="{{ $aset->id }}"
                                data-nama="{{ $aset->nama_barang }}"
                                data-toggle="modal"
                                data-target="#modalHapus"
                                title="Hapus">

                                <i class="fas fa-trash"></i>
                            </button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ========================= --}}
{{-- MODAL HAPUS --}}
{{-- ========================= --}}
<div class="modal fade"
     id="modalHapus"
     tabindex="-1"
     role="dialog"
     aria-labelledby="labelModalHapus"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title" id="labelModalHapus">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Konfirmasi Hapus
                </h6>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center py-4">

                <i class="fas fa-box-open fa-3x text-danger mb-3"></i>

                <p class="mb-1">
                    Hapus aset berikut?
                </p>

                <p class="font-weight-bold mb-0" id="namaHapus">
                    -
                </p>

            </div>

            <div class="modal-footer justify-content-center py-2">

                <button
                    type="button"
                    class="btn btn-secondary btn-sm"
                    data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>
                    Batal
                </button>

                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger btn-sm">

                        <i class="fas fa-trash mr-1"></i>
                        Ya, Hapus
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
$(function () {

    $('#tableAset').DataTable({
        dom:
            '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-6"i><"col-sm-6"p>>',

        lengthMenu: [5,10,25,50,100],
        pageLength: 10,

        language: {
            lengthMenu: "Tampilkan _MENU_ menu",
            search: "",
            searchPlaceholder: "",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data aset",
            info: "Halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Halaman 0 dari 0",
            infoFiltered: "",
            paginate: {
                previous: "<",
                next: ">"
            }
        },

        columnDefs: [
            { type: 'string', targets: 1 },
            { type: 'string', targets: 2 },
            { orderable: false, targets: 12 }
        ],

        order: [[1, 'asc']]
    });

    $('#tableAset_filter label').contents().filter(function () {
        return this.nodeType === 3;
    }).first().replaceWith('Cari Menu: ');

    // ============================
    // Modal Hapus
    // ============================

    $('.btn-hapus').on('click', function () {

        let id = $(this).data('id');
        let nama = $(this).data('nama');

        $('#namaHapus').text(nama);
        $('#formHapus').attr('action', '/aset/' + id);

    });

});
</script>
@endpush