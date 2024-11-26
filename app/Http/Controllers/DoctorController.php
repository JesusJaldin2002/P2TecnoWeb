<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Doctor::with('user'); // Relación con User

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('id', 'ilike', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('id')->paginate(10);

        return Inertia::render('Doctors/Index', [
            'doctors' => $doctors,
            'search' => $search,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Doctors/Create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar todos los datos de una sola vez
        $request->validate(
            [
                'name' => 'required|min:8',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'ci' => 'required|digits_between:7,15|unique:users,ci', // Validar rango y unicidad
                'phone_number' => 'required|numeric|digits_between:7,15', // Validar rango y numérico
                'address' => 'required',
                'number_ss' => 'required|unique:doctors,number_ss',
            ],
            [
                'name.required' => 'El nombre es requerido',
                'name.min' => 'El nombre debe contener 8 caracteres',
                'email.required' => 'El email es requerido',
                'email.email' => 'El email no tiene un formato válido',
                'email.unique' => 'El email ya está en uso',
                'password.required' => 'La contraseña es requerida',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres',
                'ci.required' => 'El CI es requerido',
                'ci.unique' => 'El CI ya está en uso',
                'ci.digits_between' => 'El CI debe tener entre 7 y 15 dígitos',
                'phone_number.required' => 'El teléfono es requerido',
                'phone_number.numeric' => 'El teléfono debe ser un número válido',
                'phone_number.digits_between' => 'El teléfono debe tener entre 7 y 15 dígitos',
                'address.required' => 'La dirección es requerida',
                'number_ss.required' => 'El número de seguro social es requerido',
                'number_ss.unique' => 'El número de seguro social ya está en uso',
            ]
        );

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'ci' => $request->ci,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'user_type' => 'D', // 'D' para doctor
        ]);

        // Crear el doctor
        Doctor::create([
            'id' => $user->id,
            'number_ss' => $request->number_ss,
        ]);

        // Redirigir con mensaje de éxito
        session()->flash('success', 'Doctor creado correctamente');

        return redirect()->route('doctors.index');
    }



    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load('user');

        return Inertia::render('Doctors/Show', [
            'doctor' => $doctor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        // Cargar la relación con el usuario
        $doctor->load('user');

        return Inertia::render('Doctors/Edit', [
            'doctor' => $doctor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        // Validar los datos
        $request->validate(
            [
                'new_name' => 'required|string|max:255|min:8',
                'email' => 'required|email|unique:users,email,' . $doctor->user->id,
                'ci' => 'required|digits_between:7,15|unique:users,ci,' . $doctor->user->id,
                'phone_number' => 'required|numeric|digits_between:7,15',
                'address' => 'required|string|max:255',
                'number_ss' => 'required|string|max:255|unique:doctors,number_ss,' . $doctor->id,
                'password' => 'nullable|min:8',
            ],
            [
                'new_name.required' => 'El nombre es obligatorio.',
                'new_name.min' => 'El nombre debe tener al menos 8 caracteres.',
                'new_name.string' => 'El nombre debe ser un texto válido.',
                'new_name.max' => 'El nombre no puede superar los 255 caracteres.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El email debe tener un formato válido.',
                'email.unique' => 'El email ya está en uso.',
                'ci.required' => 'El CI es obligatorio.',
                'ci.digits_between' => 'El CI debe tener entre 7 y 15 dígitos.',
                'ci.unique' => 'El CI ya está en uso.',
                'phone_number.required' => 'El número de teléfono es obligatorio.',
                'phone_number.numeric' => 'El número de teléfono debe ser un valor numérico.',
                'phone_number.digits_between' => 'El número de teléfono debe tener entre 7 y 15 dígitos.',
                'address.required' => 'La dirección es obligatoria.',
                'address.string' => 'La dirección debe ser un texto válido.',
                'address.max' => 'La dirección no puede superar los 255 caracteres.',
                'number_ss.required' => 'El número de seguro social es obligatorio.',
                'number_ss.string' => 'El número de seguro social debe ser un texto válido.',
                'number_ss.max' => 'El número de seguro social no puede superar los 255 caracteres.',
                'number_ss.unique' => 'El número de seguro social ya está en uso.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ]
        );

        // Verificar si hubo cambios
        $changesMade = false;

        $user = $doctor->user;
        if (
            $user->name !== $request->new_name ||
            $user->email !== $request->email ||
            $user->ci !== $request->ci ||
            $user->phone_number !== $request->phone_number ||
            $user->address !== $request->address
        ) {
            $user->update([
                'name' => $request->new_name,
                'email' => $request->email,
                'ci' => $request->ci,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);
            $changesMade = true;
        }

        if ($doctor->number_ss !== $request->number_ss) {
            $doctor->update([
                'number_ss' => $request->number_ss,
            ]);
            $changesMade = true;
        }

        if ($changesMade) {
            session()->flash('success', 'Doctor actualizado correctamente.');
        }

        return redirect()->route('doctors.index');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        try {
            if ($doctor->user) {
                $doctor->user->delete();
            }
            $doctor->delete();

            // Mensaje de éxito
            session()->flash('success', 'Doctor eliminado correctamente.');
        } catch (\Exception $e) {
            // Mensaje de error en caso de excepción
            session()->flash('error', 'Hubo un problema al intentar eliminar el doctor.');
        }

        // Redirigir de vuelta a la lista de doctores
        return redirect()->route('doctors.index');
    }
}
