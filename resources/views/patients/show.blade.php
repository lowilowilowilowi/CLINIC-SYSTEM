@extends('layouts.app')

@section('content')
<div style="margin-bottom: 15px;">
    <a href="{{ route('patients.index') }}" class="btn-primary" style="display: inline-block; text-decoration: none;">&larr; Back</a>
</div>

<h1 class="page-title">PATIENT INFORMATION</h1>

<div style="display: flex; gap: 30px; align-items: flex-start;">
    <div style="flex: 1; max-width: 600px; background: white; border: 2px solid #4a4c8c; padding: 24px; border-radius: 6px;">
        <div style="margin-bottom: 15px;">
            <span style="font-weight: bold; color: #4a4c8c; display: block; font-size: 13px;">PATIENT ID:</span>
            <span style="font-size: 16px; font-weight: bold;">P-{{ str_pad($patient->id, 3, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div style="margin-bottom: 15px;">
            <span style="font-weight: bold; color: #4a4c8c; display: block; font-size: 13px;">FULL NAME:</span>
            <span style="font-size: 16px;">{{ strtoupper($patient->first_name) }} {{ strtoupper($patient->last_name) }}</span>
        </div>

        <div style="margin-bottom: 15px;">
            <span style="font-weight: bold; color: #4a4c8c; display: block; font-size: 13px;">EMAIL:</span>
            <span style="font-size: 16px;">{{ $patient->email ?? 'N/A' }}</span>
        </div>

        <div style="margin-bottom: 15px;">
            <span style="font-weight: bold; color: #4a4c8c; display: block; font-size: 13px;">CONTACT NO.:</span>
            <span style="font-size: 16px;">{{ $patient->phone }}</span>
        </div>

        <div style="margin-bottom: 15px;">
            <span style="font-weight: bold; color: #4a4c8c; display: block; font-size: 13px;">DATE OF BIRTH:</span>
            <span style="font-size: 16px;">{{ $patient->date_of_birth ?? 'N/A' }}</span>
        </div>

        <div style="margin-bottom: 15px;">
            <span style="font-weight: bold; color: #4a4c8c; display: block; font-size: 13px;">GENDER:</span>
            <span style="font-size: 16px;">{{ $patient->gender ?? 'N/A' }}</span>
        </div>

        <div>
            <span style="font-weight: bold; color: #4a4c8c; display: block; font-size: 13px;">ADDRESS:</span>
            <span style="font-size: 16px;">{{ $patient->address ?? 'N/A' }}</span>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 12px; width: 140px;">
        <a href="{{ route('patients.edit', $patient->id) }}" class="btn-primary" style="text-align: center; text-decoration: none;">Edit</a>

        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');" style="margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" style="width: 100%; background: #c53030; color: white; border: none; padding: 8px 16px; font-weight: bold; border-radius: 4px; cursor: pointer;">Delete</button>
        </form>
    </div>
</div>
@endsection