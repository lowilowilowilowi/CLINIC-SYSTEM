@extends('layouts.app')

@section('content')
<div style="padding: 10px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="color: #4a4c8c; font-size: 22px; font-weight: bold; letter-spacing: 1px;">BILLING MANAGEMENT</h1>
        <a href="{{ route('billing.payment') }}" style="background: #4a4c8c; color: white; padding: 8px 16px; font-weight: bold; border-radius: 4px; text-decoration: none; font-size: 14px;">Record Payment +</a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border: 2px solid #4a4c8c; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: #f4f5f7; border-bottom: 2px solid #4a4c8c; color: #4a4c8c;">
                    <th style="padding: 12px 15px;">Invoice ID</th>
                    <th style="padding: 12px 15px;">Patient Name</th>
                    <th style="padding: 12px 15px;">Amount</th>
                    <th style="padding: 12px 15px;">Status</th>
                    <th style="padding: 12px 15px;">Date Issued</th>
                    <th style="padding: 12px 15px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 15px; font-weight: bold; color: #4a4c8c;">I-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td style="padding: 12px 15px;">{{ strtoupper($invoice->patient->first_name ?? '') }} {{ strtoupper($invoice->patient->last_name ?? '') }}</td>
                        <td style="padding: 12px 15px;">₱{{ number_format($invoice->amount, 2) }}</td>
                        <td style="padding: 12px 15px;">
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: {{ $invoice->status === 'Paid' ? '#d4edda' : '#f8d7da' }}; color: {{ $invoice->status === 'Paid' ? '#155724' : '#721c24' }};">
                                {{ $invoice->status }}
                            </span>
                        </td>
                        <td style="padding: 12px 15px; color: #666;">{{ $invoice->created_at->format('M d, Y') }}</td>
                        <td style="padding: 12px 15px;">
                            <a href="{{ route('billing.edit', $invoice->id) }}" style="background: #4a4c8c; color: white; padding: 4px 8px; border-radius: 3px; text-decoration: none; font-size: 12px;">Edit</a>
                            <a href="{{ route('billing.history', $invoice->id) }}" style="margin-left: 6px; color: #4a4c8c; font-weight: bold; font-size: 12px;">History</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #666;">No invoices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection