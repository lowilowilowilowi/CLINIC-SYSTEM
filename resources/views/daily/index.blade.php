@extends('layouts.app')

@section('content')
<h1 class="page-title">DAILY SCHEDULE</h1>

<div style="background: white; border: 2px solid #4a4c8c; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('daily.index') }}" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <label style="font-weight: bold; color: #4a4c8c;">Date:</label>
        <input type="date" name="date" value="{{ $date }}" class="input-box" onchange="this.form.submit()" style="padding: 6px; border: 1px solid #4a4c8c; border-radius: 4px;">
        <label style="display: inline-flex; align-items: center; gap: 6px;">
            <input type="checkbox" name="show_completed" value="1" {{ $showCompleted ? 'checked' : '' }} onchange="this.form.submit()">
            Show completed appointments
        </label>
    </form>
</div>

<h3 style="color: #4a4c8c; margin-bottom: 10px;">Today's visits:</h3>

<table class="data-table">
    <thead>
        <tr>
            <th>Time:</th>
            <th>Patient:</th>
            <th>Contact:</th>
            <th>Doctor:</th>
            <th>Room:</th>
            <th>Status:</th>
            <th>Billing Status:</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $appointment)
            <tr>
                <td>{{ date('H:i', strtotime($appointment->appointment_date)) }}</td>
                <td>{{ strtoupper($appointment->patient->first_name ?? '') }} {{ strtoupper($appointment->patient->last_name ?? '') }}</td>
                <td>{{ $appointment->patient->phone ?? 'N/A' }}</td>
                <td>{{ $appointment->doctor ? $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name : 'N/A' }}</td>
                <td>{{ $appointment->room?->room_number ?? 'Unassigned' }}</td>
                <td>{{ $appointment->status }}</td>
                <td>{{ $appointment->status === 'Completed' ? 'Unpaid' : '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #777; padding: 20px;">No appointments scheduled for this date.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection