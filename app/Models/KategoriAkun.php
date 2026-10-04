<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriAkun extends Model
{
    protected $guarded = [];

    public function akun(): HasMany
    {
        return $this->hasMany(Akun::class);
    }
}
