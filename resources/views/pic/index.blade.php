@extends('layouts.app')

@section('title', 'Update PIC')
@section('page_title', 'Update PIC')

@section('breadcrumb')
    <li class="breadcrumb-item active">Update PIC</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.7/css/dataTables.bootstrap4.min.css">
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
            <i class="fas fa-users mr-1"></i>
            Daftar PIC Ruangan
        </h3>
    </div>

    <div class="card-body table-responsive">

        <table id="tablePIC" class="table table-bordered table-striped table-hover">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Ruangan</th>
                    <th>Pengguna Barang</th>
                    <th>Pengurus Barang</th>
                    <th>Penanggung Jawab</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @foreach($ruangans as $ruangan)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $ruangan->nama_ruangan }}</td>

                    <td>{{ $ruangan->pengguna_barang ?? '-' }}</td>

                    <td>
                        {{ $ruangan->pengurusBarang->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $ruangan->penanggungJawab->nama ?? '-' }}
                    </td>

                    <td class="text-center">

                        <a href="{{ route('pic.create') }}?ruangan={{ $ruangan->id }}"
                           class="btn btn-warning btn-sm">

                            <i class="fas fa-edit"></i>
                            Update

                        </a>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>

$(function(){

    $('#tablePIC').DataTable({

        language:{
            search:"Cari:",
            lengthMenu:"Tampilkan _MENU_ baris",
            info:"Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate:{
                previous:"Sebelumnya",
                next:"Selanjutnya"
            },
            zeroRecords:"Data tidak ditemukan",
            emptyTable:"Belum ada data"
        }

    });

});

</script>

@endpush