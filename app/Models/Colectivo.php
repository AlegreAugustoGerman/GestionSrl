<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colectivo extends Model {
    protected $fillable = ['patente', 'modelo_id', 'anio', 'estado'];

    protected static function booted(): void {
        // Automatizador de carga en 3FN
        static::created(function (Colectivo $colectivo) {
            // Traemos el modelo asociado para saber cuántos asientos fabricar
            $modelo = $colectivo->modelo; 
            
            for ($i = 1; $i <= $modelo->capacidad_asientos; $i++) {
                $colectivo->asientos()->create([
                    'numero' => $i,
                    // Lógica simple: los primeros 4 son ejecutivos 'cama', el resto 'semicama'
                    'tipo' => ($i <= 4) ? 'cama' : 'semicama',
                    'estado' => 'habilitado'
                ]);
            }
        });
    }

    public function modelo(): BelongsTo {
        return $this->belongsTo(Modelo::class);
    }

    public function asientos(): HasMany {
        return $this->hasMany(Asiento::class);
    }
}
