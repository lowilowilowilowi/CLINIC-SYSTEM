@extends('layouts.app')

@section('content')
<h1 class="page-title">Edit Appointment</h1>

@if ($errors->any())
    <div style="background: #f8d7da; color: #721c24; padding: 10px 15px; border: 2px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="max-width: 600px; background: white; border: 2px solid #4a4c8c; padding: 24px; border-radius: 6px;">
    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Patient</label>
            <select name="patient_id" required class="input-box" style="width: 100%;">
                <option value="">Select Patient</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                        {{ strtoupper($patient->first_name) }} {{ strtoupper($patient->last_name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Doctor</label>
            <select name="doctor_id" required class="input-box" style="width: 100%;">
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                        {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Room</label>
            <select name="room_id" required class="input-box" style="width: 100%;">
                <option value="">Select Room</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @selected(old('room_id', $appointment->room_id) == $room->id)>
                        {{ $room->room_number }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Appointment Date &amp; Time</label>
            <input
                type="datetime-local"
                name="appointment_date"
                value="{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i')) }}"
                required
                class="input-box"
                style="width: 100%;"
            >
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Notes</label>
            <textarea name="notes" class="input-box" style="width: 100%; height: 80px; resize: vertical;">{{ old('notes', $appointment->notes) }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Status</label>
            <select name="status" required class="input-box" style="width: 100%;">
                <option value="Scheduled" @selected(old('status', $appointment->status) === 'Scheduled')>Scheduled</option>
                <option value="Completed" @selected(old('status', $appointment->status) === 'Completed')>Completed</option>
                <option value="Cancelled" @selected(old('status', $appointment->status) === 'Cancelled')>Cancelled</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn-primary">Update Appointment</button>
            <a href="{{ route('appointments.index') }}" style="margin-left: 15px; color: #4a4c8c; font-weight: bold; text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
