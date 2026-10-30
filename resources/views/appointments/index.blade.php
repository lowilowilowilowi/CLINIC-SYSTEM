@extends('layouts.app')

@section('content')
<h1 class="page-title">APPOINTMENTS</h1>

<div style="margin-bottom: 20px;">
    <a href="{{ route('appointments.create') }}" class="btn-primary">Add Appointment +</a>
</div>

@if(session('success'))
    <div style="padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 15px; border-radius: 4px;">
        {{ session('success') }}
    </div>
@endif

<table class="data-table">
    <thead>
        <tr>
            <th>ID:</th>
            <th>Patient:</th>
            <th>Doctor:</th>
            <th>Date & Time:</th>
            <th>Status:</th>
            <th>Action:</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $appointment)
            <tr>
                <td style="font-weight: bold;">APT-{{ str_pad($appointment->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ strtoupper($appointment->patient->first_name ?? '') }} {{ strtoupper($appointment->patient->last_name ?? '') }}</td>
                <td>
                    @if($appointment->doctor)
                        {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $appointment->appointment_date }}</td>
                <td>{{ $appointment->status }}</td>
                <td>
                    <a href="{{ route('appointments.show', $appointment->id) }}" style="color: #4a4c8c; font-weight: bold; text-decoration: none;">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #777; padding: 20px;">No appointments found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection