<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Room::query();

        if ($search) {
            $query->where('capacity', 'ilike', "%{$search}%")
                ->orWhere('available_rooms', 'ilike', "%{$search}%")
                ->orWhere('name', 'ilike', "%{$search}%")
                ->orWhere('id', 'ilike', "%{$search}%");
        }

        $rooms = $query->orderBy('id')->paginate(10);

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
            'search' => $search,
            'success' => session('success'),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Rooms/Create');
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        // Validar datos
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:rooms,name',
                'capacity' => 'required|integer|min:1',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.unique' => 'El nombre ya está registrado.',
                'capacity.required' => 'La capacidad es obligatoria.',
                'capacity.min' => 'La capacidad debe ser al menos 1.'
            ]
        );

        // Crear habitación
        Room::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'available_rooms' => $request->capacity,
        ]);

        session()->flash('success', 'Habitación creada correctamente.');

        return redirect()->route('rooms.index');
    }


    public function edit(Room $room)
    {
        return Inertia::render('Rooms/Edit', [
            'room' => $room,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        // Validar datos
        $request->validate(
            [
                'new_name' => 'required|string|max:255|unique:rooms,name,' . $room->id,
                'capacity' => 'required|integer|min:1',
                'available_rooms' => 'required|integer|min:0|max:' . $request->capacity,
            ],
            [
                'new_name.required' => 'El nombre es obligatorio.',
                'new_name.unique' => 'El nombre ya está registrado.',
                'capacity.required' => 'La capacidad es obligatoria.',
                'capacity.min' => 'La capacidad debe ser al menos 1.',
                'available_rooms.required' => 'El espacio disponible es obligatorio.',
                'available_rooms.min' => 'El espacio disponible no puede ser negativo.',
                'available_rooms.max' => 'El espacio disponible no puede exceder la capacidad total.',
            ]
        );

        // Actualizar la sala
        $room->update([
            'new_name' => $request->name,
            'capacity' => $request->capacity,
            'available_rooms' => $request->available_rooms,
        ]);

        session()->flash('success', 'Sala actualizada correctamente.');

        return redirect()->route('rooms.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            // Eliminar el producto
            $room->delete();

            // Mensaje de éxito
            session()->flash('success', 'Habitación eliminada correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un problema al intentar eliminar la Habitación.');
        }

        return redirect()->route('rooms.index');
    }
}
