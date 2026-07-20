@extends('layouts.app')

@section('title', 'Tambah KIR – ' . $ruangan->nama_ruangan)
@section('page_title', 'Tambah KIR')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kir.index') }}">KIR</a></li>
    <li class="breadcrumb-item"><a href="{{ route('kir.list', $ruangan->id) }}">{{ $ruangan->nama_ruangan }}</a></li>
    <li class="breadcrumb-item active">Tambah KIR</li>
@endsection
@push('styles')
<style>
    #tableRincian {
        table-layout: fixed;
    }

    #tableRincian th,
    #tableRincian td {
        word-wrap: break-word;
    }

    /* Kolom Checklist */
    #tableRincian th:nth-child(1),
    #tableRincian td:nth-child(1) {
        width: 45px;
        max-width: 45px;
        text-align: center;
        white-space: nowrap;
        word-wrap: normal;
    }

    /* Kolom No */
    #tableRincian th:nth-child(2),
    #tableRincian td:nth-child(2) {
        width: 55px;
        max-width: 55px;
        text-align: center;
        white-space: nowrap;
        word-wrap: normal;
    }

    /* Kolom Kode Barang */
    #tableRincian th:nth-child(3),
    #tableRincian td:nth-child(3) {
        min-width: 160px;
    }

    /* Kolom NIBAR & No. Register */
    #tableRincian th:nth-child(5),
    #tableRincian th:nth-child(6),
    #tableRincian td:nth-child(5),
    #tableRincian td:nth-child(6) {
        width: 180px;
        max-width: 180px;
        word-break: break-all;
        white-space: normal;
        text-align: center;
        vertical-align: middle;
        line-height: 1.5;
        font-size: 13px;
        padding: 8px 6px;
    }
</style>
@endpush

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
                    Kode Lokasi: {{ $ruangan->kode_lokasi ?? '-' }}
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
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">
            <i class="fas fa-filter mr-1"></i> Filter Informasi Barang
        </h3>
        <small class="text-muted">Pilih filter lalu klik Tampilkan</small>
    </div>
    <div class="card-body pb-2">
        <div class="row">

            {{-- 1. Tahun Perolehan --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">Tahun Perolehan</label>
                    <select id="filterTahun" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 2. Jenis Aset (klasifikasi BMD) – sama persis dengan di form aset --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">
                        Jenis Aset <small class="text-muted">(klasifikasi BMD)</small>
                    </label>
                    <select id="filterKlasifikasi" class="form-control">
                        <option value="">-- Pilih Jenis Barang --</option>
                        @foreach($klasifikasiList as $k)
                            <option value="{{ $k->id }}">
                                {{ $k->kode }} — {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>

        <div class="row mt-1">
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
                        <th class="text-center align-middle">✓</th>
                        <th class="text-center align-middle" style="width:40px">No</th>
                        <th class="align-middle">Kode Barang</th>
                        <th class="align-middle">Nama Barang</th>
                        <th class="align-middle">NIBAR</th>
                        <th class="align-middle">No. Register</th>
                        <th class="align-middle">Spesifikasi Nama Barang</th>
                        <th class="align-middle">Merk/Tipe</th>
                        <th class="text-center align-middle">Tahun Perolehan</th>
                        <th class="text-center align-middle">Jumlah</th>
                        <th class="align-middle">Satuan</th>
                        <th class="text-center align-middle">Kondisi</th>
                        <th class="align-middle">Ruangan Saat Ini</th>
                    </tr>
                </thead>
                <tbody id="tbodyRincian">
                    <tr id="rowLoading" style="display:none;">
                        <td colspan="13" class="text-center py-4">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Memuat data...
                        </td>
                    </tr>
                    <tr id="rowEmpty" style="display:none;">
                        <td colspan="13" class="text-center text-muted py-4">
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

// ── Penyimpanan pilihan aset (bertahan walau filter/tabel berganti) ─────
const selectedAset = new Map(); // key: aset.id, value: data aset

// ── Tombol Tampilkan ─────────────────────────────────────────────────────
document.getElementById('btnFilter').addEventListener('click', loadAset);

// ── Tombol Reset ─────────────────────────────────────────────────────────
document.getElementById('btnReset').addEventListener('click', function () {
    document.getElementById('filterTahun').value       = '';
    document.getElementById('filterKlasifikasi').value = '';
    document.getElementById('cardTabel').style.display = 'none';
    document.querySelectorAll('#tbodyRincian tr.row-aset').forEach(r => r.remove());
    // Catatan: reset filter TIDAK menghapus aset yang sudah terpilih.
});

// ── Load aset via AJAX ───────────────────────────────────────────────────
function loadAset() {
    const tahun       = document.getElementById('filterTahun').value;
    const klasifikasi = document.getElementById('filterKlasifikasi').value;

    const params = new URLSearchParams();
    if (tahun)       params.append('tahun_perolehan',       tahun);
    if (klasifikasi) params.append('klasifikasi_barang_id', klasifikasi);

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
            updateCount();
            return;
        }

        const tbody = document.getElementById('tbodyRincian');

        data.forEach((aset, idx) => {
            const badge       = kondisiBadge[aset.kondisi] || 'secondary';
            const kondisiText = aset.kondisi
                ? aset.kondisi.replace(/_/g, ' ')
                : '-';

            const kodeSegments = aset.kode_lengkap
                ? aset.kode_lengkap.split('.').filter(s => s !== '')
                : [];
            const kodeHtml = kodeSegments.length
                ? kodeSegments.map(s =>
                    `<span class="badge badge-secondary"
                           style="font-size:0.75rem; font-weight:600; margin:1px;">
                        ${s}
                    </span>`
                  ).join('')
                : '-';

            const isChecked = selectedAset.has(aset.id);

            const row = document.createElement('tr');
            row.className = 'row-aset';
            if (isChecked) row.classList.add('table-success');
            row.innerHTML = `
                <td class="text-center align-middle">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                               class="custom-control-input cb-aset"
                               id="cb_${aset.id}"
                               data-id="${aset.id}"
                               ${isChecked ? 'checked' : ''}>
                        <label class="custom-control-label" for="cb_${aset.id}"></label>
                    </div>
                </td>
                <td class="text-center align-middle">${idx + 1}</td>
                <td>
                    <div style="display:flex; flex-wrap:wrap; gap:2px;">
                        ${kodeHtml}
                    </div>
                </td>
                <td><strong>${aset.nama_barang}</strong></td>
                <td>${aset.nibar ?? '-'}</td>
                <td><small>${aset.nomor_register ?? '-'}</small></td>
                <td><small>${aset.spesifikasi_nama_barang ?? '-'}</small></td>
                <td>${aset.merk_tipe ?? '-'}</td>
                <td class="text-center">${aset.tahun_perolehan ?? '-'}</td>
                <td class="text-center">${aset.jumlah}</td>
                <td>${aset.satuan ?? '-'}</td>
                <td class="text-center">
                    <span class="badge badge-${badge}">${kondisiText}</span>
                </td>
                <td>
                    <span class="badge badge-light">${aset.ruangan}</span>
                </td>
            `;
            tbody.appendChild(row);

            // simpan/refresh data aset (untuk keperluan submit & tampilan)
            row.querySelector('.cb-aset').addEventListener('change', function () {
                toggleAset(aset, this.checked, row);
            });
        });

        syncCheckAllState();
    })
    .catch(() => {
        document.getElementById('rowLoading').style.display = 'none';
        document.getElementById('rowEmpty').style.display   = '';
    });
}

