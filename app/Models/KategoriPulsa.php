<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPulsa extends Model
{
    protected $guarded = [];

    public function pulsa()
    {
        return $this->hasMany(Pulsa::class);
    }
}
