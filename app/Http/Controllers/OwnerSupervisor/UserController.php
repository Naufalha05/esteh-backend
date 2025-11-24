<?php

namespace App\Http\Controllers\OwnerSupervisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $roles = ['owner', 'supervisor'];
        if (!in_array(auth('api')->user()->role, $roles)) return response()->json(['message' => 'Akses ditolak'], 403);

        $request->validate([
            'username'   => 'required|unique:users',
            'password'   => 'required|min:6',
            'role'       => 'required|in:karyawan',
            'outlet_id'  => 'required|exists:outlets,id'
        ]);

        $user = User::create([
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'outlet_id' => $request->outlet_id
        ]);

        return response()->json($user, 201);
    }

    public function index()
    {
        return User::with('outlet')->get();
    }
}