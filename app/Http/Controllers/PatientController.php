<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Person;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Patient::with(['person', 'proxy.person']); // Relación con People y Proxy

        if ($search) {
            $query->whereHas('person', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('ci', 'ilike', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhereRaw("CASE 
                        WHEN gender = 'M' THEN 'masculino' 
                        WHEN gender = 'F' THEN 'femenino' 
                        ELSE '' 
                    END ilike ?", ["%{$search}%"]) // Transformar género
                    ->orWhere('birth_date', 'ilike', "%{$search}%");
            })->orWhere('blood_type', 'ilike', "%{$search}%")
                ->orWhere('rh_factor', 'ilike', "%{$search}%")
                ->orWhereHas('proxy.person', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                });
        }

        $patients = $query->orderBy('id')->paginate(10);

        return Inertia::render('Patients/Index', [
            'patients' => $patients,
            'search' => $search,
            'success' => session('success'),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proxies = Proxy::with('person')->get();

        return Inertia::render('Patients/Create', [
            'proxies' => $proxies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar datos
        $request->validate(
            [
                'ci' => 'required|digits_between:7,15|unique:people,ci',
                'name' => 'required|string|max:255|min:8',
                'address' => 'required|string|max:255',
                'gender' => 'required|in:M,F',
                'birth_date' => 'required|date',
                'blood_type' => 'required|string|max:3',
                'rh_factor' => 'required|in:+,-',
                'proxy_id' => 'required|exists:proxies,id',
            ],
            [
                'ci.required' => 'El CI es obligatorio.',
                'ci.unique' => 'El CI ya está registrado.',
                'name.required' => 'El nombre es obligatorio.',
                'name.min' => 'El nombre debe tener al menos 8 caracteres.',
                'address.required' => 'La dirección es obligatoria.',
                'gender.required' => 'El género es obligatorio.',
                'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
                'blood_type.required' => 'El tipo de sangre es obligatorio.',
                'rh_factor.required' => 'El factor RH es obligatorio.',
                'proxy_id.required' => 'Debe seleccionar un apoderado.',
                'proxy_id.exists' => 'El apoderado seleccionado no es válido.',
            ]
        );

        // Crear persona
        $person = Person::create([
            'ci' => $request->ci,
            'name' => $request->name,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'people_type' => 'A', // 'P' para paciente
        ]);

        // Crear paciente
        Patient::create([
            'id' => $person->id,
            'blood_type' => $request->blood_type,
            'rh_factor' => $request->rh_factor,
            'proxy_id' => $request->proxy_id,
        ]);

        session()->flash('success', 'Paciente creado correctamente.');

        return redirect()->route('patients.index');
    }


    public function edit(Patient $patient)
    {
        $patient->load(['person', 'proxy.person']);
        $proxies = Proxy::with('person')->get();

        return Inertia::render('Patients/Edit', [
            'patient' => $patient,
            'proxies' => $proxies,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate(
            [
                'new_name' => 'required|string|max:255|min:8',
                'ci' => 'required|digits_between:7,15|unique:people,ci,' . $patient->id,
                'address' => 'required|string|max:255',
                'gender' => 'required|in:M,F',
                'birth_date' => 'required|date',
                'blood_type' => 'required|string|max:3',
                'rh_factor' => 'required|in:+,-',
                'proxy_id' => 'required|exists:proxies,id',
            ],
            [
                'new_name.required' => 'El nombre es obligatorio.',
                'new_name.min' => 'El nombre debe tener al menos 8 caracteres.',
                'ci.required' => 'El CI es obligatorio.',
                'ci.unique' => 'El CI ya está en uso.',
                'address.required' => 'La dirección es obligatoria.',
                'gender.required' => 'El género es obligatorio.',
                'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
                'blood_type.required' => 'El tipo de sangre es obligatorio.',
                'rh_factor.required' => 'El factor RH es obligatorio.',
                'proxy_id.required' => 'Debe seleccionar un apoderado.',
            ]
        );

        $changesMade = false;

        $person = $patient->person;

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
            $patient->blood_type !== $request->blood_type ||
            $patient->rh_factor !== $request->rh_factor ||
            $patient->proxy_id !== $request->proxy_id
        ) {
            $patient->update([
                'blood_type' => $request->blood_type,
                'rh_factor' => $request->rh_factor,
                'proxy_id' => $request->proxy_id,
            ]);
            $changesMade = true;
        }

        if ($changesMade) {
            session()->flash('success', 'Paciente actualizado correctamente.');
        }

        return redirect()->route('patients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        try {
            // Verificar y eliminar la persona asociada
            if ($patient->person) {
                $patient->person->delete();
            }

            // Eliminar el paciente
            $patient->delete();

            // Mensaje de éxito
            session()->flash('success', 'Paciente eliminado correctamente.');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Hubo un problema al intentar eliminar el paciente.');
        }

        // Redirigir de vuelta a la lista de pacientes
        return redirect()->route('patients.index');
    }
}
