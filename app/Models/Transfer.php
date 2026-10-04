<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $guarded = [];

    public function akunAsal()
    {
        return $this->belongsTo(Akun::class, 'akun_asal_id');
    }

    public function akunTujuan()
    {
        return $this->belongsTo(Akun::class, 'akun_tujuan_id');
    }
}
