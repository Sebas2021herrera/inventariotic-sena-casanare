<?php

namespace App\Http\Controllers;

use App\Models\Responsable;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    public function index()
    {
        $responsables = Responsable::orderBy('nombre')->get();
        return view('responsables.index', compact('responsables'));
    }

    public function create()
    {
        return view('responsables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cedula' => 'required|string|max:20|unique:responsables,cedula',
            'nombre' => 'required|string|max:150',
            'correo_institucional' => 'nullable|email|max:150',
            'dependencia' => 'required|string|max:150',
            'cargo' => 'required|string|max:150',
            'tipo_funcionario' => 'required|string|max:50',
        ]);

        Responsable::create($validated);

        return redirect()
            ->route('responsables.index')
            ->with('success', 'Responsable registrado correctamente');
    }

    public function show(Responsable $responsable)
    {
        return view('responsables.show', compact('responsable'));
    }

    public function edit(Responsable $responsable)
    {
        return view('responsables.edit', compact('responsable'));
    }

    public function update(Request $request, Responsable $responsable)
    {
        $validated = $request->validate([
            'cedula' => 'required|string|max:20|unique:responsables,cedula,' . $responsable->id,
            'nombre' => 'required|string|max:150',
            'correo_institucional' => 'nullable|email|max:150',
            'dependencia' => 'required|string|max:150',
            'cargo' => 'required|string|max:150',
            'tipo_funcionario' => 'required|string|max:50',
        ]);

        $responsable->update($validated);

        return redirect()
            ->route('responsables.index')
            ->with('success', 'Responsable actualizado correctamente');
    }

    public function destroy(Responsable $responsable)
    {
        $responsable->delete();

        return redirect()
            ->route('responsables.index')
            ->with('success', 'Responsable eliminado correctamente');
    }
}
