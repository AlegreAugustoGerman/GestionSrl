<?php

// app/Models/Provincia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model {
    protected $fillable = ['nombre', 'pais'];

    public function localidades(): HasMany {
        return $this->hasMany(Localidad::class);
    }
}

