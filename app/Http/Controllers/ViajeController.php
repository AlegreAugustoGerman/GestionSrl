<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Ruta;
use App\Models\Colectivo;
use App\Models\Chofer;
use Illuminate\Http\Request;

class ViajeController extends Controller
{
    public function index()
    {
        // Traemos el cronograma completo de salidas con toda su infraestructura asociada
        $viajes = Viaje::with(['ruta.origen', 'ruta.destino', 'colectivo.modelo.marca', 'choferes.user'])->latest()->get();
        
        // Datos maestros necesarios para el formulario de programación
        $rutas = Ruta::with(['origen', 'destino'])->where('estado', 'activa')->get();
        $colectivos = Colectivo::with('modelo.marca')->where('estado', 'activo')->get();
        $choferes = Chofer::with('user')->where('estado', 'activo')->get();
        
        $viajeEdit = null;

        return view('administrativo.viajes.index', compact('viajes', 'rutas', 'colectivos', 'choferes', 'viajeEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'colectivo_id' => 'required|exists:colectivos,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_salida' => 'required|date_format:H:i',
            'hora_llegada' => 'required|date_format:H:i|after:hora_salida',
            'precio' => 'required|numeric|min:0',
            'estado' => 'required|string|in:programado,en curso,finalizado,cancelado',
            'chofer_id' => 'required|exists:choferes,id' // Chofer asignado a la salida
        ], [
            'hora_llegada.after' => 'La hora de llegada debe ser posterior a la hora de salida.'
        ]);

        // 1. Crear el registro del viaje
        $viaje = Viaje::create($request->all());

        // 2. Asociar el chofer en la tabla intermedia Muchos a Muchos (viaje_chofers)
        $viaje->choferes()->attach($request->chofer_id);

        return redirect()->route('viajes.index')->with('success', '¡Salida programada y chofer asignado correctamente!');
    }

    public function edit($id)
    {
        $viajes = Viaje::with(['ruta.origen', 'ruta.destino', 'colectivo.modelo.marca', 'choferes.user'])->latest()->get();
        $rutas = Ruta::with(['origen', 'destino'])->where('estado', 'activa')->get();
        $colectivos = Colectivo::with('modelo.marca')->where('estado', 'activo')->get();
        $choferes = Chofer::with('user')->where('estado', 'activo')->get();
        
        $viajeEdit = Viaje::with('choferes')->findOrFail($id);

        return view('administrativo.viajes.index', compact('viajes', 'rutas', 'colectivos', 'choferes', 'viajeEdit'));
    }

    public function update(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);

        $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'colectivo_id' => 'required|exists:colectivos,id',
            'fecha' => 'required|date',
            'hora_salida' => 'required|date_format:H:i',
            'hora_llegada' => 'required|date_format:H:i',
            'precio' => 'required|numeric|min:0',
            'estado' => 'required|string|in:programado,en curso,finalizado,cancelado',
            'chofer_id' => 'required|exists:choferes,id'
        ]);

        $viaje->update($request->all());

        // Sincronizamos el chofer en la tabla intermedia (reemplaza el anterior de forma atómica)
        $viaje->choferes()->sync([$request->chofer_id]);

        return redirect()->route('viajes.index')->with('success', 'Cronograma de la salida modificado correctamente.');
    }

    public function destroy($id)
    {
        try {
            $viaje = Viaje::findOrFail($id);
            // Si ya se vendieron boletos para este viaje, MySQL restringirá el borrado para proteger al cliente
            $viaje->delete();
            return redirect()->route('viajes.index')->with('success', 'Salida removida del cronograma.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('viajes.index')
                ->with('error', 'No se puede cancelar el viaje porque posee boletos comerciales ya vendidos.');
        }
    }
}
