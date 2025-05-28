<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use App\Models\CordinadorUsuario;
use App\Models\CordinadorArea;
use Illuminate\Http\Request;

class CordinadorUsuarioController extends Controller
{
    public function index()
    {
        $asignaciones = CordinadorUsuario::with(['cordinador', 'user', 'area'])->get();
        return view('cordinador_usuarios.index', compact('asignaciones'));
    }

    public function create()
    {
        $areas = Area::all();
        $usuarios = User::where('rol_id', 3)->get();
        return view('cordinador_usuarios.create', compact('areas', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'cordinador_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $cordinador = User::find($request->cordinador_id);
        $usuario = User::find($request->user_id);

        if (!$cordinador) {
            return redirect()->back()->withErrors(['cordinador_id' => 'Coordinador no encontrado.'])->withInput();
        }

        if (!$usuario) {
            return redirect()->back()->withErrors(['user_id' => 'Usuario no encontrado.'])->withInput();
        }

        CordinadorUsuario::create($request->all());

        return redirect()->route('cordinador_usuarios.index')->with('success', 'Asignación creada correctamente.');
    }

    public function edit($id)
    {
        $asignacion = CordinadorUsuario::findOrFail($id);
        $areas = Area::all();
        $usuarios = User::where('rol_id', 3)->get();
        $coordinadores = CordinadorArea::where('area_id', $asignacion->area_id)
            ->with('user')
            ->get()
            ->pluck('user');

        return view('cordinador_usuarios.edit', compact('asignacion', 'areas', 'usuarios', 'coordinadores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'cordinador_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $cordinador = User::find($request->cordinador_id);
        $usuario = User::find($request->user_id);

        if (!$cordinador || !$usuario) {
            return redirect()->back()->withErrors(['error' => 'Usuario o Coordinador no encontrado.'])->withInput();
        }

        $asignacion = CordinadorUsuario::findOrFail($id);
        $asignacion->update($request->all());

        return redirect()->route('cordinador_usuarios.index')->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $asignacion = CordinadorUsuario::findOrFail($id);
        $asignacion->delete();
        return redirect()->route('cordinador_usuarios.index')->with('success', 'Asignación eliminada correctamente.');
    }

    public function getCoordinadores($area_id)
    {
        $coordinadores = CordinadorArea::where('area_id', $area_id)
            ->with('user')
            ->get()
            ->pluck('user');
        return response()->json($coordinadores);
    }
}
