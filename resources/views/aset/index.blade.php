@extends('layouts.app')

@section('title', 'Daftar Aset')
@section('page_title', 'Daftar Aset')

@section('breadcrumb')
    <li class="breadcrumb-item active">Data Aset</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.7/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-box mr-1"></i> Daftar Aset</h3>
        <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Aset
        </a>
    </div>

    <div class="card-body table-responsive">
        <table id="tableAset" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>NIBAR</th>
                    <th>Nomor Register</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Spesifikasi</th>
                    <th>Merk/Tipe</th>
                    <th>Tahun</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Ruangan</th>
                    <th>Kondisi</th>
                    <th style="width:110px">Aksi</th>
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
                        <td>{{ $aset->ruangan->nama_ruangan ?? '-' }}</td>
                        <td>
                            @php
                                $badge = [
                                    'baik' => 'success',
                                    'rusak_ringan' => 'warning',
                                    'rusak_berat' => 'danger',
                                    'hilang' => 'dark',
                                ][$aset->kondisi] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ str_replace('_', ' ', ucfirst($aset->kondisi)) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('aset.edit', $aset->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('aset.destroy', $aset->id) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Hapus aset ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
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
        $('#tableAset').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ baris",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: { previous: "Sebelumnya", next: "Selanjutnya" },
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data aset"
            },
            order: [[10, 'asc'], [0, 'asc']] // urut berdasarkan kolom Ruangan (index 10), lalu No (index 0)
        });
    });
</script>
@endpush