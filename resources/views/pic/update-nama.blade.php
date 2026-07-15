@extends('layouts.app')

@section('title', 'Update PIC')
@section('page_title', 'Update PIC')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pic.history') }}">Riwayat PIC</a>
    </li>
    <li class="breadcrumb-item active">Update PIC</li>
@endsection

@section('content')

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-edit mr-1"></i>
                    Update Nama Pengguna Barang
                </h3>
            </div>

            <form method="POST" action="{{ route('pic.update-nama') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="nama_lama" value="{{ $nama }}">

                <div class="card-body">

                    <div class="form-group">
                        <label>Nama Saat Ini</label>
                        <input type="text" class="form-control bg-light" value="{{ $nama }}" disabled>
                    </div>

                    <div class="form-group mb-0">
                        <label>Nama Baru <span class="text-danger">*</span></label>
                        <input type="text" name="nama_baru" class="form-control"
                               value="{{ old('nama_baru', $nama) }}" required autofocus>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('pic.history') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection