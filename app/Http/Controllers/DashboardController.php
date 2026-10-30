<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', $today)->count();
        $completedTodayCount = Appointment::whereDate('appointment_date', $today)
            ->where('status', 'Completed')
            ->count();
        $pendingInvoicesCount = Invoice::where('status', 'Unpaid')->count();

        $unpaidInvoices = Invoice::with('patient')
            ->where('status', 'Unpaid')
            ->latest()
            ->take(5)
            ->get();

        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'today',
            'todayAppointmentsCount',
            'completedTodayCount',
            'pendingInvoicesCount',
            'unpaidInvoices',
            'recentAppointments'
        ));
    }
}