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
    // ── STEP 1: Tampilkan form pilih ruangan ─────────────────────────────
    public function index()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        return view('kir.index', compact('ruangans'));
    }

    // ── STEP 1 POST: Redirect ke daftar KIR berdasar ruangan pilihan ─────
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

        return view('kir.list', compact('ruangan', 'kirs'));
    }

    // ── STEP 3: Form tambah KIR (filter + checkbox) ───────────────────────
    public function create(Ruangan $ruangan)
    {
        // Tahun perolehan unik
        $tahunList = Aset::whereNotNull('tahun_perolehan')
                        ->distinct()
                        ->orderByDesc('tahun_perolehan')
                        ->pluck('tahun_perolehan');

        // Jenis barang unik (sesuai kolom jenis di tabel asets)
        $jenisList = Aset::whereNotNull('jenis')
                        ->distinct()
                        ->orderBy('jenis')
                        ->pluck('jenis');

        // Kode barang + nama barang (untuk format "kode - nama")
        $kodeList = Aset::whereNotNull('kode_barang')
                        ->select('kode_barang', 'nama_barang')
                        ->distinct()
                        ->orderBy('kode_barang')
                        ->get();

        return view('kir.create', compact('ruangan', 'tahunList', 'jenisList', 'kodeList'));
    }

    // ── AJAX: Filter aset berdasarkan pilihan dropdown ────────────────────
    public function filterAset(Request $request)
    {
        $query = Aset::with('ruangan');

        if ($request->filled('tahun_perolehan')) {
            $query->where('tahun_perolehan', $request->tahun_perolehan);
        }
        if ($request->filled('jenis')) {                        // ← ganti dari nama_barang
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

    // ── STEP 3 POST: Simpan aset terpilih ke KIR baru ────────────────────
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
                'pengguna_barang'     => $ruangan->pengguna_barang,
                'pengurus_barang_id'  => $ruangan->pengurus_barang_id  ?? null, // ← tidak wajib
                'penanggung_jawab_id' => $ruangan->penanggung_jawab_id ?? null, // ← tidak wajib
                'tanggal'             => $request->tanggal,
                'keterangan'          => $request->keterangan,
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
            'items.aset',
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