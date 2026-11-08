<?php
// app/Models/Localidad.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Localidad extends Model {
    // Forzamos el nombre correcto de la tabla para que Laravel no busque "localidads"
    protected $table = 'localidades'; 

    protected $fillable = ['provincia_id', 'nombre'];

    public function provincia(): BelongsTo {
        return $this->belongsTo(Provincia::class);
    }
}
