@extends('layouts.app')

@section('content')
<h1 class="page-title">Schedule New Appointment</h1>

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
    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf
        
        <input type="hidden" name="status" value="Scheduled">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Patient</label>
            <select name="patient_id" required class="input-box" style="width: 100%;">
                <option value="">Select Patient</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ strtoupper($patient->first_name) }} {{ strtoupper($patient->last_name) }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Doctor</label>
            <select name="doctor_id" required class="input-box" style="width: 100%;">
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Room</label>
            <select name="room_id" required class="input-box" style="width: 100%;">
                <option value="">Select Room</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->room_number }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Appointment Date & Time</label>
            <input type="datetime-local" name="appointment_date" required class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Notes</label>
            <textarea name="notes" class="input-box" style="width: 100%; height: 80px; resize: vertical;"></textarea>
        </div>

        <div>
            <button type="submit" class="btn-primary">Save Appointment</button>
            <a href="{{ route('appointments.index') }}" style="margin-left: 15px; color: #4a4c8c; font-weight: bold; text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection