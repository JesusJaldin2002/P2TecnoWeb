<?php

use App\Http\Controllers\CaresheetController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProxyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\VaccineController;
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
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
    Route::get('/doctors/{doctor}/show', [DoctorController::class, 'show'])->name('doctors.show');

    // Employees
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/employees/{employee}/show', [EmployeeController::class, 'show'])->name('employees.show');

    // Proxies
    Route::get('/proxies', [ProxyController::class, 'index'])->name('proxies.index');
    Route::get('/proxies/create', [ProxyController::class, 'create'])->name('proxies.create');
    Route::post('/proxies', [ProxyController::class, 'store'])->name('proxies.store');
    Route::get('/proxies/{proxy}/edit', [ProxyController::class, 'edit'])->name('proxies.edit');
    Route::put('/proxies/{proxy}', [ProxyController::class, 'update'])->name('proxies.update');
    Route::delete('/proxies/{proxy}', [ProxyController::class, 'destroy'])->name('proxies.destroy');

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    // Meals
    Route::get('/meals', [MealController::class, 'index'])->name('meals.index');
    Route::get('/meals/create', [MealController::class, 'create'])->name('meals.create');
    Route::post('/meals', [MealController::class, 'store'])->name('meals.store');
    Route::get('/meals/{meal}/edit', [MealController::class, 'edit'])->name('meals.edit');
    Route::put('/meals/{meal}', [MealController::class, 'update'])->name('meals.update');
    Route::delete('/meals/{meal}', [MealController::class, 'destroy'])->name('meals.destroy');

    // Rooms
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    // Treatments
    Route::get('/treatments', [TreatmentController::class, 'index'])->name('treatments.index');
    Route::get('/treatments/create', [TreatmentController::class, 'create'])->name('treatments.create');
    Route::post('/treatments', [TreatmentController::class, 'store'])->name('treatments.store');
    Route::get('/treatments/{treatment}/edit', [TreatmentController::class, 'edit'])->name('treatments.edit');
    Route::put('/treatments/{treatment}', [TreatmentController::class, 'update'])->name('treatments.update');
    Route::delete('/treatments/{treatment}', [TreatmentController::class, 'destroy'])->name('treatments.destroy');

    // Consults
    Route::get('/consults', [ConsultController::class, 'index'])->name('consults.index');
    Route::get('/consults/create', [ConsultController::class, 'create'])->name('consults.create');
    Route::post('/consults', [ConsultController::class, 'store'])->name('consults.store');
    Route::get('/consults/{consult}/edit', [ConsultController::class, 'edit'])->name('consults.edit');
    Route::put('/consults/{consult}', [ConsultController::class, 'update'])->name('consults.update');
    Route::delete('/consults/{consult}', [ConsultController::class, 'destroy'])->name('consults.destroy');

    // Vaccines
    Route::get('/vaccines', [VaccineController::class, 'index'])->name('vaccines.index');
    Route::get('/vaccines/create', [VaccineController::class, 'create'])->name('vaccines.create');
    Route::post('/vaccines', [VaccineController::class, 'store'])->name('vaccines.store');
    Route::get('/vaccines/{vaccine}/edit', [VaccineController::class, 'edit'])->name('vaccines.edit');
    Route::put('/vaccines/{vaccine}', [VaccineController::class, 'update'])->name('vaccines.update');
    Route::delete('/vaccines/{vaccine}', [VaccineController::class, 'destroy'])->name('vaccines.destroy');

    // Caresheets
    Route::get('/caresheets', [CaresheetController::class, 'index'])->name('caresheets.index');
    Route::get('/caresheets/create', [CaresheetController::class, 'create'])->name('caresheets.create');
    Route::post('/caresheets', [CaresheetController::class, 'store'])->name('caresheets.store');
    Route::get('/caresheets/{caresheet}/edit', [CaresheetController::class, 'edit'])->name('caresheets.edit');
    Route::put('/caresheets/{caresheet}', [CaresheetController::class, 'update'])->name('caresheets.update');
    Route::delete('/caresheets/{caresheet}', [CaresheetController::class, 'destroy'])->name('caresheets.destroy');
    Route::get('/caresheets/{caresheet}',[CaresheetController::class, 'show'])->name('caresheets.show');

    // Observations
    Route::get('/observations', [ObservationController::class, 'index'])->name('observations.index');
    Route::get('/observations/create', [ObservationController::class, 'create'])->name('observations.create');
    Route::post('/observations', [ObservationController::class, 'store'])->name('observations.store');
    Route::get('/observations/{observation}/edit', [ObservationController::class, 'edit'])->name('observations.edit');
    Route::put('/observations/{observation}', [ObservationController::class, 'update'])->name('observations.update');
    Route::delete('/observations/{observation}', [ObservationController::class, 'destroy'])->name('observations.destroy');
    Route::get('/observations/{treatment}',[ObservationController::class, 'show'])->name('observations.show');

    // Payments
    Route::get('/payments/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments/generate-qr', [PaymentController::class, 'generateQR'])->name('payments.generateQR');
    Route::post('/payments/verify', [PaymentController::class, 'verifyPayment'])->name('payments.verify');
    Route::post('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');
    Route::get('/payments/return', [PaymentController::class, 'return'])->name('payments.return');
    Route::get('/test-token', [PaymentController::class, 'testToken']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/payments', [ReportController::class, 'payment'])->name('reports.payment');
    Route::get('/reports/consults', [ReportController::class, 'consult'])->name('reports.consult');
    Route::get('/reports/treatments', [ReportController::class, 'treatment'])->name('reports.treatment');


    // Stadistics
    Route::get('/stadistics', [ReportController::class, 'stadistics'])->name('stadistics.index');

});
