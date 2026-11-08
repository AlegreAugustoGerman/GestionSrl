<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modelo extends Model {
    // Forzamos el nombre en español para que Laravel no busque "modelos" con la lógica en inglés
    protected $table = 'modelos'; 

    protected $fillable = ['marca_id', 'nombre', 'capacidad_asientos'];

    // Un modelo pertenece a una marca
    public function marca(): BelongsTo {
        return $this->belongsTo(Marca::class);
    }

    // Un modelo tiene muchos colectivos físicos asignados
    public function colectivos(): HasMany {
        return $this->hasMany(Colectivo::class);
    }
}
