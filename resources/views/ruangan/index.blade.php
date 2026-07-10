@extends('layouts.app')

@section('title', 'Data Ruangan')
@section('page_title', 'Data Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Data Ruangan</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.7/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="card card-primary card-outline">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h3 class="card-title">
            <i class="fas fa-door-open mr-1"></i>
            Daftar Ruangan
        </h3>

        <a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i>
            Tambah Ruangan
        </a>

    </div>

    <div class="card-body table-responsive">

        <table id="tableRuangan" class="table table-bordered table-striped table-hover">

            <thead class="thead-light">

                <tr>
                    <th width="60">No</th>
                    <th>Nama Ruangan</th>
                    <th width="120">Aksi</th>
                </tr>

            </thead>

            <tbody>

                @foreach($ruangans as $ruangan)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $ruangan->nama_ruangan }}</td>

                    <td class="text-center">

                        <a href="{{ route('ruangan.edit', $ruangan->id) }}"
                           class="btn btn-warning btn-sm"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-danger btn-sm btn-hapus"
                            data-id="{{ $ruangan->id }}"
                            data-nama="{{ $ruangan->nama_ruangan }}"
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

{{-- ============================= --}}
{{-- MODAL KONFIRMASI HAPUS --}}
{{-- ============================= --}}
<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="labelModalHapus" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white py-2">

                <h6 class="modal-title" id="labelModalHapus">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Konfirmasi Hapus
                </h6>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>

            </div>

            <div class="modal-body text-center py-4">

                <i class="fas fa-door-open fa-3x text-danger mb-3"></i>

                <p class="mb-1">
                    Hapus ruangan berikut?
                </p>

                <p class="font-weight-bold mb-0" id="namaHapus">
                    -
                </p>

            </div>

            <div class="modal-footer justify-content-center py-2">

                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-dismiss="modal">

                    <i class="fas fa-times mr-1"></i>
                    Batal

                </button>

                <form id="formHapus" method="POST">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger btn-sm">

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>

$(function () {

    $('#tableRuangan').DataTable({

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            },
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data ruangan"
        }

    });

    // ============================
    // Modal Hapus
    // ============================

    $('.btn-hapus').on('click', function () {

        let id = $(this).data('id');
        let nama = $(this).data('nama');

        $('#namaHapus').text(nama);
        $('#formHapus').attr('action', '/ruangan/' + id);

    });

});

</script>

@endpush