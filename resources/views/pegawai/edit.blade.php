@extends('layouts.app')

@section('title', 'Edit Pegawai')
@section('page_title', 'Edit Pegawai')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Data Pegawai</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">

<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-edit mr-1"></i> Form Edit Pegawai
        </h3>
    </div>

    <form action="{{ route('pegawai.update', $pegawai) }}" method="POST">
        @csrf
        @method('PUT')

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
                       value="{{ old('nip', $pegawai->nip) }}"
                       maxlength="30">
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
                       value="{{ old('nama', $pegawai->nama) }}">
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
                       value="{{ old('jabatan', $pegawai->jabatan) }}"
                       placeholder="Contoh: Pengurus Barang">
            </div>

            {{-- Unit Kerja --}}
            <div class="form-group">
                <label>Unit Kerja</label>
                <input type="text"
                       name="unit_kerja"
                       class="form-control"
                       value="{{ old('unit_kerja', $pegawai->unit_kerja) }}"
                       placeholder="Contoh: Dinas Kearsipan dan Perpustakaan">
            </div>

            {{-- Status --}}
            <div class="form-group mb-0">
                <label>Status</label>
                <div class="mt-1">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="aktif" name="is_active" value="1"
                               class="custom-control-input"
                               {{ old('is_active', $pegawai->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label text-success font-weight-bold" for="aktif">
                            Aktif
                        </label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="nonaktif" name="is_active" value="0"
                               class="custom-control-input"
                               {{ !old('is_active', $pegawai->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label text-secondary" for="nonaktif">
                            Non-aktif
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save mr-1"></i> Update
            </button>
        </div>

    </form>
</div>

</div>
</div>
@endsection