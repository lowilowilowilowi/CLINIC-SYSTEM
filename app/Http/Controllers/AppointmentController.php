<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Room;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'room'])->get();
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $rooms = Room::orderBy('room_number')->get();
        return view('appointments.create', compact('patients', 'doctors', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'room_id' => ['required', 'exists:rooms,id', function ($attribute, $value, $fail) use ($request) {
                $appointmentDate = $request->input('appointment_date');

                if (! $appointmentDate) {
                    return;
                }

                if (Appointment::where('room_id', $value)
                    ->where('appointment_date', $appointmentDate)
                    ->exists()) {
                    $fail('This room is already booked at the selected time.');
                }

                if (Appointment::where('doctor_id', $request->input('doctor_id'))
                    ->where('appointment_date', $appointmentDate)
                    ->exists()) {
                    $fail('This doctor is already booked at the selected time.');
                }
            }],
            'appointment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'Scheduled';

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully.');
    }

    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'room'])->findOrFail($id);
        return view('appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $patients = Patient::all();
        $doctors = Doctor::all();
        $rooms = Room::orderBy('room_number')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'room_id' => ['required', 'exists:rooms,id', function ($attribute, $value, $fail) use ($request, $appointment) {
                $appointmentDate = $request->input('appointment_date');

                if (! $appointmentDate) {
                    return;
                }

                if (Appointment::where('room_id', $value)
                    ->where('appointment_date', $appointmentDate)
                    ->whereKeyNot($appointment->id)
                    ->exists()) {
                    $fail('This room is already booked at the selected time.');
                }

                if (Appointment::where('doctor_id', $request->input('doctor_id'))
                    ->where('appointment_date', $appointmentDate)
                    ->whereKeyNot($appointment->id)
                    ->exists()) {
                    $fail('This doctor is already booked at the selected time.');
                }
            }],
            'appointment_date' => 'required|date',
            'status' => 'required|string|in:Scheduled,Completed,Cancelled',
            'notes' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $originalStatus = $appointment->status;
        $appointment->update($validated);

        if ($originalStatus !== 'Completed' && $appointment->status === 'Completed') {
            Invoice::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'amount' => $request->input('amount'),
                'status' => 'Unpaid',
                'due_date' => now()->addDays(7),
            ]);
        }

        return redirect()->route('appointments.show', $id)->with('success', 'Appointment updated successfully.');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }
}