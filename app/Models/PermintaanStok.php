<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanStok extends Model
{
    protected $table = 'permintaan_stok'; // atau 'permintaan_stoks' kalau nama tabelnya ada "s"

    protected $fillable = ['outlet_id', 'bahan_id', 'jumlah', 'status'];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }
}