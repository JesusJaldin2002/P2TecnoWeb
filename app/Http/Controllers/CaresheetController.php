<?php

namespace App\Http\Controllers;

use App\Models\Caresheet;
use App\Models\Consult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CaresheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $doctorId = Auth::user()->id;

        $search = $request->input('search', '');

        $query = Consult::with(['service.patient.person', 'caresheet'])
            ->where('doctor_id', $doctorId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('date', 'ilike', "%{$search}%") // Búsqueda por fecha
                    ->orWhere('reason', 'ilike', "%{$search}%") // Búsqueda por motivo
                    ->orWhereHas('service.patient.person', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%"); // Búsqueda por nombre del paciente
                    });
            });
        }

        $consults = $query->orderBy('date', 'desc')->paginate(10);

        return Inertia::render('Caresheets/Index', [
            'consults' => $consults,
            'search' => $search,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validar que la consulta existe y pertenece al doctor logueado
        $consult = Consult::where('id', $request->consult_id)
            ->where('doctor_id', Auth::user()->id)
            ->with('service.patient.person')
            ->firstOrFail();

        return Inertia::render('Caresheets/Create', [
            'consult_id' => $consult->id,
            'patient_name' => $consult->service->patient->person->name,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos enviados
        $request->validate([
            'consult_id' => 'required|exists:consults,id|unique:caresheets,consult_id',
            'symptoms' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'consult_id.required' => 'La consulta es obligatoria.',
            'consult_id.exists' => 'La consulta no existe.',
            'consult_id.unique' => 'Esta consulta ya tiene una hoja de atención.',
            'symptoms.required' => 'Los síntomas son obligatorios',
            'diagnosis.required' => 'El diagnóstico es requerido',
            'symptoms.max' => 'Los síntomas no deben exceder los 255 caracteres.',
            'diagnosis.max' => 'El diagnóstico no debe exceder los 255 caracteres.',
            'notes.max' => 'Las notas no deben exceder los 255 caracteres.',
        ]);


        // Crear la hoja de atención
        Caresheet::create([
            'consult_id' => $request->consult_id,
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'notes' => $request->notes ? $request->notes : 'Ninguna',

        ]);

        // Mensaje de éxito
        session()->flash('success', 'Hoja de atención creada correctamente.');

        // Redirigir al índice de hojas de atención
        return redirect()->route('caresheets.index');
    }

    
    public function show(Caresheet $caresheet)
    {
        $caresheet = Caresheet::with(['consult.service.patient.person'])
            ->findOrFail($caresheet->id);

        return Inertia::render('Caresheets/Show', [
            'caresheet' => $caresheet,
            'patient_name' => $caresheet->consult->service->patient->person->name,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Caresheet $caresheet)
    {
        // Cargar las relaciones necesarias
        $caresheet->load(['consult.service.patient.person']);

        return Inertia::render('Caresheets/Edit', [
            'caresheet' => $caresheet,
            'patient_name' => $caresheet->consult->service->patient->person->name,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caresheet $caresheet)
    {
        // Validar los datos enviados
        $request->validate([
            'symptoms' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'symptoms.required' => 'Los síntomas son obligatorios.',
            'diagnosis.required' => 'El diagnóstico es obligatorio.',
            'symptoms.max' => 'Los síntomas no deben exceder los 255 caracteres.',
            'diagnosis.max' => 'El diagnóstico no debe exceder los 255 caracteres.',
            'notes.max' => 'Las notas no deben exceder los 255 caracteres.',
        ]);

        // Actualizar la hoja de atención
        $caresheet->update([
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'notes' => $request->notes ?: 'Ninguna',
        ]);

        session()->flash('success', 'Hoja de atención actualizada correctamente.');

        return redirect()->route('caresheets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caresheet $caresheet)
    {
        try {
            // Eliminar el tratamiento
            $caresheet->delete();
            // Mensaje de éxito
            session()->flash('success', 'Servicio de Tratamiento eliminado correctamente.');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Hubo un problema al intentar eliminar el Servicio de Tratamiento.');
        }

        // Redirigir de vuelta a la lista de tratamientos
        return redirect()->route('caresheets.index');
    }
}
