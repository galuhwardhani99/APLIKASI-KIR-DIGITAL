<?php

namespace App\Http\Controllers;

use App\Models\Kir;
use App\Models\KirItem;
use App\Models\Aset;
use App\Models\Ruangan;
use App\Models\KlasifikasiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KirController extends Controller
{
    // ── STEP 1: Form pilih ruangan ────────────────────────────────────────
    public function index()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        return view('kir.index', compact('ruangans'));
    }

    // ── STEP 1 POST ───────────────────────────────────────────────────────
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

    // ── STEP 2: Daftar KIR per ruangan ───────────────────────────────────
    public function list(Ruangan $ruangan)
    {
        $kirs = Kir::where('ruangan_id', $ruangan->id)
                   ->withCount('items')
                   ->orderByDesc('tanggal')
                   ->orderByDesc('id')
                   ->get();

        $kirs->load(['items.aset.klasifikasiBarang.parent.parent.parent']);

        return view('kir.list', compact('ruangan', 'kirs'));
    }

    // ── STEP 3: Form tambah KIR ───────────────────────────────────────────
    public function create(Ruangan $ruangan)
    {
        // Tahun perolehan unik dari aset
        $tahunList = Aset::whereNotNull('tahun_perolehan')
                        ->distinct()
                        ->orderByDesc('tahun_perolehan')
                        ->pluck('tahun_perolehan');

        // Sama persis dengan dropdown di form Tambah Aset — semua level,
        // tidak difilter whereHas('asets') atau level tertentu
        $klasifikasiList = KlasifikasiBarang::orderBy('kode')->get();

        return view('kir.create', compact(
            'ruangan',
            'tahunList',
            'klasifikasiList'
        ));
    }

    // ── AJAX: Filter aset ─────────────────────────────────────────────────
    public function filterAset(Request $request)
    {
        $query = Aset::with([
            'ruangan',
            'klasifikasiBarang.parent.parent.parent',
        ]);

        if ($request->filled('tahun_perolehan')) {
            $query->where('tahun_perolehan', $request->tahun_perolehan);
        }

        // Filter berdasarkan klasifikasi_barang_id (level 6)
        if ($request->filled('klasifikasi_barang_id')) {
            $query->where('klasifikasi_barang_id', $request->klasifikasi_barang_id);
        }

        if ($request->filled('kir_id')) {
            $query->whereNotIn('id', function ($q) use ($request) {
                $q->select('aset_id')
                  ->from('aset_kir')
                  ->where('kir_id', $request->kir_id);
            });
        }

        $asets = $query->orderBy('nama_barang')->get();

        return response()->json($asets->map(function ($a) {
            $level6      = $a->klasifikasiBarang;
            $kodeLengkap = $level6
                ? "{$level6->kode}.{$a->kode_barang}"
                : $a->kode_barang;

            return [
                'id'                      => $a->id,
                'nibar'                   => $a->nibar,
                'nomor_register'          => $a->nomor_register,
                'kode_barang'             => $a->kode_barang,
                'kode_lengkap'            => $kodeLengkap,
                'nama_barang'             => $a->nama_barang,
                'spesifikasi_nama_barang' => $a->spesifikasi_nama_barang,
                'merk_tipe'               => $a->merk_tipe,
                'tahun_perolehan'         => $a->tahun_perolehan,
                'jumlah'                  => $a->jumlah,
                'satuan'                  => $a->satuan,
                'kondisi'                 => $a->kondisi,
                'ruangan'                 => $a->ruangan?->nama_ruangan ?? '-',
            ];
        }));
    }

    // ── STEP 3 POST: Simpan KIR ───────────────────────────────────────────
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

    // ── Detail KIR ────────────────────────────────────────────────────────
    public function show(Kir $kir)
    {
        $kir->load([
            'ruangan.pengurusBarang',
            'ruangan.penanggungJawab',
            'items.aset.klasifikasiBarang.parent.parent.parent',
        ]);
        return view('kir.show', compact('kir'));
    }

    // ── Hapus KIR ─────────────────────────────────────────────────────────
    public function destroy(Kir $kir)
    {
        $ruanganId = $kir->ruangan_id;
        $kir->delete();
        return redirect()
            ->route('kir.list', $ruanganId)
            ->with('success', 'KIR berhasil dihapus.');
    }
}   