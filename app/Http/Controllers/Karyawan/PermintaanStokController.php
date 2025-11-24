<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanStok;
use Illuminate\Http\Request;

class PermintaanStokController extends Controller
{
    public function store(Request $request)
    {
        $user = auth('api')->user();
        if ($user->role !== 'karyawan') return response()->json(['message' => 'Akses ditolak'], 403);

        $request->validate([
            'bahan_id' => 'required|exists:bahan,id',
            'jumlah'   => 'required|numeric|min:0.1'
        ]);

        $permintaan = PermintaanStok::create([
            'outlet_id' => $user->outlet_id,
            'bahan_id'  => $request->bahan_id,
            'jumlah'    => $request->jumlah,
            'status'    => 'diajukan'
        ]);

        return response()->json($permintaan->load('bahan'), 201);
    }

    public function index()
    {
        $user = auth('api')->user();
        return PermintaanStok::where('outlet_id', $user->outlet_id)
            ->with('bahan')
            ->latest()
            ->get();
    }
}