<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Viaje extends Model {
    protected $fillable = ['ruta_id', 'colectivo_id', 'fecha', 'hora_salida', 'hora_llegada', 'precio', 'estado'];

    public function ruta(): BelongsTo {
        return $this->belongsTo(Ruta::class);
    }

    public function colectivo(): BelongsTo {
        return $this->belongsTo(Colectivo::class);
    }

    // Muchos a Muchos con Choferes (resuelto por la intermedia viaje_chofer)
    public function choferes(): BelongsToMany {
        return $this->belongsToMany(Chofer::class, 'viaje_chofers');
    }

    public function boletos(): HasMany {
        return $this->hasMany(Boleto::class);
    }
}

