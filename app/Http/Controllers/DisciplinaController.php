<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('role:admin'); // Comentado temporalmente
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disciplinas = Disciplina::orderBy('name')->paginate(10);
        return view('disciplinas.index', compact('disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('disciplinas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:disciplinas,name',
            'description' => 'nullable|string',
        ]);

        Disciplina::create($request->all());

        return redirect()->route('disciplinas.index')
            ->with('success', 'Disciplina creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Disciplina $disciplina)
    {
        return view('disciplinas.show', compact('disciplina'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disciplina $disciplina)
    {
        return view('disciplinas.edit', compact('disciplina'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disciplina $disciplina)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:disciplinas,name,' . $disciplina->id,
            'description' => 'nullable|string',
        ]);

        $disciplina->update($request->all());

        return redirect()->route('disciplinas.index')
            ->with('success', 'Disciplina actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disciplina $disciplina)
    {
        $disciplina->delete();

        return redirect()->route('disciplinas.index')
            ->with('success', 'Disciplina eliminada exitosamente.');
    }
}
