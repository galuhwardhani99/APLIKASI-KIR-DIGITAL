@extends('layouts.app')

@section('title', 'Edit Aset')
@section('page_title', 'Edit Aset')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Data Aset</a></li>
    <li class="breadcrumb-item active">Edit Aset</li>
@endsection

@section('content')
<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Aset — {{ $aset->nama_barang }}</h3>
    </div>

    <form action="{{ route('aset.update', $aset->id) }}" method="POST">
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

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>NIBAR</label>
                        <input type="text" class="form-control bg-light" value="{{ $aset->nibar }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomor Register</label>
                        <input type="text" class="form-control bg-light" value="{{ $aset->nomor_register }}" disabled>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Ruangan saat ini:
                <strong>{{ $aset->ruangan->nama_ruangan ?? 'Belum ditempatkan' }}</strong>
                — ubah lewat menu <strong>Data Ruangan</strong>.
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control"
                               value="{{ old('kode_barang', $aset->kode_barang) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control"
                               value="{{ old('nama_barang', $aset->nama_barang) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Spesifikasi Nama Barang</label>
                        <input type="text" name="spesifikasi_nama_barang" class="form-control"
                               value="{{ old('spesifikasi_nama_barang', $aset->spesifikasi_nama_barang) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Merk / Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control"
                               value="{{ old('merk_tipe', $aset->merk_tipe) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan" class="form-control"
                               min="1900" max="{{ date('Y') + 1 }}"
                               value="{{ old('tahun_perolehan', $aset->tahun_perolehan) }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Jumlah <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="jumlah" class="form-control"
                               value="{{ old('jumlah', $aset->jumlah) }}" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" name="satuan" class="form-control"
                               value="{{ old('satuan', $aset->satuan) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kondisi <span class="text-danger">*</span></label>
                        <select name="kondisi" class="form-control" required>
                            @foreach (['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat', 'hilang' => 'Hilang'] as $key => $label)
                                <option value="{{ $key }}" {{ old('kondisi', $aset->kondisi) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control"
                               value="{{ old('keterangan', $aset->keterangan) }}">
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
            <a href="{{ route('aset.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection