<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemasukanHarian extends Model
{
    protected $fillable = ['outlet_id', 'tanggal', 'total_pemasukan'];

    protected $dates = ['tanggal'];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}