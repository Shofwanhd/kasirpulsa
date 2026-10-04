<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $guarded = [];

    public function kategoriAkun()
    {
        return $this->belongsTo(KategoriAkun::class);
    }

    public function transfersAsal()
    {
        return $this->hasMany(Transfer::class, 'akun_asal_id');
    }

    public function transfersTujuan()
    {
        return $this->hasMany(Transfer::class, 'akun_tujuan_id');
    }

    public function saldo()
    {
        $keluar = $this->transfersAsal()
            ->selectRaw('COALESCE(SUM(nominal + admin), 0) as total')
            ->value('total');

        $masuk = $this->transfersTujuan()
            ->selectRaw('COALESCE(SUM(nominal + fee), 0) as total')
            ->value('total');

        return $this->saldoAwal + $masuk - $keluar;
    }
}
