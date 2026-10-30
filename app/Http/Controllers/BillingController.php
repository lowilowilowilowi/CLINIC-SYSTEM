<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceChangeLog;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('patient')->latest()->get();
        return view('billing.index', compact('invoices'));
    }

    public function createPayment()
    {
        $unpaidInvoices = Invoice::with('patient')->where('status', 'Unpaid')->get();
        return view('billing.payment', compact('unpaidInvoices'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount_paid' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);

        DB::transaction(function () use ($invoice, $request): void {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount_paid' => $request->amount_paid,
                'payment_method' => $request->payment_method,
            ]);

            $invoice->update([
                'status' => 'Paid',
                'amount' => $request->amount_paid,
            ]);

            InvoiceChangeLog::record($invoice, 'payment_recorded', [
                'payment_id' => ['old' => null, 'new' => $payment->id],
                'amount_paid' => ['old' => null, 'new' => $payment->amount_paid],
                'payment_method' => ['old' => null, 'new' => $payment->payment_method],
                'payment_date' => ['old' => null, 'new' => $payment->payment_date],
            ]);
        });

        return redirect()->route('billing.index');
    }

    public function edit($id)
    {
        $invoice = Invoice::with('patient')->findOrFail($id);
        return view('billing.edit', compact('invoice'));
    }

    public function history($id)
    {
        $invoice = Invoice::with('patient')->findOrFail($id);
        $changeLogs = $invoice->changeLogs()->with('user')->latest()->get();

        return view('billing.history', compact('invoice', 'changeLogs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:Unpaid,Paid',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'amount' => $request->amount,
            'status' => $request->status,
        ]);

        return redirect()->route('billing.index')->with('success', 'Invoice updated successfully.');
    }
}