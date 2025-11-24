<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\StokGudang;
use App\Models\StokOutlet;
use App\Models\PermintaanStok;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BarangKeluarController extends Controller
{
    public function store(Request $request)
    {
        if ($request->user()->role !== 'gudang') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_stok,id'
        ]);

        $permintaan = PermintaanStok::findOrFail($request->permintaan_id);

        if ($permintaan->status !== 'diajukan') {
            return response()->json(['message' => 'Permintaan sudah diproses'], 400);
        }

        $stokGudang = StokGudang::where('bahan_id', $permintaan->bahan_id)->first();

        if (!$stokGudang || $stokGudang->stok < $permintaan->jumlah) {
            return response()->json(['message' => 'Stok gudang tidak cukup'], 400);
        }

        $keluar = BarangKeluar::create([
            'bahan_id' => $permintaan->bahan_id,
            'outlet_id' => $permintaan->outlet_id,
            'jumlah' => $permintaan->jumlah,
            'tanggal_keluar' => now(),
            'status' => 'dikirim'
        ]);

        $stokGudang->decrement('stok', $permintaan->jumlah);

        StokOutlet::updateOrCreate(
            ['outlet_id' => $permintaan->outlet_id, 'bahan_id' => $permintaan->bahan_id],
            ['stok' => DB::raw("COALESCE(stok, 0) + {$permintaan->jumlah}")]
        );

        $permintaan->update(['status' => 'dikirim']);

        return response()->json([
            'message' => 'Barang berhasil dikirim!',
            'data' => $keluar->load(['bahan', 'outlet'])
        ], 201);
    }

    public function terima(Request $request, $id)
    {
        $keluar = BarangKeluar::findOrFail($id);

        if ($keluar->status !== 'dikirim') {
            return response()->json(['message' => 'Barang sudah diterima'], 400);
        }

        if ($request->hasFile('bukti_foto')) {
            $uploaded = Cloudinary::upload($request->file('bukti_foto')->getRealPath(), [
                'folder' => 'bukti_penerimaan'
            ]);
            $keluar->bukti_foto = $uploaded->getSecurePath();
        }

        $keluar->status = 'diterima';
        $keluar->save();

        return response()->json(['message' => 'Barang berhasil diterima!']);
    }
}