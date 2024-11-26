<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Meal::query();

        if ($search) {
            $query->where('name', 'ilike', "%{$search}%")
                ->orWhere('ingredients', 'ilike', "%{$search}%")
                ->orWhere('id', 'ilike', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%");
        }

        $meals = $query->orderBy('id')->paginate(10);

        return Inertia::render('Meals/Index', [
            'meals' => $meals,
            'search' => $search,
            'success' => session('success'),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Meals/Create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validar datos
        $request->validate(
            [
                'name' => 'required|string|max:100|unique:meals,name',
                'price' => 'required|numeric|min:0',
                'ingredients' => 'required|string|max:255',
            ],
            [
                'name.required' => 'El nombre del producto es obligatorio.',
                'name.unique' => 'El nombre del producto ya está registrado.',
                'price.required' => 'El precio es obligatorio.',
                'price.numeric' => 'El precio debe ser un número.',
                'price.min' => 'El precio no puede ser negativo.',
                'ingredients.required' => 'Los ingredientes son obligatorios.',
            ]
        );

        // Crear producto
        Meal::create([
            'name' => $request->name,
            'price' => $request->price,
            'ingredients' => $request->ingredients,
        ]);

        session()->flash('success', 'Producto creado correctamente.');

        return redirect()->route('meals.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meal $meal)
    {
        return Inertia::render('Meals/Edit', [
            'meal' => $meal,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Meal $meal)
    {
        // Validar datos
        $request->validate(
            [
                'new_name' => 'required|string|max:100|unique:meals,name,' . $meal->id,
                'price' => 'required|numeric|min:0',
                'ingredients' => 'required|string|max:255',
            ],
            [
                'new_name.required' => 'El nombre del producto es obligatorio.',
                'new_name.unique' => 'El nombre del producto ya está registrado.',
                'price.required' => 'El precio es obligatorio.',
                'price.numeric' => 'El precio debe ser un número.',
                'price.min' => 'El precio no puede ser negativo.',
                'ingredients.required' => 'Los ingredientes son obligatorios.',
            ]
        );

        // Actualizar producto
        $meal->update([
            'name' => $request->new_name,
            'price' => $request->price,
            'ingredients' => $request->ingredients,
        ]);

        session()->flash('success', 'Producto actualizado correctamente.');

        return redirect()->route('meals.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meal $meal)
    {
        try {
            // Eliminar el producto
            $meal->delete();

            // Mensaje de éxito
            session()->flash('success', 'Producto eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un problema al intentar eliminar el producto.');
        }

        return redirect()->route('meals.index');
    }
}
