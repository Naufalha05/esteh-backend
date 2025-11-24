<?php

namespace App\Http\Controllers\OwnerSupervisor;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        return Outlet::withCount('users')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $outlet = Outlet::create($validated);

        return response()->json([
            'message' => 'Outlet berhasil ditambahkan!',
            'data' => $outlet
        ], 201);
    }

    public function show(Outlet $outlet)
    {
        return $outlet->load('users');
    }

    public function update(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $outlet->update($validated);

        return response()->json([
            'message' => 'Outlet berhasil diupdate!',
            'data' => $outlet
        ]);
    }

    public function destroy(Outlet $outlet)
    {
        $outlet->delete();
        return response()->json(['message' => 'Outlet berhasil dihapus!']);
    }
}