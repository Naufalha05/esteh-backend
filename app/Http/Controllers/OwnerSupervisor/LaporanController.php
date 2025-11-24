<?php

namespace App\Http\Controllers\OwnerSupervisor;

use App\Http\Controllers\Controller;
use App\Exports\PenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PemasukanHarian;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pendapatan(Request $request)
    {
        $roles = ['owner', 'supervisor'];
        if (!in_array(auth('api')->user()->role, $roles)) return response()->json(['message' => 'Akses ditolak'], 403);

        $start = $request->start_date ?? now()->subDays(30)->format('Y-m-d');
        $end   = $request->end_date ?? now()->format('Y-m-d');

        $data = PemasukanHarian::whereBetween('tanggal', [$start, $end])
            ->with('outlet')
            ->get();

        return response()->json([
            'total' => $data->sum('total_pemasukan'),
            'detail' => $data
        ]);
    }

    public function export(Request $request)
    {
        $roles = ['owner', 'supervisor'];
        if (!in_array(auth('api')->user()->role, $roles)) return response()->json(['message' => 'Akses ditolak'], 403);

        return Excel::download(
            new PenjualanExport($request->start_date, $request->end_date),
            'laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}