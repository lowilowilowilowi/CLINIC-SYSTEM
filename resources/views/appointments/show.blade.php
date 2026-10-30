@extends('layouts.app')

@section('content')
<h1 class="page-title">APPOINTMENT DETAILS: APT-{{ str_pad($appointment->id, 3, '0', STR_PAD_LEFT) }}</h1>

<div style="max-width: 600px; background: white; border: 2px solid #4a4c8c; padding: 24px; border-radius: 6px;">
    <div style="margin-bottom: 12px;">
        <strong>Patient:</strong> 
        {{ strtoupper($appointment->patient->first_name ?? '') }} {{ strtoupper($appointment->patient->last_name ?? 'N/A') }}
    </div>

    <div style="margin-bottom: 12px;">
        <strong>Doctor:</strong> 
        {{ $appointment->doctor ? $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name : 'N/A' }}
    </div>

    <div style="margin-bottom: 12px;">
        <strong>Date & Time:</strong> {{ $appointment->appointment_date }}
    </div>

    <div style="margin-bottom: 12px;">
        <strong>Status:</strong> 
        <span style="font-weight: bold; color: {{ $appointment->status === 'Completed' ? 'green' : ($appointment->status === 'Cancelled' ? 'red' : '#4a4c8c') }};">
            {{ $appointment->status }}
        </span>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Notes:</strong> {{ $appointment->notes ?? 'None' }}
    </div>

    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <a href="{{ route('appointments.index') }}" class="btn-primary" style="text-decoration: none; padding: 8px 16px;">Back to List</a>

        <a href="{{ route('appointments.edit', $appointment->id) }}" style="background: #ffc107; color: #000; padding: 9px 16px; border-radius: 4px; font-weight: bold; text-decoration: none;">
            Edit
        </a>

        @if($appointment->status !== 'Completed')
            <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('PUT')
                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                <input type="hidden" name="doctor_id" value="{{ $appointment->doctor_id }}">
                <input type="hidden" name="room_id" value="{{ $appointment->room_id }}">
                <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                <input type="hidden" name="status" value="Completed">
                <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                
                <button type="submit" style="background: #28a745; color: white; border: none; padding: 9px 16px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    Mark as Completed
                </button>
            </form>
        @endif

        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 9px 16px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Delete Appointment
            </button>
        </form>
    </div>
</div>
@endsection