@extends('layouts.app')

@section('content')
<div style="padding: 10px; max-width: 600px; margin: 0 auto;">
    <h1 style="color: #4a4c8c; font-size: 22px; font-weight: bold; margin-bottom: 20px; letter-spacing: 1px;">RECORD PAYMENT</h1>

    <div style="background: white; border: 2px solid #4a4c8c; padding: 25px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <form method="POST" action="{{ route('billing.store') }}">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #4a4c8c; font-size: 14px; margin-bottom: 8px;">Select Unpaid Invoice:</label>
                <select name="invoice_id" required style="width: 100%; padding: 8px 12px; border: 1px solid #4a4c8c; border-radius: 4px; font-size: 14px; background: white; color: #333; outline: none;">
                    <option value="">-- Choose Invoice --</option>
                    @foreach($unpaidInvoices as $invoice)
                        <option value="{{ $invoice->id }}">
                            I-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }} - {{ strtoupper($invoice->patient->first_name ?? '') }} {{ strtoupper($invoice->patient->last_name ?? '') }} (₱{{ number_format($invoice->amount, 2) }})
                        </option>
                    @endforeach
                </select>
                @error('invoice_id')
                    <span style="color: #c62828; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; color: #4a4c8c; font-size: 14px; margin-bottom: 8px;">Amount Paid (₱):</label>
                <input type="number" step="0.01" name="amount_paid" required style="width: 100%; padding: 8px 12px; border: 1px solid #4a4c8c; border-radius: 4px; font-size: 14px; outline: none;" placeholder="0.00">
                @error('amount_paid')
                    <span style="color: #c62828; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; color: #4a4c8c; font-size: 14px; margin-bottom: 8px;">Payment Method:</label>
                <select name="payment_method" required style="width: 100%; padding: 8px 12px; border: 1px solid #4a4c8c; border-radius: 4px; font-size: 14px; background: white; color: #333; outline: none;">
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Health Insurance">Health Insurance</option>
                    <option value="GCash">GCash</option>
                </select>
                @error('payment_method')
                    <span style="color: #c62828; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('billing.index') }}" style="background: #ccc; color: #333; padding: 8px 16px; font-weight: bold; border-radius: 4px; text-decoration: none; font-size: 14px;">Cancel</a>
                <button type="submit" style="background: #4a4c8c; color: white; border: none; padding: 8px 20px; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 14px;">Submit Payment</button>
            </div>
        </form>
    </div>
</div>
@endsection