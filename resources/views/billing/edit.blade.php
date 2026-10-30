@extends('layouts.app')

@section('content')
<div style="padding: 10px; max-width: 600px; margin: 0 auto;">
    <h1 style="color: #4a4c8c; font-size: 22px; font-weight: bold; margin-bottom: 20px;">EDIT INVOICE</h1>

    <div style="background: white; border: 2px solid #4a4c8c; padding: 25px; border-radius: 6px;">
        <form method="POST" action="{{ route('billing.update', $invoice->id) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #4a4c8c; margin-bottom: 8px;">Patient:</label>
                <input type="text" disabled value="{{ strtoupper($invoice->patient->first_name ?? '') }} {{ strtoupper($invoice->patient->last_name ?? '') }}" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; background: #eee; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #4a4c8c; margin-bottom: 8px;">Amount (₱):</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $invoice->amount) }}" required style="width: 100%; padding: 8px 12px; border: 1px solid #4a4c8c; border-radius: 4px; outline: none;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; color: #4a4c8c; margin-bottom: 8px;">Status:</label>
                <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #4a4c8c; border-radius: 4px; background: white;">
                    <option value="Unpaid" {{ $invoice->status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Paid" {{ $invoice->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('billing.index') }}" style="background: #ccc; color: #333; padding: 8px 16px; font-weight: bold; border-radius: 4px; text-decoration: none;">Cancel</a>
                <button type="submit" style="background: #4a4c8c; color: white; border: none; padding: 8px 20px; font-weight: bold; border-radius: 4px; cursor: pointer;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection