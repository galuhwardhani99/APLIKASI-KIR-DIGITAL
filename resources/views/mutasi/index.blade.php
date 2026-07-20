@extends('layouts.app')


@section('title','Permintaan Mutasi')
@section('page_title','Permintaan Mutasi Aset')


@section('breadcrumb')

<li class="breadcrumb-item active">
    Permintaan Mutasi
</li>

@endsection



@section('content')


@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif



<div class="card card-primary card-outline">


<div class="card-header d-flex justify-content-between">


<h3 class="card-title">

<i class="fas fa-exchange-alt"></i>

Daftar Permintaan Mutasi

</h3>



<a href="{{ route('mutasi.create') }}"
   class="btn btn-primary btn-sm">

<i class="fas fa-plus"></i>

Tambah Permintaan

</a>


</div>





<div class="card-body">


<table class="table table-bordered table-striped">


<thead>

<tr>

<th>No</th>

<th>Aset</th>

<th>Dari Ruangan</th>

<th>Ke Ruangan</th>

<th>Pemohon</th>

<th>Tanggal</th>

<th>Status</th>


</tr>


</thead>



<tbody>


@forelse($mutasis as $i => $mutasi)


<tr>


<td>
{{ $i+1 }}
</td>



<td>

<strong>
{{ $mutasi->aset->nama_barang }}
</strong>

<br>

<small>
Kode:
{{ $mutasi->aset->kode_barang }}
</small>


</td>



<td>

{{ $mutasi->ruanganAsal?->nama_ruangan ?? '-' }}

</td>



<td>

{{ $mutasi->ruanganTujuan?->nama_ruangan ?? '-' }}

</td>



<td>

{{ $mutasi->pemohon?->nama ?? '-' }}

</td>



<td>

{{ $mutasi->tanggal_pengajuan->format('d-m-Y') }}

</td>




<td>


@if($mutasi->status == 'pending')

<span class="badge badge-warning">
Pending
</span>


@elseif($mutasi->status == 'disetujui')


<span class="badge badge-success">
Disetujui
</span>


@else


<span class="badge badge-danger">
Ditolak
</span>


@endif



</td>



</tr>


@empty


<tr>

<td colspan="7"
class="text-center">

Belum ada permintaan mutasi.

</td>


</tr>


@endforelse



</tbody>


</table>


</div>


</div>


@endsection