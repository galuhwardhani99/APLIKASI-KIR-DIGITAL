@extends('layouts.app')

@section('title', 'Tambah Ruangan')
@section('page_title', 'Tambah Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('ruangan.index') }}">Data Ruangan</a>
    </li>
    <li class="breadcrumb-item active">Tambah Ruangan</li>
@endsection

@section('content')

<div class="card card-primary card-outline">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-door-open mr-1"></i>
            Form Tambah Ruangan
        </h3>
    </div>

    <form action="{{ route('ruangan.store') }}" method="POST">
        @csrf

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label>Nama Ruangan <span class="text-danger">*</span></label>

                <input
                    type="text"
                    name="nama_ruangan"
                    class="form-control"
                    placeholder="Contoh : Ruang Sekretaris"
                    value="{{ old('nama_ruangan') }}"
                    required>
            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                Simpan
            </button>

            <a href="{{ route('ruangan.index') }}" class="btn btn-secondary">
                Batal
            </a>

        </div>

    </form>

</div>

@endsection