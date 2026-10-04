<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pulsa extends Model
{
    protected $guarded = [];

    public function kategoriPulsa()
    {
        return $this->belongsTo(KategoriPulsa::class);
    }
}
