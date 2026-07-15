@extends('layouts.app')

@section('title', 'Tambah Aset')
@section('page_title', 'Tambah Aset')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Data Aset</a></li>
    <li class="breadcrumb-item active">Tambah Aset</li>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-box mr-1"></i> Form Tambah Aset</h3>
    </div>

    <form action="{{ route('aset.store') }}" method="POST">
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
                        <label>NIBAR <small class="text-muted">(otomatis)</small></label>
                        <input type="text" class="form-control bg-light" value="{{ $previewNibar }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomor Register <small class="text-muted">(otomatis)</small></label>
                        <input type="text" class="form-control bg-light" value="{{ $previewRegister }}" disabled>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Penempatan ruangan diatur belakangan lewat menu <strong>Data Ruangan</strong>, bukan di sini.
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Aset <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-control" required>
                            <option value="peralatan_mesin" {{ old('jenis') == 'peralatan_mesin' ? 'selected' : '' }}>
                                Peralatan dan Mesin
                            </option>
                            <option value="aset_tetap_lainnya" {{ old('jenis') == 'aset_tetap_lainnya' ? 'selected' : '' }}>
                                Aset Tetap Lainnya
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Barang <small class="text-muted">(klasifikasi BMD)</small></label>
                        <select name="klasifikasi_barang_id" class="form-control">
                            <option value="">-- Pilih Jenis Barang --</option>
                            @foreach($klasifikasiList as $k)
                                <option value="{{ $k->id }}" {{ old('klasifikasi_barang_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->kode }} — {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control"
                               placeholder="Contoh: 02.05.01.04.003" value="{{ old('kode_barang') }}">
                        <small class="text-muted">Kode klasifikasi Barang Milik Daerah (jika ada)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input list="daftar_nama_barang" name="nama_barang" class="form-control"
                               placeholder="Contoh: AC, Laptop, Filling Cabinet" value="{{ old('nama_barang') }}" required>
                        <datalist id="daftar_nama_barang">
                            <option value="AC">
                            <option value="Laptop">
                            <option value="Komputer PC">
                            <option value="Printer">
                            <option value="Filling Cabinet">
                            <option value="Meja Kerja">
                            <option value="Kursi Kerja">
                            <option value="Lemari Arsip">
                        </datalist>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Spesifikasi Nama Barang</label>
                        <input type="text" name="spesifikasi_nama_barang" class="form-control"
                               placeholder="Deskripsi tambahan (opsional)" value="{{ old('spesifikasi_nama_barang') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Merk / Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control"
                               placeholder="Contoh: Panasonik, HP, Brother" value="{{ old('merk_tipe') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan" class="form-control"
                               min="1900" max="{{ date('Y') + 1 }}" placeholder="Contoh: 2012"
                               value="{{ old('tahun_perolehan') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Jumlah <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="jumlah" class="form-control"
                               value="{{ old('jumlah', 1) }}" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Satuan</label>
                        <input list="daftar_satuan" name="satuan" class="form-control"
                               placeholder="Unit / Buah" value="{{ old('satuan') }}">
                        <datalist id="daftar_satuan">
                            <option value="Unit">
                            <option value="Buah">
                            <option value="Set">
                            <option value="Pcs">
                        </datalist>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kondisi <span class="text-danger">*</span></label>
                        <select name="kondisi" class="form-control" required>
                            <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="hilang" {{ old('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control"
                               placeholder="Catatan tambahan (opsional)" value="{{ old('keterangan') }}">
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Aset</button>
            <a href="{{ route('aset.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection