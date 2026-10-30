@extends('layouts.app')

@section('content')
<h1 class="page-title">Edit Patient</h1>

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
    <form action="{{ route('patients.update', $patient->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" value="{{ old('email', $patient->email) }}" class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth) }}" class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Gender</label>
            <select name="gender" class="input-box" style="width: 100%;">
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender', $patient->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $patient->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender', $patient->gender) === 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Address</label>
            <textarea name="address" class="input-box" style="width: 100%; height: 80px; resize: vertical;">{{ old('address', $patient->address) }}</textarea>
        </div>

        <div>
            <button type="submit" class="btn-primary">Update Patient</button>
            <a href="{{ route('patients.show', $patient->id) }}" style="margin-left: 15px; color: #4a4c8c; font-weight: bold; text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection