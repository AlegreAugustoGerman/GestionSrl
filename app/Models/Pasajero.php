<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasajero extends Model {
    protected $fillable = ['dni', 'nombre', 'apellido', 'telefono', 'email'];

    public function boletos(): HasMany {
        return $this->hasMany(Boleto::class);
    }
}

