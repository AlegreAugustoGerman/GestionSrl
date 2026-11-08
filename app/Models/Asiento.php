<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asiento extends Model {
    protected $fillable = ['colectivo_id', 'numero', 'tipo', 'estado'];

    public function colectivo(): BelongsTo {
        return $this->belongsTo(Colectivo::class);
    }
}

