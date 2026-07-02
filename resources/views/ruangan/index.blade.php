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
    <div class="alert alert-success">
        {{ session('success') }}
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

        <table id="tableRuangan" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode Lokasi</th>
                    <th>Nama Ruangan</th>
                    <th>Pengguna Barang</th>
                    <th>Pengurus Barang</th>
                    <th>Penanggung Jawab</th>
                    <th>Jumlah Aset</th>
                    <th width="170">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($ruangans as $ruangan)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $ruangan->kode_lokasi }}</td>

                        <td>{{ $ruangan->nama_ruangan }}</td>

                        <td>{{ $ruangan->pengguna_barang ?? '-' }}</td>

                        <td>{{ $ruangan->pengurusBarang->nama ?? '-' }}</td>

                        <td>{{ $ruangan->penanggungJawab->nama ?? '-' }}</td>

                        <td class="text-center">
                            <span class="badge badge-info">
                                {{ $ruangan->asets->count() }}
                            </span>
                        </td>

                        <td class="text-center">

                            <a href="{{ route('ruangan.kelola-aset', $ruangan->id) }}"
                               class="btn btn-info btn-sm"
                               title="Kelola Aset">
                                <i class="fas fa-boxes"></i>
                            </a>

                            <a href="{{ route('ruangan.edit', $ruangan->id) }}"
                               class="btn btn-warning btn-sm"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('ruangan.destroy', $ruangan->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus ruangan ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </form>

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

});
</script>

@endpush