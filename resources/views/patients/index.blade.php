@extends('layouts.app')

@section('content')
<h1 class="page-title">PATIENTS</h1>

<div style="display: flex; gap: 10px; margin-bottom: 15px; align-items: center;">
    <span style="font-weight: bold; color: #4a4c8c;">Search:</span>
    <input type="text" class="input-box" style="width: 300px;">
    <button class="btn-primary">Search</button>
</div>

<div style="margin-bottom: 20px;">
    <a href="{{ route('patients.create') }}" class="btn-primary">Add patient +</a>
</div>

@if(session('success'))
    <div style="padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 15px; border-radius: 4px;">
        {{ session('success') }}
    </div>
@endif

<table class="data-table">
    <thead>
        <tr>
            <th>Patient ID:</th>
            <th>Name:</th>
            <th>Contact no.:</th>
            <th>Action:</th>
        </tr>
    </thead>
    <tbody>
        @forelse($patients as $patient)
            <tr>
                <td style="font-weight: bold;">P-{{ str_pad($patient->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ strtoupper($patient->first_name) }} {{ strtoupper($patient->last_name) }}</td>
                <td>{{ $patient->phone }}</td>
                <td>
                    <a href="{{ route('patients.show', $patient->id) }}" style="color: #4a4c8c; font-weight: bold; text-decoration: none;">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #777; padding: 20px;">No patients found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection