@extends('layouts.app')

@section('title', 'Tambah KIR – ' . $ruangan->nama_ruangan)
@section('page_title', 'Tambah KIR')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kir.index') }}">KIR</a></li>
    <li class="breadcrumb-item"><a href="{{ route('kir.list', $ruangan->id) }}">{{ $ruangan->nama_ruangan }}</a></li>
    <li class="breadcrumb-item active">Tambah KIR</li>
@endsection

@section('content')

<form action="{{ route('kir.store', $ruangan->id) }}" method="POST" id="formKir">
@csrf

{{-- INFO RUANGAN --}}
<div class="card card-outline card-info mb-3">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h5 class="mb-1">
                    <i class="fas fa-door-open mr-1 text-info"></i>
                    <strong>{{ $ruangan->nama_ruangan }}</strong>
                </h5>
                <small class="text-muted">
                    Kode Lokasi: {{ $ruangan->kode_lokasi ?? '-' }} &nbsp;|&nbsp;
                    Pengguna Barang: {{ $ruangan->pengguna_barang ?? '-' }}
                </small>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <label class="font-weight-bold">
                        Tanggal KIR <span class="text-danger">*</span>
                    </label>
                    <input type="date"
                           name="tanggal"
                           id="tanggalKir"
                           class="form-control"
                           value="{{ date('Y-m-d') }}"
                           required>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER BARANG --}}
<div class="card card-outline card-primary mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-1"></i> Filter Informasi Barang
        </h3>
        <div class="card-tools">
            <small class="text-muted">Pilih filter lalu klik Tampilkan</small>
        </div>
    </div>
    <div class="card-body pb-2">
        <div class="row">

            {{-- Tahun Perolehan --}}
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tahun Perolehan</label>
                    <select id="filterTahun" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Jenis Barang --}}
            <div class="col-md-4">
                <div class="form-group">
                    <label>Jenis Barang</label>
                    <select id="filterJenis" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        @foreach($jenisList as $j)
                            <option value="{{ $j }}">
                                {{ $j === 'peralatan_mesin' ? 'Peralatan & Mesin' : 'Aset Tetap Lainnya' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Kode Barang (format: kode - nama) --}}
            <div class="col-md-4">
                <div class="form-group">
                    <label>Kode Barang</label>
                    <select id="filterKode" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        @foreach($kodeList as $k)
                            <option value="{{ $k->kode_barang }}">
                                {{ $k->kode_barang }} - {{ $k->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    Filter bisa dikombinasikan. Kosongkan semua untuk tampilkan semua aset.
                </small>
                <div>
                    <button type="button" id="btnReset" class="btn btn-secondary btn-sm mr-1">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                    <button type="button" id="btnFilter" class="btn btn-primary btn-sm">
                        <i class="fas fa-search mr-1"></i> Tampilkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL RINCIAN BARANG --}}
<div class="card card-outline card-success" id="cardTabel" style="display:none;">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mr-auto">
            <i class="fas fa-table mr-1"></i> Tabel Rincian Barang
        </h3>
        <div class="d-flex align-items-center">
            <span id="infoTerpilih" class="badge badge-success mr-2" style="display:none;">
                0 aset dipilih
            </span>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="checkAll">
                <label class="custom-control-label font-weight-bold" for="checkAll">
                    Pilih Semua
                </label>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tableRincian">
                <thead class="thead-light">
                    <tr>
                        <th width="40" class="text-center">✓</th>
                        <th>No</th>
                        <th>NIBAR</th>
                        <th>No. Register</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Spesifikasi</th>
                        <th>Merk/Tipe</th>
                        <th>Tahun</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Ruangan Saat Ini</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody id="tbodyRincian">
                    <tr id="rowLoading" style="display:none;">
                        <td colspan="14" class="text-center py-4">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Memuat data...
                        </td>
                    </tr>
                    <tr id="rowEmpty" style="display:none;">
                        <td colspan="14" class="text-center text-muted py-4">
                            Tidak ada aset yang cocok dengan filter.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <a href="{{ route('kir.list', $ruangan->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Batal
        </a>
        <button type="submit" class="btn btn-success" id="btnSimpan" disabled>
            <i class="fas fa-save mr-1"></i>
            Simpan KIR (<span id="jumlahSimpan">0</span> aset dipilih)
        </button>
    </div>
</div>

</form>

@endsection

@push('scripts')
<script>
const filterUrl = "{{ route('kir.filter-aset') }}";

const kondisiBadge = {
    baik:         'success',
    rusak_ringan: 'warning',
    rusak_berat:  'danger',
    hilang:       'dark',
};

