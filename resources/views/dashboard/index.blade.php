@extends('layouts.app')

@section('content')
<style>
    .dashboard {
        --dashboard-blue: #4a4c8c;
        --dashboard-surface: #fff;
        --dashboard-line: #d7d7df;
        --dashboard-text: #333;
        --dashboard-muted: #666;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        margin-bottom: 20px;
    }

    .dashboard-header .page-title { margin-bottom: 0; }
    .dashboard-date { color: var(--dashboard-muted); font-size: 15px; }
    .dashboard-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .dashboard-metric {
        min-height: 132px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 18px 20px;
        border: 2px solid var(--dashboard-blue);
        background: var(--dashboard-surface);
    }

    .dashboard-metric h2 {
        margin: 0;
        color: var(--dashboard-blue);
        font-size: 16px;
        line-height: 1.35;
    }

    .dashboard-metric p {
        margin: 12px 0 0;
        color: var(--dashboard-text);
        font-size: 34px;
        font-weight: 700;
        line-height: 1;
    }

    .today-visits { border-top: 2px solid var(--dashboard-blue); padding-top: 18px; }
    .today-visits h2 { margin: 0 0 12px; color: var(--dashboard-blue); font-size: 18px; }
    .dashboard-panels { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
    .dashboard-panel { min-width: 0; padding: 20px; border: 2px solid var(--dashboard-blue); border-radius: 6px; background: var(--dashboard-surface); }
    .dashboard-panel h2 { margin: 0 0 12px; padding-bottom: 10px; border-bottom: 2px solid #eee; color: var(--dashboard-blue); font-size: 18px; }
    .dashboard-table-wrap { overflow-x: auto; background: var(--dashboard-surface); }
    .dashboard-table { width: 100%; border-collapse: collapse; text-align: left; }
    .dashboard-table th {
        padding: 11px 12px;
        border-bottom: 2px solid var(--dashboard-blue);
        color: var(--dashboard-blue);
        font-size: 14px;
    }
    .dashboard-table td { padding: 12px; border-bottom: 1px solid var(--dashboard-line); color: var(--dashboard-text); }
    .dashboard-status { font-weight: 700; }
    .dashboard-status-completed { color: #287348; }
    .dashboard-status-cancelled { color: #a52e38; }
    .dashboard-status-scheduled { color: var(--dashboard-blue); }

</style>

<main class="dashboard">
    <header class="dashboard-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="dashboard-date">{{ $today->format('F j, Y') }}</p>
    </header>

    <section class="dashboard-metrics" aria-label="Today's clinic summary">
        <article class="dashboard-metric">
            <h2>Visits today</h2>
            <p>{{ $todayAppointmentsCount }}</p>
        </article>
        <article class="dashboard-metric">
            <h2>Completed today</h2>
            <p>{{ $completedTodayCount }}</p>
        </article>
        <article class="dashboard-metric">
            <h2>Unpaid invoices</h2>
            <p>{{ $pendingInvoicesCount }}</p>
        </article>
    </section>

    <div class="dashboard-panels">
        <section class="dashboard-panel" aria-labelledby="recent-appointments-title">
            <h2 id="recent-appointments-title">Recent Appointments</h2>
            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAppointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->first_name ?? 'N/A' }} {{ $appointment->patient->last_name ?? '' }}</td>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td>
                                    <span class="dashboard-status dashboard-status-{{ strtolower($appointment->status) }}">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: #666;">No recent appointments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="dashboard-panel" aria-labelledby="pending-invoices-title">
            <h2 id="pending-invoices-title">Pending Unpaid Invoices</h2>
            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unpaidInvoices as $invoice)
                            <tr>
                                <td>{{ $invoice->patient->first_name ?? 'N/A' }} {{ $invoice->patient->last_name ?? '' }}</td>
                                <td>
                                    <a href="{{ route('billing.payment') }}" class="btn-primary">Pay</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: #666;">No pending invoices.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
@endsection