<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Service;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Vaccine::with(['service.patient.person', 'service.employee.user']);

        if ($search) {
            $query->whereHas('service.patient.person', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            })->orWhereHas('service.employee.user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            })->orWhere('name', 'ilike', "%{$search}%")
                ->orWhereHas('service', function ($q) use ($search) {
                    $q->where('price', 'ilike', "%{$search}%");
                });
        }

        $vaccines = $query->orderBy('id')->paginate(10);

        return Inertia::render('Vaccines/Index', [
            'vaccines' => $vaccines,
            'search' => $search,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::with('person')->get();

        return Inertia::render('Vaccines/Create', [
            'patients' => $patients,
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
                'patient_id' => 'required|exists:patients,id',
                'vaccine_name' => 'required|string|max:100',
            ],
            [
                'patient_id.required' => 'Debe seleccionar un paciente.',
                'patient_id.exists' => 'El paciente seleccionado no es válido.',
                'vaccine_name.required' => 'El nombre de la vacuna es obligatorio.',
            ]
        );

        // Obtener el empleado autenticado
        $user = Auth::user()->id;

        // Registrar el servicio
        $service = Service::create([
            'patient_id' => $request->patient_id,
            'employee_id' => $user,
            'service_type' => 'V', // 'V' para vacunas
            'price' => 10.00, // Precio fijo
        ]);

        // Registrar la vacuna
        Vaccine::create([
            'id' => $service->id,
            'name' => $request->vaccine_name,
        ]);

        session()->flash('success', 'Vacuna registrada correctamente.');

        return redirect()->route('vaccines.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaccine $vaccine)
    {
        $vaccine->load(['service.patient.person', 'service.employee.user']);
        $patients = Patient::with('person')->get();

        return Inertia::render('Vaccines/Edit', [
            'vaccine' => $vaccine,
            'patients' => $patients,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vaccine $vaccine)
    {
        $request->validate(
            [
                'patient_id' => 'required|exists:patients,id',
                'vaccine_name' => 'required|string|max:100',
            ],
            [
                'patient_id.required' => 'Debe seleccionar un paciente.',
                'patient_id.exists' => 'El paciente seleccionado no es válido.',
                'vaccine_name.required' => 'El nombre de la vacuna es obligatorio.',
            ]
        );

        // Actualizar el servicio relacionado
        $vaccine->service->update([
            'patient_id' => $request->patient_id,
        ]);

        // Actualizar la vacuna
        $vaccine->update([
            'name' => $request->vaccine_name,
        ]);

        session()->flash('success', 'Vacuna actualizada correctamente.');

        return redirect()->route('vaccines.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccine $vaccine)
    {
        try {
            if ($vaccine->service) {
                $vaccine->service->delete();
            }

            // Eliminar el la vacuna
            $vaccine->delete();

            // Mensaje de éxito
            session()->flash('success', 'Servicio de Vacuna eliminado correctamente.');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Hubo un problema al intentar eliminar el Servicio de Vacuna.');
        }

        // Redirigir de vuelta a la lista de pacientes
        return redirect()->route('vaccines.index');
    }
}
