@extends('layouts.app')

@section('content')
<h1 class="page-title">Register New Patient</h1>

<div style="max-width: 600px; background: white; border: 2px solid #4a4c8c; padding: 24px; border-radius: 6px;">
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">First Name</label>
            <input type="text" name="first_name" required class="input-box" style="width: 100%;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Last Name</label>
            <input type="text" name="last_name" required class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Phone</label>
            <input type="text" name="phone" required class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Date of Birth</label>
            <input type="date" name="date_of_birth" class="input-box" style="width: 100%;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Gender</label>
            <select name="gender" class="input-box" style="width: 100%;">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; font-size: 14px; color: #4a4c8c; margin-bottom: 5px;">Address</label>
            <textarea name="address" class="input-box" style="width: 100%; height: 80px; resize: vertical;"></textarea>
        </div>

        <div>
            <button type="submit" class="btn-primary">Save Patient</button>
            <a href="{{ route('patients.index') }}" style="margin-left: 15px; color: #4a4c8c; font-weight: bold; text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection