<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Employee::with('user'); // Relación con User

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            })->orWhere('occupation', 'like', "%{$search}%");
        }

        $employees = $query->orderBy('id')->paginate(10);

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'search' => $search,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Employees/Create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'ci' => 'required|digits_between:7,15|unique:users,ci',
                'phone_number' => 'required|numeric|digits_between:7,15',
                'address' => 'required',
                'occupation' => 'required|string|max:255',
            ],
            [
                'name.required' => 'El nombre es requerido',
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
                'occupation.required' => 'La ocupación es requerida',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'ci' => $request->ci,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'user_type' => 'E', // 'E' para empleado
        ]);

        // Crear el empleado
        Employee::create([
            'id' => $user->id,
            'occupation' => $request->occupation,
        ]);

        // Redirigir con mensaje de éxito
        session()->flash('success', 'Empleado creado correctamente');

        return redirect()->route('employees.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        // Cargar la relación con el usuario
        $employee->load('user');

        return Inertia::render('Employees/Show', [
            'employee' => $employee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee->load('user');

        return Inertia::render('Employees/Edit', [
            'employee' => $employee,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Validar los datos
        $request->validate(
            [
                'new_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $employee->user->id,
                'ci' => 'required|integer|min:1|max:2147483647|unique:users,ci,' . $employee->user->id,
                'phone_number' => 'required|numeric|digits_between:7,15',
                'address' => 'required|string|max:255',
                'occupation' => 'required|string|max:255',
                'password' => 'nullable|min:8',
            ],
            [
                'new_name.required' => 'El nombre es obligatorio.',
                'new_name.string' => 'El nombre debe ser un texto válido.',
                'new_name.max' => 'El nombre no puede superar los 255 caracteres.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El email debe tener un formato válido.',
                'email.unique' => 'El email ya está en uso.',
                'ci.required' => 'El CI es obligatorio.',
                'ci.integer' => 'El CI debe ser un número entero válido.',
                'ci.min' => 'El CI debe ser mayor a 0.',
                'ci.max' => 'El CI no puede superar el rango permitido.',
                'ci.unique' => 'El CI ya está en uso.',
                'phone_number.required' => 'El número de teléfono es obligatorio.',
                'phone_number.numeric' => 'El número de teléfono debe ser un valor numérico.',
                'phone_number.digits_between' => 'El número de teléfono debe tener entre 7 y 15 dígitos.',
                'address.required' => 'La dirección es obligatoria.',
                'address.string' => 'La dirección debe ser un texto válido.',
                'address.max' => 'La dirección no puede superar los 255 caracteres.',
                'occupation.required' => 'La ocupación es obligatoria.',
                'occupation.string' => 'La ocupación debe ser un texto válido.',
                'occupation.max' => 'La ocupación no puede superar los 255 caracteres.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ]
        );

        $changesMade = false;
        $user = $employee->user;

        // Actualizar el usuario si hubo cambios
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

        // Actualizar la ocupación del empleado si hubo cambios
        if ($employee->occupation !== $request->occupation) {
            $employee->update(['occupation' => $request->occupation]);
            $changesMade = true;
        }

        if ($changesMade) {
            session()->flash('success', 'Empleado actualizado correctamente.');
        }

        return redirect()->route('employees.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
