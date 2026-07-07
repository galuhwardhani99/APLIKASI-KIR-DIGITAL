@extends('layouts.app')

@section('title', 'Tambah Pegawai')
@section('page_title', 'Tambah Pegawai')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Data Pegawai</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-plus mr-1"></i> Form Tambah Pegawai
        </h3>
    </div>

    <form action="{{ route('pegawai.store') }}" method="POST">
        @csrf

        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- NIP --}}
            <div class="form-group">
                <label>NIP <span class="text-danger">*</span></label>
                <input type="text"
                       name="nip"
                       class="form-control @error('nip') is-invalid @enderror"
                       value="{{ old('nip') }}"
                       placeholder="Contoh: 196801011990031001"
                       maxlength="30"
                       autofocus>
                @error('nip')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Nama --}}
            <div class="form-group">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text"
                       name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama') }}"
                       placeholder="Nama lengkap (tanpa gelar)">
                @error('nama')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div class="form-group">
                <label>Jabatan</label>
                <input type="text"
                       name="jabatan"
                       class="form-control"
                       value="{{ old('jabatan') }}"
                       placeholder="Contoh: Pengurus Barang, Kepala Sub Bagian">
            </div>

            {{-- Unit Kerja --}}
            <div class="form-group">
                <label>Unit Kerja</label>
                <input type="text"
                       name="unit_kerja"
                       class="form-control"
                       value="{{ old('unit_kerja') }}"
                       placeholder="Contoh: Dinas Kearsipan dan Perpustakaan">
            </div>

        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </div>

    </form>
</div>

</div>
</div>
@endsection