<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Service;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TreatmentController extends Controller
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
            'meals', // Incluimos las comidas asociadas
        ]);

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
                ->orWhere('status', 'ilike', "%{$search}%")
                ->orWhereHas('service', function ($q) use ($search) {
                    $q->where('price', 'ilike', "%{$search}%");
                });
        }

        $treatments = $query->orderBy('id')->paginate(10);

        return Inertia::render('Treatments/Index', [
            'treatments' => $treatments,
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
        $rooms = Room::where('available_rooms', '>', 0)->get();
        $meals = Meal::all();

        return Inertia::render('Treatments/Create', [
            'patients' => $patients,
            'rooms' => $rooms,
            'meals' => $meals,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'patient_id' => 'required|exists:patients,id',
                'room_id' => 'required|exists:rooms,id',
                'origin' => 'required|string|max:255',
                'status' => 'required|string|max:10',
                'meals' => 'required|array|min:1',
                'meals.*.id' => 'required|exists:meals,id',
                'meals.*.quantity' => 'required|integer|min:1',
            ],
            [
                'patient_id.required' => 'Debe seleccionar un paciente.',
                'room_id.required' => 'Debe seleccionar una habitación.',
                'origin.required' => 'El origen es obligatorio.',
                'status.required' => 'El estado es obligatorio.',
                'meals.required' => 'Debe seleccionar al menos un producto.',
                'meals.min' => 'Debe seleccionar al menos un producto.',
                'meals.*.id.required' => 'Debe seleccionar un producto válido.',
                'meals.*.quantity.required' => 'Debe especificar la cantidad para cada producto.',
            ]
        );

        $user = Auth::user()->id;

        // Calcular precio total basado en las comidas seleccionadas
        $mealCost = collect($request->meals)->reduce(function ($total, $meal) {
            $mealPrice = Meal::find($meal['id'])->price;
            return $total + ($mealPrice * $meal['quantity']);
        }, 0);

        // Crear servicio
        $service = Service::create([
            'patient_id' => $request->patient_id,
            'employee_id' => $user,
            'service_type' => 'T',
            'price' => $mealCost, // Costo total basado en las comidas
        ]);

        // Crear tratamiento
        $treatment = Treatment::create([
            'id' => $service->id,
            'origin' => $request->origin,
            'room_id' => $request->room_id,
            'status' => $request->status,
        ]);

        // Asociar productos al tratamiento
        foreach ($request->meals as $meal) {
            $treatment->meals()->attach($meal['id'], ['quantity' => $meal['quantity']]);
        }

        // Reducir disponibilidad de la habitación
        $room = Room::find($request->room_id);
        $room->decrement('available_rooms');

        session()->flash('success', 'Tratamiento registrado correctamente.');

        return redirect()->route('treatments.index');
    }





    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Treatment $treatment)
    {
        // Cargar relaciones necesarias para la vista
        $treatment->load([
            'service.patient.person',
            'service.employee.user',
            'room',
            'meals' => function ($query) {
                $query->withPivot('quantity'); // Traer la cantidad de la relación pivot
            },
        ]);

        // Obtener pacientes, habitaciones disponibles y comidas
        $patients = Patient::with('person')->get();
        $rooms = Room::where('available_rooms', '>', 0)
            ->orWhere('id', $treatment->room_id) // Incluir la habitación actual aunque esté llena
            ->get();
        $meals = Meal::all();

        return Inertia::render('Treatments/Edit', [
            'treatment' => $treatment,
            'patients' => $patients,
            'rooms' => $rooms,
            'meals' => $meals,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required|exists:rooms,id',
            'origin' => 'required|string|max:255',
            'status' => 'required|string|max:10',
            'meals' => 'array',
            'meals.*.id' => 'required|exists:meals,id',
            'meals.*.quantity' => 'required|integer|min:1',
        ]);

        $oldRoomId = $treatment->room_id;

        // Recalcular el costo total
        $mealCost = collect($request->meals)->reduce(function ($total, $meal) {
            $mealPrice = Meal::find($meal['id'])->price;
            return $total + ($mealPrice * $meal['quantity']);
        }, 0);
        $totalPrice = $mealCost;

        // Actualizar el servicio
        $treatment->service->update([
            'patient_id' => $request->patient_id,
            'price' => $totalPrice,
        ]);

        // Actualizar el tratamiento
        $treatment->update([
            'origin' => $request->origin,
            'room_id' => $request->room_id,
            'status' => $request->status,
        ]);

        // Actualizar productos asociados
        $treatment->meals()->sync(collect($request->meals)->mapWithKeys(function ($meal) {
            return [$meal['id'] => ['quantity' => $meal['quantity']]];
        }));

        // Manejar el cambio de habitación
        if ($oldRoomId !== $request->room_id) {
            Room::find($oldRoomId)->increment('available_rooms');
            Room::find($request->room_id)->decrement('available_rooms');
        }

        session()->flash('success', 'Tratamiento actualizado correctamente.');

        return redirect()->route('treatments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treatment $treatment)
    {
        try {
            // Incrementar la disponibilidad de la habitación asociada
            if ($treatment->room) {
                $treatment->room->increment('available_rooms');
            }

            // Verificar y eliminar el servicio relacionado
            if ($treatment->service) {
                $treatment->service->delete();
            }

            // Eliminar el tratamiento
            $treatment->delete();

            // Mensaje de éxito
            session()->flash('success', 'Servicio de Tratamiento eliminado correctamente.');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Hubo un problema al intentar eliminar el Servicio de Tratamiento.');
        }

        // Redirigir de vuelta a la lista de tratamientos
        return redirect()->route('treatments.index');
    }
}
