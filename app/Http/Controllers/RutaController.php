<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Localidad;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        // Cargamos las relaciones origen y destino junto con su provincia en un viaje rápido
        $rutas = Ruta::with(['origen.provincia', 'destino.provincia'])->latest()->get();
        
        // Traemos todas las localidades para cargarlas en los selects del formulario
        $localidades = Localidad::with('provincia')->orderBy('nombre')->get();
        
        // Variable de control para el modo edición
        $rutaEdit = null;

        return view('administrativo.rutas.index', compact('rutas', 'localidades', 'rutaEdit'));
    }

    public function store(Request $request)
    {
        // VALIDACIÓN: Origen y destino no pueden ser iguales y la combinación debe ser única
        $request->validate([
            'origen_id' => 'required|exists:localidades,id|different:destino_id',
            'destino_id' => 'required|exists:localidades,id',
            'distancia' => 'required|numeric|min:1|max:5000',
            'duracion_estimada' => 'required|date_format:H:i:s|regex:/^[0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/',
            'estado' => 'required|string|in:activa,inactiva',
        ], [
            'origen_id.different' => 'El origen y el destino no pueden ser la misma localidad.',
            'duracion_estimada.regex' => 'El formato de duración estimada debe ser exacto (HH:MM:SS).'
        ]);

        // Verificamos de forma fáctica si la combinación ya existe antes de insertar
        $existe = Ruta::where('origen_id', $request->origen_id)
            ->where('destino_id', $request->destino_id)
            ->exists();

        if ($existe) {
            return redirect()->back()->withInput()->with('error', 'Error: Esta ruta comercial ya se encuentra registrada.');
        }

        Ruta::create($request->all());

        return redirect()->route('rutas.index')->with('success', '¡Ruta comercial trazada con éxito!');
    }

    public function edit($id)
    {
        $rutas = Ruta::with(['origen.provincia', 'destino.provincia'])->latest()->get();
        $localidades = Localidad::with('provincia')->orderBy('nombre')->get();
        $rutaEdit = Ruta::findOrFail($id);

        return view('administrativo.rutas.index', compact('rutas', 'localidades', 'rutaEdit'));
    }

    public function update(Request $request, $id)
    {
        $ruta = Ruta::findOrFail($id);

        $request->validate([
            'origen_id' => 'required|exists:localidades,id|different:destino_id',
            'destino_id' => 'required|exists:localidades,id',
            'distancia' => 'required|numeric|min:1|max:5000',
            'duracion_estimada' => 'required|date_format:H:i:s',
            'estado' => 'required|string|in:activa,inactiva',
        ], [
            'origen_id.different' => 'El origen y el destino no pueden ser la misma localidad.'
        ]);

        // Controlar unicidad omitiendo la ruta actual
        $existe = Ruta::where('origen_id', $request->origen_id)
            ->where('destino_id', $request->destino_id)
            ->where('id', '!=', $ruta->id)
            ->exists();

        if ($existe) {
            return redirect()->back()->withInput()->with('error', 'Error: Ya existe otra ruta registrada para ese trayecto exacto.');
        }

        $ruta->update($request->all());

        return redirect()->route('rutas.index')->with('success', 'Trayecto actualizado correctamente.');
    }

    public function destroy($id)
    {
        try {
            $ruta = Ruta::findOrFail($id);
            $ruta->delete();
            return redirect()->route('rutas.index')->with('success', 'Ruta removida de la red de logística.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('rutas.index')
                ->with('error', 'No se puede eliminar la ruta porque posee viajes activos programados en el cronograma.');
        }
    }
}
