<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Shuchkin\SimpleXLSX;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil tanggal dari filter kalender, kalau nggak ada default ke hari ini
        $date = $request->input('date', date('Y-m-d'));

        // 2. Ambil data yang HANYA diperbarui pada tanggal tersebut
        $barangs = Barang::whereDate('updated_at', $date)->get();

        $listBarang = Barang::select('code', 'nama_pupuk')->distinct()->get();

        // 3. Kirim variabel $date ke view supaya kalender tetap menunjukkan tanggal yang dipilih
        return view('barang.index', compact('barangs', 'date', 'listBarang'));
    }

    public function destroyByDate(Request $request)
    {
        // Ambil tanggal dari input, kalau kosong default ke hari ini
        $date = $request->input('date');

        if (!$date) {
            return back()->with('error', 'Pilih tanggal dulu yang mau dihapus!');
        }

        // Hapus hanya data yang updated_at sesuai tanggal tersebut
        $deletedCount = \App\Models\Barang::whereDate('updated_at', $date)->delete();

        return redirect()->route('barang.index', ['date' => $date])
            ->with('success', "Berhasil menghapus $deletedCount data pada tanggal $date.");
    }

    // TAMPILKAN FORM TAMBAH (Fungsi yang tadi error/hilang)
    public function create()
    {
        $codes = Barang::select('code')->distinct()->get();
        $names = Barang::select('nama_pupuk')->distinct()->get();
        $locators = Barang::select('locator')->distinct()->get();
        $uoms = Barang::select('uom')->distinct()->get();

        return view('barang.create', compact('codes', 'names', 'locators', 'uoms')); 
    }

    // PROSES SIMPAN DATA KE DATABASE
    public function store(Request $request)
    {
        $stok_akhir = $request->stok_awal + $request->masuk - $request->keluar;

        Barang::create([
            'code' => $request->code,
            'nama_pupuk' => $request->nama_pupuk,
            'locator' => $request->locator,
            'uom' => $request->uom,
            'stok_awal' => $request->stok_awal,
            'masuk' => $request->masuk,
            'keluar' => $request->keluar,
            'stok_total' => $stok_akhir,
        ]);

        return redirect()->route('barang.index');
    }

    // Menampilkan Form Edit
    public function edit($id)
    {
        // 1. Ambil data barang yang mau di-edit
        $barang = Barang::findOrFail($id);

        // 2. Ambil semua pilihan untuk dropdown
        $codes = Barang::select('code')->distinct()->get();
        $names = Barang::select('nama_pupuk')->distinct()->get();
        $locators = Barang::select('locator')->distinct()->get();
        $uoms = Barang::select('uom')->distinct()->get();

        // 3. Kirim SEMUA variabel ke view edit
        return view('barang.edit', compact('barang', 'codes', 'names', 'locators', 'uoms'));
    }

    // Proses Update Data ke Database
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        // Hitung Stok Akhir Otomatis
        $stok_akhir = $request->stok_awal + $request->masuk - $request->keluar;

        $barang->update([
            'code' => $request->code,
            'nama_pupuk' => $request->nama_pupuk,
            'locator' => $request->locator,
            'uom' => $request->uom,
            'stok_awal' => $request->stok_awal,
            'masuk' => $request->masuk,
            'keluar' => $request->keluar,
            'stok_total' => $stok_akhir,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data dihapus!');
    }

    public function exportCsv()
    {
        $filename = "Laporan_Stok_Saprotan_" . date('Y-m-d') . ".csv";
        $barangs = \App\Models\Barang::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($barangs) {
            $file = fopen('php://output', 'w');
            
            // 1. Header CSV
            fputcsv($file, [
                'Code', 'Name', 'Locator', 'Kategori Lokasi', 
                'Uom', 'Stock Awal', 'Masuk', 'Keluar', 
                'Stock Akhir', 'Status Stok'
            ]);

            // 2. Data Looping dengan Logika Cleaning
            foreach ($barangs as $b) {
                $masuk = $b->masuk ?? 0;
                $keluar = $b->keluar ?? 0;
                $stok_akhir = $b->stok_awal + $masuk - $keluar;

                // --- SISTEM LOGIKA KATEGORI ---
                $locator_upper = strtoupper($b->locator);
                if (str_contains($locator_upper, 'NOK')) {
                    $kategori_lokasi = 'NOK';
                } elseif (str_contains($locator_upper, 'HOLD')) {
                    $kategori_lokasi = 'HOLD';
                } else {
                    $kategori_lokasi = 'REGULER';
                }

                // --- SISTEM LOGIKA STATUS STOK ---
                $status_stok = ($stok_akhir < 500) ? 'Low Stock' : 'Safe Stock';

                fputcsv($file, [
                    $b->code,
                    $b->nama_pupuk,
                    $b->locator,
                    $kategori_lokasi,
                    $b->uom,
                    $b->stok_awal,
                    $masuk,
                    $keluar,
                    $stok_akhir,
                    $status_stok
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx',
            'sheet_index' => 'required|integer' 
        ]);

        if ($xlsx = SimpleXLSX::parse($request->file('file_excel'))) {
            $sheetIndex = $request->sheet_index;
            $rows = $xlsx->rows($sheetIndex);
            
            // Looping mulai dari index 3 (Baris ke-4 di Excel)
            foreach ($rows as $index => $row) {
                if ($index < 1) continue; // Skip header baris 1-3

                // Jika kolom Code (index 0) kosong, berhenti/lewati
                if (empty($row[0])) continue;

                $stok_awal = (int)($row[7] ?? 0);
                $masuk     = (int)($row[8] ?? 0);
                $keluar    = (int)($row[9] ?? 0);
                $stok_total = $stok_awal + $masuk - $keluar;

                // Simpan atau Update berdasarkan Code
                \App\Models\Barang::updateOrCreate(
                        ['code'       => $row[0], 
                        
                        'nama_pupuk' => \Illuminate\Support\Str::limit($row[2], 250), // Nama Barang di Kolom C
                        'locator'    => $row[5], // Locator di Kolom F
                        'uom'        => $row[6], // Uom di Kolom G
                        'stok_awal'  => $stok_awal,
                        'masuk'      => $masuk,
                        'keluar'     => $keluar,
                        'stok_total' => $stok_total,
                        'updated_at' => now(),
                    ]
                );
            }

            return back()->with('success', 'Import Excel Berhasil!');
        } else {
            return back()->with('error', SimpleXLSX::parseError());
        }
    }

    public function dashboard(Request $request)
    {
        // 1. Inisialisasi Query Dasar
        $query = Barang::query();

        if ($request->filled('search')) {
            $query->where('nama_pupuk', $request->search);
        }

        // 2. Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('updated_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // 3. Filter Dropdown (Lokasi, Satuan, Status)
        // Bagian Filter Lokasi
        if ($request->filled('locator')) {
            $locators = (array) $request->locator; // Pastikan jadi array
            $query->where(function($q) use ($locators) {
                foreach ($locators as $loc) {
                    if ($loc == 'REGULER') {
                        $q->orWhere(fn($sq) => $sq->where('locator', 'NOT LIKE', '%HOLD%')->where('locator', 'NOT LIKE', '%NOK%'));
                    } else {
                        $q->orWhere('locator', 'LIKE', '%' . $loc . '%');
                    }
                }
            });
        }

        // Bagian Filter UOM
        if ($request->filled('uom')) {
            $query->whereIn('uom', (array) $request->uom);
        }
        if ($request->filled('status')) {
            if ($request->status == 'Low Stock') {
                $query->where('stok_total', '<', 500);
            } else {
                $query->where('stok_total', '>=', 500);
            }
        }

        // 4. Ambil Data Hasil Filter
        $barangs = $query->get();

        $allLocators = Barang::select('locator')->distinct()->pluck('locator');
        $allUoms = Barang::select('uom')->distinct()->pluck('uom');

        // --- LOGIKA SCORECARDS ---
        $totalSKU = $barangs->count();
        $totalStok = $barangs->sum('stok_total');
        $lowStockItem = $barangs->where('stok_total', '<', 500);

        // --- LOGIKA DONUT CHART (KATEGORI LOKASI) ---
        $reguler = $barangs->filter(function($b) {
            $loc = strtoupper($b->locator);
            return !str_contains($loc, 'HOLD') && !str_contains($loc, 'NOK');
        })->sum('stok_total');

        $hold = $barangs->filter(fn($b) => str_contains(strtoupper($b->locator), 'HOLD'))->sum('stok_total');
        $nok = $barangs->filter(fn($b) => str_contains(strtoupper($b->locator), 'NOK'))->sum('stok_total');

        // --- LOGIKA BAR CHART (TOP 10 PRODUK) ---
        $topProduk = $barangs->sortByDesc('stok_total')->take(10);
        $labelsBar = array_values($topProduk->pluck('nama_pupuk')->toArray());
        $dataBar = array_values($topProduk->pluck('stok_total')->toArray());
        
        $colorsBar = array_values($topProduk->map(function($b) {
            return $b->stok_total < 500 ? '#EA4335' : '#4285F4';
        })->toArray());

        return view('barang.dashboard', [
            'barangs' => $barangs,
            'totalSKU' => $totalSKU,
            'totalStok' => $totalStok,
            'lowStockItem' => $lowStockItem,
            'reguler' => $reguler,
            'hold' => $hold,
            'nok' => $nok,
            'labelsBar' => $labelsBar,
            'dataBar' => $dataBar,
            'colorsBar' => $colorsBar,
            'allLocators' => $allLocators,
            'allUoms' => $allUoms,
            'request' => $request
        ]);
    }

    public function landpage()
    {
        return view('landpage');
    }
}
 