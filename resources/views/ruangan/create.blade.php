@extends('layouts.app')

@section('title', 'Tambah Ruangan')
@section('page_title', 'Tambah Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ruangan.index') }}">Data Ruangan</a></li>
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

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Lokasi <span class="text-danger">*</span></label>
                        <input type="text"
                               name="kode_lokasi"
                               class="form-control"
                               value="{{ old('kode_lokasi') }}"
                               placeholder="Contoh : 12.13.33.08.02.01.01"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text"
                               name="nama_ruangan"
                               class="form-control"
                               value="{{ old('nama_ruangan') }}"
                               placeholder="Contoh : Ruang Sekretaris"
                               required>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label>Pengguna Barang</label>
                <input type="text"
                       name="pengguna_barang"
                       class="form-control"
                       value="{{ old('pengguna_barang') }}">
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pengurus Barang</label>

                        <select name="pengurus_barang_id" class="form-control">
                            <option value="">-- Pilih Pegawai --</option>

                            @foreach($pegawai as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('pengurus_barang_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach

                        </select>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Penanggung Jawab</label>

                        <select name="penanggung_jawab_id" class="form-control">
                            <option value="">-- Pilih Pegawai --</option>

                            @foreach($pegawai as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('penanggung_jawab_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach

                        </select>

                    </div>
                </div>

            </div>

            <div class="form-group">
                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    class="form-control"
                    rows="3">{{ old('keterangan') }}</textarea>
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