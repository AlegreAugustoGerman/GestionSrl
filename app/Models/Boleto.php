<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Boleto extends Model {
    protected $fillable = ['venta_id', 'user_id', 'viaje_id', 'asiento_id', 'precio', 'estado'];

    public function venta(): BelongsTo {
        return $this->belongsTo(Venta::class);
    }

    public function pasajero(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function viaje(): BelongsTo {
        return $this->belongsTo(Viaje::class);
    }

    public function asiento(): BelongsTo {
        return $this->belongsTo(Asiento::class);
    }
}

 

