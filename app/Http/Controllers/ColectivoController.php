<?php

namespace App\Http\Controllers;

use App\Models\Colectivo;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ColectivoController extends Controller
{
    public function index()
    {
        // Traemos todo lo necesario para la pantalla única
        $colectivos = Colectivo::with('modelo.marca')->latest()->get();
        $modelos = Modelo::with('marca')->get();
        
        // Enviamos un colectivo vacío por defecto (sirve para el formulario de alta)
        $colectivoEdit = null;

        return view('administrativo.colectivos.index', compact('colectivos', 'modelos', 'colectivoEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patente' => 'required|string|max:10|unique:colectivos,patente',
            'modelo_id' => 'required|exists:modelos,id',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'estado' => 'required|string|in:activo,mantenimiento,baja',
        ]);

        Colectivo::create($request->all());

        return redirect()->route('colectivos.index')
            ->with('success', '¡Unidad registrada! Los asientos se fabricaron automáticamente.');
    }

    public function edit($id)
    {
        // Al darle editar, recargamos la misma página pero pasándole la unidad a modificar
        $colectivos = Colectivo::with('modelo.marca')->latest()->get();
        $modelos = Modelo::with('marca')->get();
        $colectivoEdit = Colectivo::findOrFail($id);

        return view('administrativo.colectivos.index', compact('colectivos', 'modelos', 'colectivoEdit'));
    }

    public function update(Request $request, $id)
    {
        $colectivo = Colectivo::findOrFail($id);

        $request->validate([
            'patente' => 'required|string|max:10|unique:colectivos,patente,' . $colectivo->id,
            'modelo_id' => 'required|exists:modelos,id',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'estado' => 'required|string|in:activo,mantenimiento,baja',
        ]);

        $colectivo->update($request->all());

        return redirect()->route('colectivos.index')->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        try {
            $colectivo = Colectivo::findOrFail($id);
            $colectivo->delete();
            return redirect()->route('colectivos.index')->with('success', 'Unidad dada de baja.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('colectivos.index')
                ->with('error', 'No se puede eliminar la unidad porque tiene viajes programados.');
        }
    }
}