const jenisLabel = {
    peralatan_mesin:     'Peralatan & Mesin',
    aset_tetap_lainnya:  'Aset Tetap Lainnya',
};

// Tombol Tampilkan
document.getElementById('btnFilter').addEventListener('click', loadAset);

// Tombol Reset
document.getElementById('btnReset').addEventListener('click', function () {
    document.getElementById('filterTahun').value = '';
    document.getElementById('filterJenis').value = '';
    document.getElementById('filterKode').value  = '';
    document.getElementById('cardTabel').style.display = 'none';
    document.querySelectorAll('#tbodyRincian tr.row-aset').forEach(r => r.remove());
});

// Load aset via AJAX
function loadAset() {
    const tahun = document.getElementById('filterTahun').value;
    const jenis = document.getElementById('filterJenis').value;
    const kode  = document.getElementById('filterKode').value;

    const params = new URLSearchParams();
    if (tahun) params.append('tahun_perolehan', tahun);
    if (jenis) params.append('jenis', jenis);           // ← ganti dari nama_barang
    if (kode)  params.append('kode_barang', kode);

    document.getElementById('cardTabel').style.display  = '';
    document.getElementById('rowLoading').style.display = '';
    document.getElementById('rowEmpty').style.display   = 'none';
    document.querySelectorAll('#tbodyRincian tr.row-aset').forEach(r => r.remove());

    fetch(filterUrl + '?' + params.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('rowLoading').style.display = 'none';

        if (data.length === 0) {
            document.getElementById('rowEmpty').style.display = '';
            return;
        }

        const tbody = document.getElementById('tbodyRincian');
        data.forEach((aset, idx) => {
            const badge       = kondisiBadge[aset.kondisi] || 'secondary';
            const kondisiText = aset.kondisi ? aset.kondisi.replace(/_/g, ' ') : '-';
            const jenisText   = jenisLabel[aset.jenis] || (aset.jenis ?? '-');

            const row = document.createElement('tr');
            row.className = 'row-aset';
            row.innerHTML = `
                <td class="text-center align-middle">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                               class="custom-control-input cb-aset"
                               id="cb_${aset.id}"
                               name="aset_ids[]"
                               value="${aset.id}">
                        <label class="custom-control-label" for="cb_${aset.id}"></label>
                    </div>
                </td>
                <td>${idx + 1}</td>
                <td>${aset.nibar ?? '-'}</td>
                <td>${aset.nomor_register ?? '-'}</td>
                <td><small>${aset.kode_barang ?? '-'}</small></td>
                <td><strong>${aset.nama_barang}</strong></td>
                <td><small>${jenisText}</small></td>
                <td><small>${aset.spesifikasi_nama_barang ?? '-'}</small></td>
                <td>${aset.merk_tipe ?? '-'}</td>
                <td>${aset.tahun_perolehan ?? '-'}</td>
                <td>${aset.jumlah}</td>
                <td>${aset.satuan ?? '-'}</td>
                <td><span class="badge badge-light">${aset.ruangan}</span></td>
                <td><span class="badge badge-${badge}">${kondisiText}</span></td>
            `;
            tbody.appendChild(row);
        });

        bindCheckboxes();
    })
    .catch(() => {
        document.getElementById('rowLoading').style.display = 'none';
        document.getElementById('rowEmpty').style.display   = '';
    });
}

// Checkbox logic
function bindCheckboxes() {
    document.querySelectorAll('.cb-aset').forEach(cb => {
        cb.addEventListener('change', updateCount);
    });
}

function updateCount() {
    const checked = document.querySelectorAll('.cb-aset:checked').length;
    const total   = document.querySelectorAll('.cb-aset').length;

    document.getElementById('jumlahSimpan').textContent = checked;
    document.getElementById('btnSimpan').disabled = checked === 0;

    const info = document.getElementById('infoTerpilih');
    if (checked > 0) {
        info.textContent = checked + ' dari ' + total + ' dipilih';
        info.style.display = '';
    } else {
        info.style.display = 'none';
    }

    document.getElementById('checkAll').checked =
        checked > 0 && checked === total;
    document.getElementById('checkAll').indeterminate =
        checked > 0 && checked < total;
}

// Check All
document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('.cb-aset').forEach(cb => {
        cb.checked = this.checked;
    });
    updateCount();
});

// Highlight baris saat diceklis
document.getElementById('tbodyRincian').addEventListener('change', function (e) {
    if (e.target.classList.contains('cb-aset')) {
        const row = e.target.closest('tr');
        row.classList.toggle('table-success', e.target.checked);
    }
});
</script>
@endpush