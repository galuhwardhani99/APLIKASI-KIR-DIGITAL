<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 10px; color: #000; }
    .header-title { text-align: center; margin-bottom: 10px; }
    .header-title div { font-size: 13px; font-weight: bold; }
    
    table.info { width: 100%; margin-bottom: 8px; border-collapse: collapse; }
    table.info td { padding: 2px 4px; vertical-align: top; font-size: 10px; }
    
    table.data { width: 100%; border-collapse: collapse; margin-top: 5px; table-layout: fixed; }
    table.data th, table.data td { 
        border: 1px solid #000; 
        padding: 4px 2px; 
        font-size: 9px; 
        word-wrap: break-word; 
        overflow-wrap: break-word;
    }
    table.data th { text-align: center; background-color: #f0f0f0; font-weight: bold; }
    table.data td { text-align: center; vertical-align: middle; }
    table.data td.left { text-align: left; }

    /* Kolom nomor ultra sempit */
    .col-no {
        width: 2.5% !important;
        padding: 4px 0px !important;
        text-align: center;
        font-size: 8.5px;
    }

    /* Styling NIBAR & Register agar rapi 2 baris persis seperti di gambar */
    .col-code {
        font-size: 8px !important;
        line-height: 1.15;
        letter-spacing: -0.2px;
        word-break: break-all;
        padding: 4px 1px !important;
    }

    /* Style khusus header Jumlah & Satuan */
    .col-small-header {
        font-size: 8px !important;
        padding: 2px 0px !important;
    }

    .catatan { margin-top: 15px; font-size: 9.5px; }
    table.ttd-table { width: 100%; margin-top: 25px; border-collapse: collapse; }
    table.ttd-table td { text-align: center; vertical-align: top; padding: 4px; width: 50%; font-size: 10px; }
    .ttd-space { height: 45px; }
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
            <th rowspan="2" class="col-no" style="width: 2.5%;">No</th>
            <th rowspan="2" style="width: 15.5%;">NIBAR</th>
            <th rowspan="2" style="width: 15.5%;">Nomor Register</th>
            <th rowspan="2" style="width: 6.5%;">Kode Barang</th>
            <th rowspan="2" style="width: 14%;">Nama Barang</th>
            <th rowspan="2" style="width: 14%;">Spesifikasi Nama Barang</th>
            <th colspan="2" style="width: 17%;">Spesifikasi Barang</th>
            <th rowspan="2" class="col-small-header" style="width: 3.5%;">Jumlah</th>
            <th rowspan="2" class="col-small-header" style="width: 3.5%;">Satuan</th>
            <th rowspan="2" style="width: 8%;">Ket</th>
        </tr>
        <tr>
            <th style="width: 11%;">Merk/Tipe</th>
            <th style="width: 6%;">Tahun Perolehan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kir->items as $item)
            @php $aset = $item->aset; @endphp
            <tr>
                <td class="col-no">{{ $loop->iteration }}</td>
                <td class="col-code">{{ $aset->nibar }}</td>
                <td class="col-code">{{ $aset->nomor_register }}</td>
                <td>{{ $aset->kode_barang }}</td>
                <td class="left">{{ $aset->nama_barang }}</td>
                <td class="left">{{ $aset->spesifikasi_nama_barang }}</td>
                <td>{{ $aset->merk_tipe }}</td>
                <td>{{ $aset->tahun_perolehan }}</td>
                <td>{{ rtrim(rtrim(number_format($aset->jumlah, 2, '.', ''), '0'), '.') }}</td>
                <td>{{ $aset->satuan }}</td>
                <td style="text-align: center; vertical-align: middle; word-wrap: break-word;">
                    {{ $item->aset->keterangan ?? '-' }}
                </td>
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