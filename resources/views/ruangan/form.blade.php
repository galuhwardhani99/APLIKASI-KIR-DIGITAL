@extends('layouts.app')

@section('title', isset($ruangan) ? 'Edit Ruangan' : 'Tambah Ruangan')
@section('page_title', isset($ruangan) ? 'Edit Ruangan' : 'Tambah Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Data Ruangan</a></li>
    <li class="breadcrumb-item active">{{ isset($ruangan) ? 'Edit' : 'Tambah' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-door-open mr-1"></i>
            Form {{ isset($ruangan) ? 'Edit' : 'Tambah' }} Ruangan
        </h3>
    </div>

    <form action="{{ isset($ruangan) ? route('ruangan.update', $ruangan) : route('ruangan.store') }}"
          method="POST">
        @csrf
        @if(isset($ruangan)) @method('PUT') @endif

        <div class="card-body">

            {{-- Alert error --}}
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- ── INFO RUANGAN ──────────────────────────────────────────── --}}
            <h6 class="text-muted border-bottom pb-1 mb-3">
                <i class="fas fa-info-circle mr-1"></i> Informasi Ruangan
            </h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Ruangan <span class="text-danger">*</span></label>
                        <select name="nama_ruangan" class="form-control @error('nama_ruangan') is-invalid @enderror">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach(['Kepala Dinas','Sekretaris','Sub Keuangan','Sub Umum','Record Center Arsip','Tamu','Rapat'] as $r)
                                <option value="{{ $r }}"
                                    {{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') === $r ? 'selected' : '' }}>
                                    {{ $r }}
                                </option>
                            @endforeach
                        </select>
                        @error('nama_ruangan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="kode_lokasi"
                               class="form-control @error('kode_lokasi') is-invalid @enderror"
                               value="{{ old('kode_lokasi', $ruangan->kode_lokasi ?? '') }}"
                               placeholder="12.13.33.08.02.01.01">
                        @error('kode_lokasi')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Pengguna Barang</label>
                <input type="text" name="pengguna_barang"
                       class="form-control"
                       value="{{ old('pengguna_barang', $ruangan->pengguna_barang ?? '') }}"
                       placeholder="Nama kepala dinas / pimpinan">
            </div>

            {{-- ── PENGURUS BARANG ────────────────────────────────────────── --}}
            <h6 class="text-muted border-bottom pb-1 mb-3 mt-4">
                <i class="fas fa-user-tie mr-1"></i> Pengurus Barang
            </h6>

            <div class="row">
                {{-- Dropdown nama pegawai --}}
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Nama Pengurus Barang <span class="text-danger">*</span></label>
                        <select name="pengurus_barang_id"
                                id="select_pengurus"
                                class="form-control @error('pengurus_barang_id') is-invalid @enderror"
                                data-target-nip="#nip_pengurus"
                                data-target-jabatan="#jabatan_pengurus">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->id }}"
                                        data-nip="{{ $p->nip }}"
                                        data-jabatan="{{ $p->jabatan }}"
                                    {{ old('pengurus_barang_id', $ruangan->pengurus_barang_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('pengurus_barang_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- NIP otomatis terisi --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" id="nip_pengurus"
                               class="form-control bg-light"
                               readonly
                               placeholder="Otomatis terisi"
                               value="{{ old('pengurus_barang_id') ? $pegawais->find(old('pengurus_barang_id'))?->nip : ($ruangan->pengurusBarang?->nip ?? '') }}">
                    </div>
                </div>

                {{-- Tanggal TTD --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal TTD</label>
                        <input type="date" name="tanggal_ttd_pengurus"
                               class="form-control"
                               value="{{ old('tanggal_ttd_pengurus', isset($ruangan) ? $ruangan->tanggal_ttd_pengurus?->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>

            {{-- Jabatan otomatis --}}
            <div class="form-group">
                <label>Jabatan Pengurus</label>
                <input type="text" id="jabatan_pengurus"
                       class="form-control bg-light" readonly
                       placeholder="Otomatis terisi dari data pegawai"
                       value="{{ isset($ruangan) ? ($ruangan->pengurusBarang?->jabatan ?? '') : '' }}">
            </div>

            {{-- ── PENANGGUNG JAWAB RUANGAN ───────────────────────────────── --}}
            <h6 class="text-muted border-bottom pb-1 mb-3 mt-4">
                <i class="fas fa-user-shield mr-1"></i> Penanggung Jawab Ruangan
            </h6>

            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Nama Penanggung Jawab <span class="text-danger">*</span></label>
                        <select name="penanggung_jawab_id"
                                id="select_pj"
                                class="form-control @error('penanggung_jawab_id') is-invalid @enderror"
                                data-target-nip="#nip_pj"
                                data-target-jabatan="#jabatan_pj">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->id }}"
                                        data-nip="{{ $p->nip }}"
                                        data-jabatan="{{ $p->jabatan }}"
                                    {{ old('penanggung_jawab_id', $ruangan->penanggung_jawab_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('penanggung_jawab_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" id="nip_pj"
                               class="form-control bg-light" readonly
                               placeholder="Otomatis terisi"
                               value="{{ old('penanggung_jawab_id') ? $pegawais->find(old('penanggung_jawab_id'))?->nip : ($ruangan->penanggungJawab?->nip ?? '') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal TTD</label>
                        <input type="date" name="tanggal_ttd_pj"
                               class="form-control"
                               value="{{ old('tanggal_ttd_pj', isset($ruangan) ? $ruangan->tanggal_ttd_pj?->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Jabatan Penanggung Jawab</label>
                <input type="text" id="jabatan_pj"
                       class="form-control bg-light" readonly
                       placeholder="Otomatis terisi dari data pegawai"
                       value="{{ isset($ruangan) ? ($ruangan->penanggungJawab?->jabatan ?? '') : '' }}">
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2"
                          placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $ruangan->keterangan ?? '') }}</textarea>
            </div>

        </div>{{-- end card-body --}}

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i>
                {{ isset($ruangan) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('ruangan.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
        </div>

    </form>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Auto-load NIP + Jabatan saat dropdown pegawai dipilih.
 * Satu fungsi, dipakai untuk kedua dropdown (pengurus & PJ).
 */
document.querySelectorAll('[data-target-nip]').forEach(function (select) {
    // Isi saat halaman pertama kali load (mode edit)
    fillFromSelect(select);

    // Isi setiap kali dipilih ulang
    select.addEventListener('change', function () {
        fillFromSelect(this);
    });
});

function fillFromSelect(select) {
    const chosen      = select.options[select.selectedIndex];
    const nipTarget   = document.querySelector(select.dataset.targetNip);
    const jabTarget   = document.querySelector(select.dataset.targetJabatan);

    if (chosen && chosen.value) {
        if (nipTarget) nipTarget.value = chosen.dataset.nip   || '';
        if (jabTarget) jabTarget.value = chosen.dataset.jabatan || '';
    } else {
        if (nipTarget) nipTarget.value = '';
        if (jabTarget) jabTarget.value = '';
    }
}
</script>
@endpush
