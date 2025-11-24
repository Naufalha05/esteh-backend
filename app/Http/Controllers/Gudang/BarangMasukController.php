<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        return BarangMasuk::with('bahan')->latest()->get();
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'gudang') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'bahan_id' => 'required|exists:bahan,id',
            'jumlah'   => 'required|numeric|min:0.001',
            'supplier' => 'required|string|max:255'
        ]);

        $masuk = BarangMasuk::create([
            'bahan_id' => $request->bahan_id,
            'jumlah'   => $request->jumlah,
            'tanggal'  => now(),
            'supplier' => $request->supplier
        ]);

        StokGudang::updateOrCreate(
            ['bahan_id' => $request->bahan_id],
            ['stok' => DB::raw("COALESCE(stok, 0) + {$request->jumlah}")]
        );

        return response()->json([
            'message' => 'Barang masuk berhasil dicatat!',
            'data' => $masuk->load('bahan')
        ], 201);
    }
}