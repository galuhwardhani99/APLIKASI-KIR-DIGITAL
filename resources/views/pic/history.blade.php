@extends('layouts.app')

@section('title', 'Riwayat PIC')
@section('page_title', 'Riwayat PIC')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pic.index') }}">Data PIC</a>
    </li>
    <li class="breadcrumb-item active">Riwayat PIC</li>
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
            <i class="fas fa-history mr-1"></i>
            Riwayat Pergantian PIC
        </h3>
    </div>

    <div class="card-body table-responsive">

        <table id="tableHistory" class="table table-bordered table-striped table-hover" style="width:100%">

            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Ruangan</th>
                    <th>Pengguna Barang Lama</th>
                    <th>Pengguna Barang Baru</th>
                    <th>Pengurus Barang Lama</th>
                    <th>Pengurus Barang Baru</th>
                    <th>PJ Ruangan Lama</th>
                    <th>PJ Ruangan Baru</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>

                @forelse($histories as $history)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $history->tanggal->format('d-m-Y') }}
                    </td>

                    <td>
                        {{ $history->ruangan->nama_ruangan ?? '-' }}
                    </td>

                    <td>
                        {{ $history->pengguna_barang_lama }}
                    </td>

                    <td>
                        {{ $history->pengguna_barang_baru }}
                    </td>

                    <td>
                        {{ $history->pengurusBarangLama->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $history->pengurusBarangBaru->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $history->penanggungJawabLama->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $history->penanggungJawabBaru->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $history->keterangan ?? '-' }}
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="10" class="text-center">
                        Belum ada riwayat pergantian PIC.
                    </td>
                </tr>

                @endforelse

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

    $('#tableHistory').DataTable({

        order: [[1, 'desc']],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            },
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada riwayat PIC"
        }

    });

});
</script>

@endpush