<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\ItemTransaksi;
use App\Models\StokOutlet;
use App\Models\PemasukanHarian;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        $user = auth('api')->user();
        if ($user->role !== 'karyawan') return response()->json(['message' => 'Akses ditolak'], 403);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.quantity' => 'required|integer|min:1',
            'metode_bayar' => 'required|in:tunai,qris',
            'bukti_qris' => 'nullable|image|mimes:jpeg,png,jpg|max:5048'
        ]);

        return DB::transaction(function () use ($request, $user) {
            $total = 0;
            $items = [];

            foreach ($request->items as $item) {
                $produk = Produk::with('komposisi.bahan')->findOrFail($item['produk_id']);
                $subtotal = $produk->harga * $item['quantity'];
                $total += $subtotal;

                foreach ($produk->komposisi as $k) {
                    $stok = StokOutlet::firstOrCreate(
                        ['outlet_id' => $user->outlet_id, 'bahan_id' => $k->bahan_id],
                        ['stok' => 0]
                    );

                    if ($stok->stok < $k->quantity * $item['quantity']) {
                        throw new \Exception("Stok {$k->bahan->nama} tidak cukup");
                    }
                    $stok->decrement('stok', $k->quantity * $item['quantity']);
                }

                $items[] = [
                    'produk_id' => $produk->id,
                    'quantity'  => $item['quantity'],
                    'subtotal'  => $subtotal
                ];
            }

            $bukti = null;
            if ($request->hasFile('bukti_qris')) {
                $uploaded = Cloudinary::upload($request->file('bukti_qris')->getRealPath(), [
                    'folder' => 'bukti_qris'
                ]);
                $bukti = $uploaded->getSecurePath();
            }

            $transaksi = Transaksi::create([
                'outlet_id'     => $user->outlet_id,
                'karyawan_id'   => $user->id,
                'tanggal'       => now(),
                'total'         => $total,
                'metode_bayar'  => $request->metode_bayar,
                'bukti_qris'    => $bukti
            ]);

            foreach ($items as $i) {
                $i['transaksi_id'] = $transaksi->id;
                ItemTransaksi::create($i);
            }

            PemasukanHarian::updateOrCreate(
                ['outlet_id' => $user->outlet_id, 'tanggal' => now()->format('Y-m-d')],
                ['total_pemasukan' => DB::raw("total_pemasukan + {$total}")]
            );

            return response()->json([
                'message' => 'Transaksi berhasil',
                'transaksi' => $transaksi->load('itemTransaksi.produk')
            ], 201);
        });
    }

    public function index()
    {
        $user = auth('api')->user();
        return Transaksi::where('outlet_id', $user->outlet_id)
            ->with('itemTransaksi.produk', 'karyawan')
            ->latest()
            ->get();
    }
}