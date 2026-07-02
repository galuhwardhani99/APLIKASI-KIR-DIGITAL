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
        <h3 class="card-title">{{ $aset->nama_barang }}</h3>
    </div>
    <div class="card-body">
        <table class="table table-sm table-bordered">
            <tr><th style="width:220px">NIBAR</th><td>{{ $aset->nibar }}</td></tr>
            <tr><th>Nomor Register</th><td>{{ $aset->nomor_register }}</td></tr>
            <tr><th>Kode Barang</th><td>{{ $aset->kode_barang }}</td></tr>
            <tr><th>Spesifikasi</th><td>{{ $aset->spesifikasi_nama_barang }}</td></tr>
            <tr><th>Merk / Tipe</th><td>{{ $aset->merk_tipe }}</td></tr>
            <tr><th>Tahun Perolehan</th><td>{{ $aset->tahun_perolehan }}</td></tr>
            <tr><th>Jumlah</th><td>{{ $aset->jumlah }} {{ $aset->satuan }}</td></tr>
            <tr><th>Ruangan</th><td>{{ $aset->ruangan->nama_ruangan ?? '-' }}</td></tr>
            <tr><th>Kondisi</th><td>{{ str_replace('_', ' ', ucfirst($aset->kondisi)) }}</td></tr>
            <tr><th>Keterangan</th><td>{{ $aset->keterangan }}</td></tr>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('aset.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection