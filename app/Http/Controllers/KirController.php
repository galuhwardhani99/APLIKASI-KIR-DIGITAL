<?php

namespace App\Http\Controllers;

use App\Models\Kir;
use App\Models\KirItem;
use App\Models\Aset;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KirController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        return view('kir.index', compact('ruangans'));
    }

    public function pilihRuangan(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangans,id',
        ], [
            'ruangan_id.required' => 'Silahkan pilih ruangan.',
            'ruangan_id.exists'   => 'Ruangan tidak ditemukan.',
        ]);

        return redirect()->route('kir.list', $request->ruangan_id);
    }

    public function list(Ruangan $ruangan)
    {
        $kirs = Kir::where('ruangan_id', $ruangan->id)
                   ->withCount('items')
                   ->orderByDesc('tanggal')
                   ->orderByDesc('id')
                   ->get();

        $kirs->load(['items.aset.klasifikasiBarang.parent.parent.parent']);

        // Kelompokkan & urutkan aset di tiap KIR sesuai pola Daftar Aset
        $kirs->each(function ($kir) {
            $kir->setRelation('items', $this->enrichAndSortItems($kir->items));
        });

        return view('kir.list', compact('ruangan', 'kirs'));
    }

    public function create(Ruangan $ruangan)
    {
        $tahunList = Aset::whereNotNull('tahun_perolehan')
                        ->distinct()
                        ->orderByDesc('tahun_perolehan')
                        ->pluck('tahun_perolehan');

        $jenisList = Aset::whereNotNull('jenis')
                        ->distinct()
                        ->orderBy('jenis')
                        ->pluck('jenis');

        $kodeList = Aset::whereNotNull('kode_barang')
                        ->select('kode_barang', 'nama_barang')
                        ->distinct()
                        ->orderBy('kode_barang')
                        ->get();

        return view('kir.create', compact('ruangan', 'tahunList', 'jenisList', 'kodeList'));
    }

    public function filterAset(Request $request)
    {
        $query = Aset::with('ruangan');

        if ($request->filled('tahun_perolehan')) {
            $query->where('tahun_perolehan', $request->tahun_perolehan);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kode_barang')) {
            $query->where('kode_barang', $request->kode_barang);
        }
        if ($request->filled('kir_id')) {
            $query->whereNotIn('id', function ($q) use ($request) {
                $q->select('aset_id')
                  ->from('aset_kir')
                  ->where('kir_id', $request->kir_id);
            });
        }

        $asets = $query->orderBy('nama_barang')->get();

        return response()->json($asets->map(fn($a) => [
            'id'                      => $a->id,
            'nibar'                   => $a->nibar,
            'nomor_register'          => $a->nomor_register,
            'kode_barang'             => $a->kode_barang,
            'nama_barang'             => $a->nama_barang,
            'spesifikasi_nama_barang' => $a->spesifikasi_nama_barang,
            'merk_tipe'               => $a->merk_tipe,
            'tahun_perolehan'         => $a->tahun_perolehan,
            'jumlah'                  => $a->jumlah,
            'satuan'                  => $a->satuan,
            'kondisi'                 => $a->kondisi,
            'jenis'                   => $a->jenis,
            'ruangan'                 => $a->ruangan?->nama_ruangan ?? '-',
        ]));
    }

    public function store(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'aset_ids'   => 'required|array|min:1',
            'aset_ids.*' => 'exists:asets,id',
            'tanggal'    => 'required|date',
        ], [
            'aset_ids.required' => 'Pilih minimal 1 aset.',
            'aset_ids.min'      => 'Pilih minimal 1 aset.',
            'tanggal.required'  => 'Tanggal KIR wajib diisi.',
        ]);

        $jumlahAset = count($request->aset_ids);

        DB::transaction(function () use ($request, $ruangan) {
            $kir = Kir::create([
                'ruangan_id'          => $ruangan->id,
                'pengguna_barang'     => null,
                'pengurus_barang_id'  => null,
                'penanggung_jawab_id' => null,
                'tanggal'             => $request->tanggal,
                'keterangan'          => null,
            ]);

            foreach ($request->aset_ids as $asetId) {
                KirItem::create([
                    'kir_id'  => $kir->id,
                    'aset_id' => $asetId,
                ]);
            }
        });

        return redirect()
            ->route('kir.list', $ruangan->id)
            ->with('success', 'KIR berhasil disimpan. ' . $jumlahAset . ' aset telah ditambahkan ke KIR.');
    }

    public function show(Kir $kir)
    {
        $kir->load([
            'ruangan.pengurusBarang',
            'ruangan.penanggungJawab',
            'items.aset.klasifikasiBarang.parent.parent.parent',
        ]);

        $kir->setRelation('items', $this->enrichAndSortItems($kir->items));

        return view('kir.show', compact('kir'));
    }

    public function destroy(Kir $kir)
    {
        $ruanganId = $kir->ruangan_id;
        $kir->delete();
        return redirect()
            ->route('kir.list', $ruanganId)
            ->with('success', 'KIR berhasil dihapus.');
    }

    /**
     * Tambahkan label level3-6 + kode_barang_lengkap ke tiap $item->aset,
     * lalu urutkan persis seperti pola Daftar Aset (biar RowGroup nyambung).
     */
    private function enrichAndSortItems($items)
    {
        $items = $items->map(function ($item) {
            $aset   = $item->aset;
            $level6 = $aset->klasifikasiBarang;
            $level5 = $level6?->parent;
            $level4 = $level5?->parent;
            $level3 = $level4?->parent;

            $aset->level3_label = $level3 ? "{$level3->kode} — {$level3->nama}" : 'BELUM DIKATEGORIKAN';
            $aset->level4_label = $level4 ? "{$level4->kode} — {$level4->nama}" : 'BELUM DIKATEGORIKAN';
            $aset->level5_label = $level5 ? "{$level5->kode} — {$level5->nama}" : 'BELUM DIKATEGORIKAN';
            $aset->level6_label = $level6 ? "{$level6->kode} — {$level6->nama}" : 'BELUM DIKATEGORIKAN';

            $aset->kode_barang_lengkap = $level6
                ? "{$level6->kode}.{$aset->kode_barang}"
                : $aset->kode_barang;

            return $item;
        });

        return $items->sortBy(function ($item) {
            $aset       = $item->aset;
            $kode       = optional($aset->klasifikasiBarang)->kode ?? '';
            $paddedKode = collect(explode('.', $kode))
                ->filter(fn ($s) => $s !== '')
                ->map(fn ($s) => str_pad($s, 2, '0', STR_PAD_LEFT))
                ->implode('.');

            $itemKode = str_pad($aset->kode_barang ?? '000', 3, '0', STR_PAD_LEFT);

            return $paddedKode . '.' . $itemKode;
        })->values();
    }
}