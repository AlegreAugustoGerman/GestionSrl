<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model {
    protected $fillable = ['cliente_id', 'vendedor_id', 'fecha_venta', 'total', 'metodo_pago', 'estado'];

    // Quién pagó la transacción
    public function cliente(): BelongsTo {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    // El empleado que procesó el pago
    public function vendedor(): BelongsTo {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    // Los pasajes que componen esta factura/operación
    public function boletos(): HasMany {
        return $this->hasMany(Boleto::class);
    }
}