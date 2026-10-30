@extends('layouts.app')

@section('content')
<div style="padding: 10px;">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
        <div>
            <h1 style="color: #4a4c8c; font-size: 22px; font-weight: bold; margin-bottom: 6px;">INVOICE CHANGE HISTORY</h1>
            <div style="color: #555;">
                I-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }} ·
                {{ strtoupper($invoice->patient->first_name ?? '') }} {{ strtoupper($invoice->patient->last_name ?? '') }}
            </div>
        </div>
        <a href="{{ route('billing.index') }}" style="color: #4a4c8c; font-weight: bold;">Back to Billing</a>
    </div>

    <div style="overflow-x: auto; background: white; border: 2px solid #4a4c8c; border-radius: 4px;">
        <table style="width: 100%; min-width: 760px; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: #f4f5f7; border-bottom: 2px solid #4a4c8c; color: #4a4c8c;">
                    <th style="padding: 12px 15px;">Date and time</th>
                    <th style="padding: 12px 15px;">User</th>
                    <th style="padding: 12px 15px;">Action</th>
                    <th style="padding: 12px 15px;">Changes</th>
                    <th style="padding: 12px 15px;">IP address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($changeLogs as $log)
                    <tr style="border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                        <td style="padding: 12px 15px; white-space: nowrap;">{{ $log->created_at->format('M d, Y h:i:s A') }}</td>
                        <td style="padding: 12px 15px;">{{ $log->user?->name ?? 'System' }}</td>
                        <td style="padding: 12px 15px;">{{ ucwords(str_replace('_', ' ', $log->action)) }}</td>
                        <td style="padding: 12px 15px;">
                            @foreach($log->changes as $field => $values)
                                <div style="margin-bottom: 6px;">
                                    <strong>{{ ucwords(str_replace('_', ' ', $field)) }}:</strong>
                                    {{ $values['old'] ?? 'N/A' }} &rarr; {{ $values['new'] ?? 'N/A' }}
                                </div>
                            @endforeach
                        </td>
                        <td style="padding: 12px 15px;">{{ $log->ip_address ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px; text-align: center; color: #666;">No changes recorded for this invoice.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection