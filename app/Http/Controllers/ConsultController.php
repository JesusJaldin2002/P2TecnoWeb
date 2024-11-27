<?php

namespace App\Http\Controllers;

use App\Models\Consult;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConsultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Consult::with([
            'service.patient.person',
            'service.employee.user',
            'doctor.user'
        ]);

        if ($search) {
            $query->whereHas('service.patient.person', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            })
                ->orWhereHas('service.employee.user', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                })
                ->orWhereHas('doctor.user', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                })
                ->orWhere('reason', 'ilike', "%{$search}%")
                ->orWhereHas('service', function ($q) use ($search) {
                    $q->where('price', 'like', "%{$search}%");
                });
        }

        $consults = $query->orderBy('id')->paginate(10);

        return Inertia::render('Consults/Index', [
            'consults' => $consults,
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
        $doctors = Doctor::with('user')->get();

        return Inertia::render('Consults/Create', [
            'patients' => $patients,
            'doctors' => $doctors,
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
                'doctor_id' => 'required|exists:doctors,id',
                'date' => 'required|date|after_or_equal:today',
                'reason' => 'nullable|string|max:255',
            ],
            [
                'patient_id.required' => 'Debe seleccionar un paciente.',
                'patient_id.exists' => 'El paciente seleccionado no es válido.',
                'doctor_id.required' => 'Debe seleccionar un doctor.',
                'doctor_id.exists' => 'El doctor seleccionado no es válido.',
                'date.required' => 'Debe seleccionar una fecha.',
                'date.after_or_equal' => 'La fecha debe ser hoy o una posterior.',
            ]
        );

        // Obtener el empleado autenticado
        $user = Auth::user()->id;

        // Registrar el servicio
        $service = Service::create([
            'patient_id' => $request->patient_id,
            'employee_id' => $user,
            'service_type' => 'C', // 'C' para consultas
            'price' => 50.00, // Precio fijo
        ]);

        // Registrar la consulta
        Consult::create([
            'id' => $service->id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'reason' => $request->reason,
        ]);

        session()->flash('success', 'Consulta registrada correctamente.');

        return redirect()->route('consults.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consult $consult)
    {
        $consult->load(['service.patient.person', 'service.employee.user', 'doctor.user']);
        $patients = Patient::with('person')->get();
        $doctors = Doctor::with('user')->get();

        return Inertia::render('Consults/Edit', [
            'consult' => $consult,
            'patients' => $patients,
            'doctors' => $doctors,
        ]);
    }


    public function update(Request $request, Consult $consult)
    {
        $request->validate(
            [
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,id',
                'date' => 'required|date|after_or_equal:today',
                'reason' => 'nullable|string|max:255',
            ],
            [
                'patient_id.required' => 'Debe seleccionar un paciente.',
                'patient_id.exists' => 'El paciente seleccionado no es válido.',
                'doctor_id.required' => 'Debe seleccionar un doctor.',
                'doctor_id.exists' => 'El doctor seleccionado no es válido.',
                'date.required' => 'Debe seleccionar una fecha.',
                'date.after_or_equal' => 'La fecha debe ser hoy o una posterior.',
            ]
        );

        // Actualizar el servicio relacionado
        $consult->service->update([
            'patient_id' => $request->patient_id,
        ]);

        // Actualizar la consulta
        $consult->update([
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'reason' => $request->reason,
        ]);

        session()->flash('success', 'Consulta actualizada correctamente.');

        return redirect()->route('consults.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consult $consult)
    {
        try {
            if ($consult->service) {
                $consult->service->delete();
            }

            // Eliminar la consulta
            $consult->delete();

            // Mensaje de éxito
            session()->flash('success', 'Servicio de Consulta eliminado correctamente.');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Hubo un problema al intentar eliminar el Servicio de Consulta.');
        }

        // Redirigir de vuelta a la lista de pacientes
        return redirect()->route('consults.index');
    }
}
