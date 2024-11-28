<?php

namespace App\Http\Controllers;

use App\Models\Consult;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index');
    }

    public function payment(Request $request)
    {
        // Obtener todos los pacientes para el select
        $patients = Patient::with('person')->get();

        // Si no hay filtros, devuelve la vista inicial con un paginador vacío
        if (!$request->has(['patient_id', 'date_from', 'date_to'])) {
            $emptyPayments = (object) [
                'data' => [],
                'links' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => null,
                    'to' => null,
                    'total' => 0,
                ],
            ];
            return Inertia::render('Reports/Payments', [
                'patients' => $patients,
                'payments' => $emptyPayments,
            ]);
        }

        // Validar filtros
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ], [
            'patient_id.required' => 'Debe seleccionar un paciente.',
            'patient_id.exists' => 'El paciente seleccionado no es válido.',
            'date_to.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
        ]);

        // Consulta de pagos filtrada
        $query = Payment::with(['service.patient.person'])
            ->whereHas('service', function ($q) use ($request) {
                $q->where('patient_id', $request->patient_id);
            });

        // Aplicar filtros de fechas en la tabla de pagos
        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('date', 'desc')->paginate(10);

        // Retornar datos filtrados junto con pacientes
        return Inertia::render('Reports/Payments', [
            'patients' => $patients,
            'payments' => $payments,
        ]);
    }

    public function consult(Request $request)
    {
        // Obtener todos los doctores para el select
        $doctors = Doctor::with('user')->get();

        // Si no hay filtros, devuelve la vista inicial con un paginador vacío
        if (!$request->has(['doctor_id', 'date_from', 'date_to'])) {
            $emptyConsults = (object) [
                'data' => [],
                'links' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => null,
                    'to' => null,
                    'total' => 0,
                ],
            ];
            return Inertia::render('Reports/Consults', [
                'doctors' => $doctors,
                'consults' => $emptyConsults,
            ]);
        }

        // Validar filtros
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ], [
            'doctor_id.required' => 'Debe seleccionar un doctor.',
            'doctor_id.exists' => 'El doctor seleccionado no es válido.',
            'date_to.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
        ]);

        // Consultar las consultas filtradas
        $query = Consult::with(['doctor.user', 'service.patient.person'])
            ->where('doctor_id', $request->doctor_id);

        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $consults = $query->orderBy('date', 'desc')->paginate(10);

        // Retornar datos filtrados junto con doctores
        return Inertia::render('Reports/Consults', [
            'doctors' => $doctors,
            'consults' => $consults,
        ]);
    }

    public function treatment(Request $request)
    {
        // Obtener todos los pacientes para el select
        $patients = \App\Models\Patient::with('person')->get();

        // Si no hay filtros, devuelve la vista inicial con un paginador vacío
        if (!$request->has(['patient_id', 'date_from', 'date_to'])) {
            $emptyTreatments = (object) [
                'data' => [],
                'links' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'from' => null,
                    'to' => null,
                    'total' => 0,
                ],
            ];
            return Inertia::render('Reports/Treatments', [
                'patients' => $patients,
                'treatments' => $emptyTreatments,
            ]);
        }

        // Validar filtros
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ], [
            'patient_id.required' => 'Debe seleccionar un paciente.',
            'patient_id.exists' => 'El paciente seleccionado no es válido.',
            'date_to.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
        ]);

        // Consultar tratamientos filtrados
        $query = \App\Models\Treatment::with(['service.patient.person', 'observations.doctor.user', 'meals', 'room'])
            ->whereHas('service', function ($q) use ($request) {
                $q->where('patient_id', $request->patient_id);

                // Filtrar por fechas en la tabla services
                if ($request->date_from) {
                    $q->whereDate('services.created_at', '>=', $request->date_from);
                }
                if ($request->date_to) {
                    $q->whereDate('services.created_at', '<=', $request->date_to);
                }
            });

        $treatments = $query->orderBy('id', 'desc')->paginate(10);

        return Inertia::render('Reports/Treatments', [
            'patients' => $patients,
            'treatments' => $treatments,
        ]);
    }
}
