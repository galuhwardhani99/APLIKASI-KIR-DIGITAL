@extends('layouts.app')

@section('title', isset($pegawai) ? 'Edit Pegawai' : 'Tambah Pegawai')
@section('page_title', isset($pegawai) ? 'Edit Pegawai' : 'Tambah Pegawai')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pegawai.index') }}">Data Pegawai</a></li>
    <li class="breadcrumb-item active">{{ isset($pegawai) ? 'Edit' : 'Tambah' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-plus mr-1"></i>
            Form {{ isset($pegawai) ? 'Edit' : 'Tambah' }} Pegawai
        </h3>
    </div>

    <form action="{{ isset($pegawai) ? route('pegawai.update', $pegawai) : route('pegawai.store') }}"
          method="POST">
        @csrf
        @if(isset($pegawai)) @method('PUT') @endif

        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label>NIP <span class="text-danger">*</span></label>
                <input type="text" name="nip"
                       class="form-control @error('nip') is-invalid @enderror"
                       value="{{ old('nip', $pegawai->nip ?? '') }}"
                       placeholder="Contoh: 196801011990031001"
                       maxlength="30">
                @error('nip')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $pegawai->nama ?? '') }}"
                       placeholder="Nama lengkap tanpa gelar">
                @error('nama')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="jabatan"
                       class="form-control"
                       value="{{ old('jabatan', $pegawai->jabatan ?? '') }}"
                       placeholder="Contoh: Pengurus Barang, Kepala Sub Bagian">
            </div>

            <div class="form-group">
                <label>Unit Kerja</label>
                <input type="text" name="unit_kerja"
                       class="form-control"
                       value="{{ old('unit_kerja', $pegawai->unit_kerja ?? '') }}"
                       placeholder="Contoh: Dinas Kearsipan dan Perpustakaan">
            </div>

            @if(isset($pegawai))
            <div class="form-group">
                <label>Status</label>
                <div>
                    <div class="icheck-primary d-inline mr-3">
                        <input type="radio" name="is_active" id="aktif" value="1"
                               {{ old('is_active', $pegawai->is_active) ? 'checked' : '' }}>
                        <label for="aktif">Aktif</label>
                    </div>
                    <div class="icheck-danger d-inline">
                        <input type="radio" name="is_active" id="nonaktif" value="0"
                               {{ !old('is_active', $pegawai->is_active) ? 'checked' : '' }}>
                        <label for="nonaktif">Non-aktif</label>
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                {{ isset($pegawai) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
        </div>

    </form>
</div>

</div>
</div>
@endsection
