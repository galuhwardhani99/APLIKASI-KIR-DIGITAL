<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }
    .header-title { text-align: center; margin-bottom: 10px; }
    .header-title div { font-size: 13px; font-weight: bold; }
    table.info { width: 100%; margin-bottom: 8px; border-collapse: collapse; }
    table.info td { padding: 2px 4px; vertical-align: top; font-size: 11px; }
    table.data { width: 100%; border-collapse: collapse; margin-top: 5px; table-layout: fixed; }
    table.data th, table.data td { border: 1px solid #000; padding: 4px; font-size: 10px; word-wrap: break-word; }
    table.data th { text-align: center; background-color: #f0f0f0; font-weight: bold; }
    table.data td { text-align: center; vertical-align: middle; }
    table.data td.left { text-align: left; }

    /* Kolom NIBAR & Nomor Register: wrap rapi, jangan melebar ke kanan */
    table.data th:nth-child(2), table.data td:nth-child(2),
    table.data th:nth-child(3), table.data td:nth-child(3) {
        width: 60px;
        word-break: break-all;
        text-align: center;
    }

    .catatan { margin-top: 15px; font-size: 10px; }
    table.ttd-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
    table.ttd-table td { text-align: center; vertical-align: top; padding: 4px; width: 50%; font-size: 11px; }
    .ttd-space { height: 50px; }
    .underline { text-decoration: underline; font-weight: bold; }
</style>
</head>
<body>

<div class="header-title">
    <div>PEMERINTAH KOTA KEDIRI</div>
    <div>KARTU INVENTARIS RUANGAN (KIR)</div>
    <div>BARANG MILIK DAERAH</div>
</div>

<table class="info">
    <tr>
        <td style="width:150px;">PENGGUNA BARANG</td>
        <td style="width:10px;">:</td>
        <td>{{ strtoupper($penggunaBarang) }}</td>
    </tr>
    <tr>
        <td>KODE LOKASI</td>
        <td>:</td>
        <td>{{ $kodeLokasi }}</td>
    </tr>
    <tr>
        <td>RUANGAN</td>
        <td>:</td>
        <td>{{ $kir->ruangan->nama_ruangan }}</td>
    </tr>
    <tr>
        <td>PERIODE</td>
        <td>:</td>
        <td>{{ $periode }}</td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">NIBAR</th>
            <th rowspan="2">Nomor Register</th>
            <th rowspan="2">Kode Barang</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">Spesifikasi Nama Barang</th>
            <th colspan="2">Spesifikasi Barang</th>
            <th rowspan="2">Jumlah</th>
            <th rowspan="2">Satuan</th>
            <th rowspan="2">Ket</th>
        </tr>
        <tr>
            <th>Merk/Tipe</th>
            <th>Tahun Perolehan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kir->items as $item)
            @php $aset = $item->aset; @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $aset->nibar }}</td>
                <td>{{ $aset->nomor_register }}</td>
                <td>{{ $aset->kode_barang }}</td>
                <td class="left">{{ $aset->nama_barang }}</td>
                <td class="left">{{ $aset->spesifikasi_nama_barang }}</td>
                <td>{{ $aset->merk_tipe }}</td>
                <td>{{ $aset->tahun_perolehan }}</td>
                <td>{{ rtrim(rtrim(number_format($aset->jumlah, 2, '.', ''), '0'), '.') }}</td>
                <td>{{ $aset->satuan }}</td>
                <td class="left">{{ $aset->keterangan }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11">Belum ada aset pada KIR ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="catatan">
    Catatan : Tidak dibenarkan memindahkan barang-barang yang ada pada daftar barang ini tanpa sepengetahuan
    pengurus barang pengguna dan penanggung jawab ruangan
</div>

<table class="ttd-table">
    <tr>
        <td></td>
        <td>Kediri, {{ \Carbon\Carbon::parse($tanggalTtd)->locale('id')->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td>Pengurus Barang</td>
        <td>Penanggungjawab Ruangan</td>
    </tr>
    <tr>
        <td class="ttd-space"></td>
        <td class="ttd-space"></td>
    </tr>
    <tr>
        <td class="underline">{{ $penandatanganKiri->nama ?? '.......................' }}</td>
        <td class="underline">{{ $penandatanganKanan->nama ?? '.......................' }}</td>
    </tr>
    <tr>
        <td>NIP. {{ $penandatanganKiri->nip ?? '-' }}</td>
        <td>NIP. {{ $penandatanganKanan->nip ?? '-' }}</td>
    </tr>
</table>

</body>
</html>