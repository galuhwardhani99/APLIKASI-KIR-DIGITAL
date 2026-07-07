@extends('layouts.app')

@section('title', 'Edit Ruangan')
@section('page_title', 'Edit Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('ruangan.index') }}">Data Ruangan</a>
    </li>
    <li class="breadcrumb-item active">Edit Ruangan</li>
@endsection

@section('content')

<div class="card card-primary card-outline">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit mr-1"></i>
            Form Edit Ruangan
        </h3>
    </div>

    <form action="{{ route('ruangan.update', $ruangan->id) }}" method="POST">
        @csrf
        @method('PUT')

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
                    value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}"
                    placeholder="Contoh : Ruang Sekretaris"
                    required>

            </div>

            <div class="form-group">

                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    rows="3"
                    class="form-control"
                    placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $ruangan->keterangan) }}</textarea>

            </div>

        </div>

        <div class="card-footer">

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                Update
            </button>

            <a href="{{ route('ruangan.index') }}" class="btn btn-secondary">
                Batal
            </a>

        </div>

    </form>

</div>

@endsection