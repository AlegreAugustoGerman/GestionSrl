<?php

namespace App\Http\Controllers;

use App\Models\Chofer;
use App\Models\User;
use Illuminate\Http\Request;

class ChoferController extends Controller
{
    public function index()
    {
        // Traemos todos los choferes indexados con sus datos de usuario
        $choferes = Chofer::with('user')->latest()->get();

        // Buscamos usuarios que NO sean choferes todavía, para poder promoverlos en el selector
        $usuariosDisponibles = User::whereDoesntHave('chofer')->get();

        // Variable para controlar si estamos en modo edición
        $choferEdit = null;

        return view('administrativo.choferes.index', compact('choferes', 'usuariosDisponibles', 'choferEdit'));
    }

    public function store(Request $request)
    {
        // Validación estricta de la licencia única
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:choferes,user_id',
            'licencia' => 'required|string|max:30|unique:choferes,licencia',
            'estado' => 'required|string|in:activo,inactivo,suspendido',
        ]);

        Chofer::create($request->all());

        return redirect()->route('choferes.index')
            ->with('success', '¡Conductor habilitado con éxito en el sistema!');
    }

    public function edit($id)
    {
        $choferes = Chofer::with('user')->latest()->get();
        // Al editar, el usuario actual sí debe figurar en la lista del selector
        $choferEdit = Chofer::with('user')->findOrFail($id);
        $usuariosDisponibles = User::whereDoesntHave('chofer')
            ->orWhere('id', $choferEdit->user_id)
            ->get();

        return view('administrativo.choferes.index', compact('choferes', 'usuariosDisponibles', 'choferEdit'));
    }

    public function update(Request $request, $id)
    {
        $chofer = Chofer::findOrFail($id);

        $request->validate([
            'licencia' => 'required|string|max:30|unique:choferes,licencia,' . $chofer->id,
            'estado' => 'required|string|in:activo,inactivo,suspendido',
        ]);

        // El user_id no se cambia para resguardar la herencia 1:1 original
        $chofer->update($request->only(['licencia', 'estado']));

        return redirect()->route('choferes.index')
            ->with('success', 'Legajo del conductor actualizado correctamente.');
    }

    public function destroy($id)
    {
        try {
            $chofer = Chofer::findOrFail($id);
            // Si tiene viajes asignados, la restricción restrict de MySQL bloqueará el borrado
            $chofer->delete();
            return redirect()->route('choferes.index')->with('success', 'Se revocó la habilitación del conductor.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('choferes.index')
                ->with('error', 'No se puede dar de baja al chofer porque tiene viajes asignados en el cronograma.');
        }
    }
}
