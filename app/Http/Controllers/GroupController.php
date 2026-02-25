<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::query();
        
        // Aplicar búsqueda si existe
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('codigo', 'LIKE', "%{$search}%");
            });
        }
        
        // Paginación (10 elementos por página)
        $groups = $query->paginate(10);
        
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:groups',
            'nombre' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Group::create($request->all());
        return redirect()->route('groups.index')->with('success', 'Grupo creado exitosamente.');
    }

    public function edit(Group $group)
    {
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'codigo' => 'required|string|unique:groups,codigo,' . $group->id,
            'nombre' => 'required|string|max:255',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $group->update($request->all());
        return redirect()->route('groups.index')->with('success', 'Grupo actualizado exitosamente.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Grupo eliminado exitosamente.');
    }

    /**
     * Buscar grupos basado en un término de búsqueda
     * Mantenido para compatibilidad con código anterior
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $groups = Group::when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where(function($q) use ($query) {
                    $q->where('nombre', 'LIKE', '%'.$query.'%')
                      ->orWhere('codigo', 'LIKE', '%'.$query.'%');
                });
            })
            ->paginate(10);

        return response()->json([
            'groups' => $groups->items(),
            'pagination' => [
                'total' => $groups->total(),
                'per_page' => $groups->perPage(),
                'current_page' => $groups->currentPage(),
                'last_page' => $groups->lastPage(),
                'from' => $groups->firstItem(),
                'to' => $groups->lastItem()
            ]
        ]);
    }
}
