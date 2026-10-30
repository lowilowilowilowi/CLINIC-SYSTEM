<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class DailyScheduleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $showCompleted = $request->boolean('show_completed');

        $appointmentsQuery = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', $date);

        if (! $showCompleted) {
            $appointmentsQuery->where('status', '!=', 'Completed');
        }

        $appointments = $appointmentsQuery->get();

        return view('daily.index', compact('appointments', 'date', 'showCompleted'));
    }
}