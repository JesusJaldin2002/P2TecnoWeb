<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProxyController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login'); 
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Doctors
    Route::get('/doctors',[DoctorController::class,'index'])->name('doctors.index');
    Route::get('/doctors/create',[DoctorController::class,'create'])->name('doctors.create');
    Route::post('/doctors',[DoctorController::class,'store'])->name('doctors.store');
    Route::get('/doctors/{doctor}/edit',[DoctorController::class,'edit'])->name('doctors.edit');
    Route::put('/doctors/{doctor}',[DoctorController::class,'update'])->name('doctors.update');
    Route::delete('/doctors/{doctor}',[DoctorController::class,'destroy'])->name('doctors.destroy');
    Route::get('/doctors/{doctor}/show',[DoctorController::class,'show'])->name('doctors.show');

    // Employees
    Route::get('/employees',[EmployeeController::class,'index'])->name('employees.index');
    Route::get('/employees/create',[EmployeeController::class,'create'])->name('employees.create');
    Route::post('/employees',[EmployeeController::class,'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit',[EmployeeController::class,'edit'])->name('employees.edit');
    Route::put('/employees/{employee}',[EmployeeController::class,'update'])->name('employees.update');
    Route::delete('/employees/{employee}',[EmployeeController::class,'destroy'])->name('employees.destroy');
    Route::get('/employees/{employee}/show',[EmployeeController::class,'show'])->name('employees.show');

    // Proxies
    Route::get('/proxies',[ProxyController::class,'index'])->name('proxies.index');
    Route::get('/proxies/create',[ProxyController::class,'create'])->name('proxies.create');
    Route::post('/proxies',[ProxyController::class,'store'])->name('proxies.store');
    Route::get('/proxies/{proxy}/edit',[ProxyController::class,'edit'])->name('proxies.edit');
    Route::put('/proxies/{proxy}',[ProxyController::class,'update'])->name('proxies.update');
    Route::delete('/proxies/{proxy}',[ProxyController::class,'destroy'])->name('proxies.destroy');

    // Patients
    Route::get('/patients',[PatientController::class,'index'])->name('patients.index');
    Route::get('/patients/create',[PatientController::class,'create'])->name('patients.create');
    Route::post('/patients',[PatientController::class,'store'])->name('patients.store');
    Route::get('/patients/{patient}/edit',[PatientController::class,'edit'])->name('patients.edit');
    Route::put('/patients/{patient}',[PatientController::class,'update'])->name('patients.update');
    Route::delete('/patients/{patient}',[PatientController::class,'destroy'])->name('patients.destroy');

    // Meals
    Route::get('/meals',[MealController::class,'index'])->name('meals.index');
    Route::get('/meals/create',[MealController::class,'create'])->name('meals.create');
    Route::post('/meals',[MealController::class,'store'])->name('meals.store');
    Route::get('/meals/{meal}/edit',[MealController::class,'edit'])->name('meals.edit');
    Route::put('/meals/{meal}',[MealController::class,'update'])->name('meals.update');
    Route::delete('/meals/{meal}',[MealController::class,'destroy'])->name('meals.destroy');    

    // Rooms
    Route::get('/rooms',[RoomController::class,'index'])->name('rooms.index');
    Route::get('/rooms/create',[RoomController::class,'create'])->name('rooms.create');
    Route::post('/rooms',[RoomController::class,'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit',[RoomController::class,'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}',[RoomController::class,'update'])->name('rooms.update');
    Route::delete('/rooms/{room}',[RoomController::class,'destroy'])->name('rooms.destroy');
    
    
});
