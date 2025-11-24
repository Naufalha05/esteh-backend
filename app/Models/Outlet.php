<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['nama', 'alamat', 'is_active'];

    public function users() { return $this->hasMany(User::class); }
    public function stokOutlet() { return $this->hasMany(StokOutlet::class); }
    public function transaksi() { return $this->hasMany(Transaksi::class); }
    public function barangKeluar() { return $this->hasMany(BarangKeluar::class); }
    public function permintaanStok() { return $this->hasMany(PermintaanStok::class); }
    public function pemasukanHarian() { return $this->hasMany(PemasukanHarian::class); }
}