<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
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

    public function kategoriPulsa()
    {
        return $this->belongsTo(KategoriPulsa::class, 'kategori_pulsa_id');
    }

    public function pulsa()
    {
        return $this->belongsTo(Pulsa::class, 'pulsa_id');
    }

    public function getProfitAttribute()
    {
        return $this->amount - $this->nominal;
    }
}
