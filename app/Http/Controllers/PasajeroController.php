<?php

namespace App\Http\Controllers;

use App\Models\Pasajero;
use Illuminate\Http\Request;
use App\Models\Boleto;
use Illuminate\Support\Facades\Auth;

class PasajeroController extends Controller
{
  public function index()
    {
        $misBoletos = Boleto::with(['viaje.ruta.origen', 'viaje.ruta.destino', 'viaje.colectivo', 'asiento', 'venta'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pasajero.boletos.index', compact('misBoletos'));
    }

    public function reportarAusencia(Boleto $boleto)
    {
        // Verificamos que el boleto pertenezca al usuario logueado
        abort_if($boleto->user_id !== Auth::id(), 403);

        $boleto->update(['embarque' => 'ausente']);

        return redirect()->back()->with('success', 'Se registró tu aviso de cancelación para el control del chofer.');
    }

    public function show(Boleto $boleto)
{
    $user = Auth::user();

    // Solo puede ver el ticket si es quien viaja o quien pagó esa venta
    $esDueno = ($boleto->user_id === $user->id) || ($boleto->venta->cliente_id === $user->id);
    abort_unless($esDueno, 403, 'No tenés permiso para ver este boleto.');

    $boleto->load(['venta.vendedor', 'pasajero', 'viaje.ruta.origen', 'viaje.ruta.destino', 'viaje.colectivo', 'asiento']);

    // Reutilizamos la misma vista limpia del ticket que ya armamos
    return view('boleteria.show', compact('boleto'));
}

}