@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 class="page-title" style="margin-bottom: 0;">Edit Appointment</h1>
    <a href="{{ route('appointments.index') }}" style="color: #4a4c8c; font-weight: bold; text-decoration: none;">&larr; Back to Appointments</a>
</div>

@if ($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 10px 15px; border: 2px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background: white; border: 2px solid #4a4c8c; padding: 20px; border-radius: 4px;">
    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4a4c8c;">Patient:</label>
            <select name="patient_id" style="width: 100%; padding: 8px; border: 2px solid #4a4c8c; border-radius: 4px;">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                        {{ $patient->first_name }} {{ $patient->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4a4c8c;">Doctor:</label>
            <select name="doctor_id" style="width: 100%; padding: 8px; border: 2px solid #4a4c8c; border-radius: 4px;">
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name ?? $doctor->first_name . ' ' . $doctor->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4a4c8c;">Room:</label>
            <select name="room_id" style="width: 100%; padding: 8px; border: 2px solid #4a4c8c; border-radius: 4px;" required>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ $appointment->room_id == $room->id ? 'selected' : '' }}>
                        {{ $room->room_number }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4a4c8c;">Appointment Date:</label>
            <input type="datetime-local" name="appointment_date" value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i') }}" style="width: 100%; padding: 8px; border: 2px solid #4a4c8c; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4a4c8c;">Status:</label>
            <select name="status" style="width: 100%; padding: 8px; border: 2px solid #4a4c8c; border-radius: 4px;">
                <option value="Scheduled" {{ $appointment->status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ $appointment->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4a4c8c;">Consultation Fee / Amount (₱):</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" placeholder="Leave blank if unknown" style="width: 100%; padding: 8px; border: 2px solid #4a4c8c; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4a4c8c;">Notes:</label>
            <textarea name="notes" rows="3" style="width: 100%; padding: 8px; border: 2px solid #4a4c8c; border-radius: 4px;">{{ old('notes', $appointment->notes) }}</textarea>
        </div>

        <button type="submit" class="btn-primary" style="padding: 10px 20px; font-weight: bold; cursor: pointer;">Update Appointment</button>
    </form>
</div>
@endsection