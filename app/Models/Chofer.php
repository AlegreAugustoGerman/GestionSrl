<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chofer extends Model {
    use HasFactory;
    // Especificamos la tabla en español ya que el modelo en inglés sería Driver
    protected $table = 'choferes';
    
    protected $fillable = ['user_id', 'licencia', 'estado'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

