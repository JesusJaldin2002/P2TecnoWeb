<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProxyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Proxy::with('person'); // Relación con Person

        if ($search) {
            $query->whereHas('person', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('ci', 'ilike', "%{$search}%")
                    ->orWhere('address', 'ilike', "%{$search}%")
                    ->orWhereRaw("CASE 
                    WHEN gender = 'M' THEN 'masculino' 
                    WHEN gender = 'F' THEN 'femenino' 
                    ELSE '' 
                END ilike ?", ["%{$search}%"])
                    ->orWhere('birth_date', 'ilike', "%{$search}%");
            })->orWhere('phone_number', 'ilike', "%{$search}%")
                ->orWhere('occupation', 'ilike', "%{$search}%");
        }

        $proxies = $query->orderBy('id')->paginate(10);

        return Inertia::render('Proxies/Index', [
            'proxies' => $proxies,
            'search' => $search,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Proxies/Create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'ci' => 'required|digits_between:7,15|unique:people,ci',
                'name' => 'required|string|max:255|min:8',
                'address' => 'required|string|max:255',
                'gender' => 'required|in:M,F',
                'birth_date' => 'required|date',
                'phone_number' => 'required|digits_between:7,15',
                'occupation' => 'required|string|max:255',
            ],
            [
                'ci.required' => 'El CI es obligatorio.',
                'ci.unique' => 'El CI ya está registrado.',
                'name.required' => 'El nombre es obligatorio.',
                'name.min' => 'El nombre debe tener al menos 8 caracteres.',
                'address.required' => 'La dirección es obligatoria.',
                'gender.required' => 'El género es obligatorio.',
                'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
                'phone_number.required' => 'El número de teléfono es obligatorio.',
                'occupation.required' => 'La ocupación es obligatoria.',
            ]
        );

        // Crear persona
        $person = Person::create([
            'ci' => $request->ci,
            'name' => $request->name,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'people_type' => 'P', // 'P' para proxy
        ]);

        // Crear proxy
        Proxy::create([
            'id' => $person->id,
            'phone_number' => $request->phone_number,
            'occupation' => $request->occupation,
        ]);

        session()->flash('success', 'Apoderado creado correctamente.');

        return redirect()->route('proxies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proxy $proxy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proxy $proxy)
    {
        $proxy->load('person');

        return Inertia::render('Proxies/Edit', [
            'proxy' => $proxy,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proxy $proxy)
    {
        $request->validate(
            [
                'new_name' => 'required|string|max:255|min:8',
                'ci' => 'required|digits_between:7,15|unique:people,ci,' . $proxy->id,
                'address' => 'required|string|max:255',
                'gender' => 'required|in:M,F',
                'birth_date' => 'required|date',
                'phone_number' => 'required|digits_between:7,15',
                'occupation' => 'required|string|max:255',
            ],
            [
                'new_name.required' => 'El nombre es obligatorio.',
                'new_name.min' => 'El nombre debe tener al menos 8 caracteres.',
                'ci.required' => 'El CI es obligatorio.',
                'ci.unique' => 'El CI ya está en uso.',
                'address.required' => 'La dirección es obligatoria.',
                'gender.required' => 'El género es obligatorio.',
                'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
                'phone_number.required' => 'El número de teléfono es obligatorio.',
                'occupation.required' => 'La ocupación es obligatoria.',
            ]
        );

        $changesMade = false;

        $person = $proxy->person;

        if (
            $person->name !== $request->new_name ||
            $person->ci !== $request->ci ||
            $person->address !== $request->address ||
            $person->gender !== $request->gender ||
            $person->birth_date !== $request->birth_date
        ) {
            $person->update([
                'name' => $request->new_name,
                'ci' => $request->ci,
                'address' => $request->address,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
            ]);
            $changesMade = true;
        }

        if (
            $proxy->phone_number !== $request->phone_number ||
            $proxy->occupation !== $request->occupation
        ) {
            $proxy->update([
                'phone_number' => $request->phone_number,
                'occupation' => $request->occupation,
            ]);
            $changesMade = true;
        }

        if ($changesMade) {
            session()->flash('success', 'Apoderado actualizado correctamente.');
        }

        return redirect()->route('proxies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proxy $proxy)
    {
        try {
            // Verificar y eliminar la persona asociada
            if ($proxy->person) {
                $proxy->person->delete();
            }

            // Eliminar el paciente
            $proxy->delete();

            // Mensaje de éxito
            session()->flash('success', 'Apoderado eliminado correctamente.');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Hubo un problema al intentar eliminar el Apoderado.');
        }

        // Redirigir de vuelta a la lista de pacientes
        return redirect()->route('proxies.index');
    }
}
