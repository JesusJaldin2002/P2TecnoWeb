<?php

namespace App\Http\Controllers;

use App\Models\Observation;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Treatment::with([
            'service.patient.person',
            'service.employee.user',
            'room',
            'meals',
        ])->where('status', 'Activo'); // Filtrar por estado activo

        if ($search) {
            $query->whereHas('service.patient.person', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            })
                ->orWhereHas('service.employee.user', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                })
                ->orWhereHas('room', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                })
                ->orWhere('origin', 'ilike', "%{$search}%")
                ->orWhereHas('service', function ($q) use ($search) {
                    $q->where('price', 'ilike', "%{$search}%");
                });
        }

        $treatments = $query->orderBy('id')->paginate(10);

        return Inertia::render('Observations/Index', [
            'treatments' => $treatments,
            'search' => $search,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $treatment_id = $request->query('treatment_id');

        if (!$treatment_id) {
            abort(404, 'El tratamiento no se encontró.');
        }

        // Obtener el tratamiento relacionado
        $treatment = Treatment::with('service.patient.person')->findOrFail($treatment_id);

        return Inertia::render('Observations/Create', [
            'treatment' => $treatment,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'treatment_id' => 'required|exists:treatments,id',
                'weight' => 'required|numeric|min:0',
                'height' => 'required|numeric|min:0',
                'age' => 'required|integer|min:0',
                'description' => 'required|string|max:500',
            ],
            [
                'treatment_id.required' => 'El ID del tratamiento es obligatorio.',
                'weight.required' => 'El peso es obligatorio.',
                'height.required' => 'La altura es obligatoria.',
                'age.required' => 'La edad es obligatoria.',
                'description.required' => 'La descripción es obligatoria.',
            ]
        );

        $user_id = Auth::id(); // ID del médico autenticado

        Observation::create([
            'treatment_id' => $request->treatment_id,
            'doctor_id' => $user_id,
            'date' => now(), // Fecha actual
            'weight' => $request->weight,
            'height' => $request->height,
            'age' => $request->age,
            'description' => $request->description,
        ]);

        session()->flash('success', 'Seguimiento registrado correctamente.');

        return redirect()->route('observations.index');
    }



    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        $observations = $treatment->observations()->with('doctor.user')->orderBy('date', 'asc')->get();

        return Inertia::render('Observations/Show', [
            'treatment' => $treatment->load('service.patient.person'),
            'observations' => $observations,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Observation $observation)
    {
        // Cargar la observación con las relaciones necesarias
        $observation->load(['treatment.service.patient.person', 'doctor.user']);

        return Inertia::render('Observations/Edit', [
            'observation' => $observation,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Observation $observation)
    {
        $request->validate(
            [
                'weight' => 'required|numeric|min:0',
                'height' => 'required|numeric|min:0',
                'age' => 'required|integer|min:0',
                'description' => 'required|string|max:500',
            ],
            [
                'weight.required' => 'El campo de peso es obligatorio.',
                'weight.numeric' => 'El peso debe ser un número.',
                'weight.min' => 'El peso no puede ser un valor negativo.',
                'height.required' => 'El campo de altura es obligatorio.',
                'height.numeric' => 'La altura debe ser un número.',
                'height.min' => 'La altura no puede ser un valor negativo.',
                'age.required' => 'El campo de edad es obligatorio.',
                'age.integer' => 'La edad debe ser un número entero.',
                'age.min' => 'La edad no puede ser un valor negativo.',
                'description.required' => 'El campo de descripción es obligatorio.',
                'description.string' => 'La descripción debe ser un texto.',
                'description.max' => 'La descripción no puede tener más de 500 caracteres.',
            ]
        );

        // Actualizar la observación
        $observation->update([
            'weight' => $request->weight,
            'height' => $request->height,
            'age' => $request->age,
            'description' => $request->description,
        ]);

        session()->flash('success', 'Observación actualizada correctamente.');

        return redirect()->route('observations.show', ['treatment' => $observation->treatment_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Observation $observation)
    {
        $treatmentId = $observation->treatment_id;
        $observation->delete();

        session()->flash('success', 'Observación eliminada correctamente.');

        return redirect()->route('observations.show', ['treatment' => $treatmentId]);
    }
}