// ── Toggle satu aset masuk/keluar dari pilihan ───────────────────────────
function toggleAset(aset, checked, row) {
    if (checked) {
        selectedAset.set(aset.id, aset);
        if (row) row.classList.add('table-success');
    } else {
        selectedAset.delete(aset.id);
        if (row) row.classList.remove('table-success');
    }
    rebuildHiddenInputs();
    updateCount();
    syncCheckAllState();
}

// ── Bangun ulang hidden input aset_ids[] sesuai isi selectedAset ─────────
function rebuildHiddenInputs() {
    document.querySelectorAll('.hidden-aset-id').forEach(el => el.remove());

    const form = document.getElementById('formKir');
    selectedAset.forEach((aset, id) => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'aset_ids[]';
        input.value = id;
        input.className = 'hidden-aset-id';
        form.appendChild(input);
    });
}

// ── Update tampilan jumlah & tombol simpan ───────────────────────────────
function updateCount() {
    const checked = selectedAset.size;

    document.getElementById('jumlahSimpan').textContent = checked;
    document.getElementById('btnSimpan').disabled       = checked === 0;

    const info = document.getElementById('infoTerpilih');
    if (checked > 0) {
        info.textContent   = checked + ' aset dipilih (total, termasuk dari filter lain)';
        info.style.display = '';
    } else {
        info.style.display = 'none';
    }
}

// ── Samakan status checkbox "Pilih Semua" dengan baris yang tampil ──────
function syncCheckAllState() {
    const visibleBoxes = document.querySelectorAll('.cb-aset');
    const visibleChecked = document.querySelectorAll('.cb-aset:checked').length;

    const checkAll = document.getElementById('checkAll');
    checkAll.checked = visibleBoxes.length > 0 && visibleChecked === visibleBoxes.length;
    checkAll.indeterminate = visibleChecked > 0 && visibleChecked < visibleBoxes.length;
}

// ── Check All (hanya memengaruhi baris yang sedang tampil) ───────────────
document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('#tbodyRincian tr.row-aset').forEach(row => {
        const cb = row.querySelector('.cb-aset');
        cb.checked = this.checked;
        const id = parseInt(cb.dataset.id, 10);
        // ambil data aset dari row (re-fetch dari selectedAset kalau ada, atau simpan sementara)
        toggleAset({ id: id, ...extractRowData(row) }, this.checked, row);
    });
});

// ── Ambil data minimal dari baris (fallback kalau perlu) ─────────────────
function extractRowData(row) {
    return {}; // toggleAset hanya butuh id untuk hidden input; data lengkap sudah tersimpan saat render pertama
}

// ── Submit ────────────────────────────────────────────────────────────────
document.getElementById('formKir').addEventListener('submit', function () {
    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
});
</script>
@endpush