<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruta extends Model {
    protected $fillable = ['origen_id', 'destino_id', 'distancia', 'duracion_estimada', 'estado'];

    // Relación doble hacia la misma tabla maestra de localidades
    public function origen(): BelongsTo {
        return $this->belongsTo(Localidad::class, 'origen_id');
    }

    public function destino(): BelongsTo {
        return $this->belongsTo(Localidad::class, 'destino_id');
    }

    public function viajes(): HasMany {
        return $this->hasMany(Viaje::class);
    }
}
