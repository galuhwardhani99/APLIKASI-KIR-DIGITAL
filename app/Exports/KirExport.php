<?php

namespace App\Exports;

use App\Models\Kir;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KirExport implements FromArray, WithEvents, WithTitle
{
    protected Kir $kir;
    protected string $periode;
    protected string $penggunaBarang;
    protected string $kodeLokasi;
    protected string $tanggalTtd;
    protected $penandatanganKiri;
    protected $penandatanganKanan;

    public function __construct(
        Kir $kir,
        string $periode,
        string $penggunaBarang,
        string $kodeLokasi,
        string $tanggalTtd,
        $penandatanganKiri,
        $penandatanganKanan
    ) {
        $this->kir                = $kir;
        $this->periode            = $periode;
        $this->penggunaBarang     = $penggunaBarang;
        $this->kodeLokasi         = $kodeLokasi;
        $this->tanggalTtd         = $tanggalTtd;
        $this->penandatanganKiri  = $penandatanganKiri;
        $this->penandatanganKanan = $penandatanganKanan;
    }

    public function array(): array
    {
        return [[]];
    }

    public function title(): string
    {
        return 'KIR';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastCol    = 'K';
                $thinBorder = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                ];

                // ── LEBAR KOLOM ──────────────────────────────────────────
                $sheet->getColumnDimension('A')->setWidth(7);    // No
                $sheet->getColumnDimension('B')->setWidth(40.6); // NIBAR
                $sheet->getColumnDimension('C')->setWidth(40.6); // Nomor Register
                $sheet->getColumnDimension('D')->setWidth(14);   // Kode Barang
                $sheet->getColumnDimension('E')->setWidth(24);   // Nama Barang
                $sheet->getColumnDimension('F')->setWidth(28);   // Spesifikasi Nama Barang
                $sheet->getColumnDimension('G')->setWidth(20);   // Merk/Tipe
                $sheet->getColumnDimension('H')->setWidth(14);   // Tahun Perolehan
                $sheet->getColumnDimension('I')->setWidth(9);    // Jumlah
                $sheet->getColumnDimension('J')->setWidth(11);   // Satuan
                $sheet->getColumnDimension('K')->setWidth(22);   // Ket

                // ── JUDUL ────────────────────────────────────────────────
                $sheet->setCellValue('A1', 'PEMERINTAH KOTA KEDIRI');
                $sheet->setCellValue('A2', 'KARTU INVENTARIS RUANGAN (KIR)');
                $sheet->setCellValue('A3', 'BARANG MILIK DAERAH');

                foreach ([1, 2, 3] as $row) {
                    $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(13);
                    $sheet->getStyle("A{$row}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // ── INFO HEADER ────────────────────────────────────────────
                $infoRows = [
                    5 => ['PENGGUNA BARANG', $this->penggunaBarang],
                    6 => ['KODE LOKASI',     $this->kodeLokasi],
                    7 => ['RUANGAN',         $this->kir->ruangan->nama_ruangan],
                    8 => ['PERIODE',         $this->periode],
                ];

                foreach ($infoRows as $row => [$label, $value]) {
                    $sheet->setCellValue("A{$row}", $label);
                    $sheet->mergeCells("A{$row}:B{$row}");

                    $sheet->setCellValue("C{$row}", ': ' . $value);
                    $sheet->mergeCells("C{$row}:{$lastCol}{$row}");

                    $sheet->getStyle("A{$row}")->getFont()->setBold(false);
                }

                // ── HEADER TABEL ───────────────────────────────────────────
                $headerRow1 = 10;
                $headerRow2 = 11;

                $sheet->setCellValue("A{$headerRow1}", 'No');
                $sheet->setCellValue("B{$headerRow1}", 'NIBAR');
                $sheet->setCellValue("C{$headerRow1}", 'Nomor Register');
                $sheet->setCellValue("D{$headerRow1}", 'Kode Barang');
                $sheet->setCellValue("E{$headerRow1}", 'Nama Barang');
                $sheet->setCellValue("F{$headerRow1}", 'Spesifikasi Nama Barang');
                $sheet->setCellValue("G{$headerRow1}", 'Spesifikasi Barang');
                $sheet->setCellValue("I{$headerRow1}", 'Jumlah');
                $sheet->setCellValue("J{$headerRow1}", 'Satuan');
                $sheet->setCellValue("K{$headerRow1}", 'Ket');

                $sheet->setCellValue("G{$headerRow2}", 'Merk/Tipe');
                $sheet->setCellValue("H{$headerRow2}", 'Tahun Perolehan');

                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'I', 'J', 'K'] as $col) {
                    $sheet->mergeCells("{$col}{$headerRow1}:{$col}{$headerRow2}");
                }
                $sheet->mergeCells("G{$headerRow1}:H{$headerRow1}");

                $sheet->getStyle("A{$headerRow1}:{$lastCol}{$headerRow2}")
                    ->getFont()->setBold(true);
                $sheet->getStyle("A{$headerRow1}:{$lastCol}{$headerRow2}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $sheet->getStyle("A{$headerRow1}:{$lastCol}{$headerRow2}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F0F0F0');
                $sheet->getStyle("A{$headerRow1}:{$lastCol}{$headerRow2}")
                    ->applyFromArray($thinBorder);

                // ── DATA ASET ─────────────────────────────────────────────
                $row = $headerRow2 + 1;
                $no  = 1;

                if ($this->kir->items->isEmpty()) {
                    $sheet->setCellValue("A{$row}", 'Belum ada aset pada KIR ini.');
                    $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
                    $sheet->getStyle("A{$row}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $row++;
                } else {
                    foreach ($this->kir->items as $item) {
                        $aset = $item->aset;

                        $jumlah = rtrim(rtrim(number_format($aset->jumlah, 2, '.', ''), '0'), '.');

                        $sheet->setCellValue("A{$row}", $no);
                        
                        // Menggunakan setCellValueExplicit agar angka panjang (seperti NIBAR & Keterangan) tetap dibaca String
                        $sheet->setCellValueExplicit("B{$row}", (string) $aset->nibar, DataType::TYPE_STRING);
                        $sheet->setCellValueExplicit("C{$row}", (string) $aset->nomor_register, DataType::TYPE_STRING);
                        $sheet->setCellValueExplicit("D{$row}", (string) $aset->kode_barang, DataType::TYPE_STRING);
                        
                        $sheet->setCellValue("E{$row}", $aset->nama_barang);
                        $sheet->setCellValue("F{$row}", $aset->spesifikasi_nama_barang);
                        $sheet->setCellValue("G{$row}", $aset->merk_tipe);
                        $sheet->setCellValue("H{$row}", $aset->tahun_perolehan);
                        $sheet->setCellValue("I{$row}", $jumlah);
                        $sheet->setCellValue("J{$row}", $aset->satuan);
                        
                        // Kolom K (Ket) dipaksa String agar tidak menjadi 1.303E+16
                        $sheet->setCellValueExplicit("K{$row}", (string) $aset->keterangan, DataType::TYPE_STRING);

                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($thinBorder);

                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->getAlignment()
                            ->setVertical(Alignment::VERTICAL_CENTER)
                            ->setWrapText(true);

                        // Alignment posisi kolom
                        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("B{$row}:C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("I{$row}:J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        
                        // Kolom K (Ket) dibuat rata tengah (Center)
                        $sheet->getStyle("K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                        $sheet->getRowDimension($row)->setRowHeight(-1);

                        $row++;
                        $no++;
                    }
                }

                // ── CATATAN ───────────────────────────────────────────────
                $row += 1;
                $catatanRow = $row;
                $sheet->setCellValue(
                    "A{$catatanRow}",
                    'Catatan : Tidak dibenarkan memindahkan barang-barang yang ada pada daftar barang ini '
                    . 'tanpa sepengetahuan pengurus barang pengguna dan penanggung jawab ruangan'
                );
                $sheet->mergeCells("A{$catatanRow}:{$lastCol}{$catatanRow}");
                $sheet->getStyle("A{$catatanRow}")->getFont()->setItalic(true)->setSize(9);

                // ── TANDA TANGAN ──────────────────────────────────────────
                $row = $catatanRow + 3;

                $tanggalText = 'Kediri, ' . Carbon::parse($this->tanggalTtd)->locale('id')->translatedFormat('d F Y');
                $sheet->setCellValue("G{$row}", $tanggalText);
                $sheet->mergeCells("G{$row}:{$lastCol}{$row}");
                $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row += 1;
                // Pengurus Barang di-merge A s.d. C agar lebih ke kiri
                $sheet->setCellValue("A{$row}", 'Pengurus Barang');
                $sheet->mergeCells("A{$row}:C{$row}");
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue("G{$row}", 'Penanggungjawab Ruangan');
                $sheet->mergeCells("G{$row}:{$lastCol}{$row}");
                $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row += 4;

                $namaKiri  = $this->penandatanganKiri->nama  ?? '.......................';
                $nipKiri   = $this->penandatanganKiri->nip   ?? '-';
                $namaKanan = $this->penandatanganKanan->nama ?? '.......................';
                $nipKanan  = $this->penandatanganKanan->nip  ?? '-';

                $sheet->setCellValue("A{$row}", $namaKiri);
                $sheet->mergeCells("A{$row}:C{$row}");
                $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setUnderline(true);
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue("G{$row}", $namaKanan);
                $sheet->mergeCells("G{$row}:{$lastCol}{$row}");
                $sheet->getStyle("G{$row}")->getFont()->setBold(true)->setUnderline(true);
                $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $row += 1;
                $sheet->setCellValue("A{$row}", 'NIP. ' . $nipKiri);
                $sheet->mergeCells("A{$row}:C{$row}");
                $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue("G{$row}", 'NIP. ' . $nipKanan);
                $sheet->mergeCells("G{$row}:{$lastCol}{$row}");
                $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}