<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (Invoice $invoice): void {
            $changes = [];

            foreach (['patient_id', 'appointment_id', 'amount', 'status', 'due_date'] as $field) {
                $changes[$field] = ['old' => null, 'new' => $invoice->getAttribute($field)];
            }

            InvoiceChangeLog::record($invoice, 'created', $changes);
        });

        static::updated(function (Invoice $invoice): void {
            $changes = [];

            foreach (['patient_id', 'appointment_id', 'amount', 'status', 'due_date'] as $field) {
                if (array_key_exists($field, $invoice->getChanges())) {
                    $changes[$field] = [
                        'old' => $invoice->getOriginal($field),
                        'new' => $invoice->getAttribute($field),
                    ];
                }
            }

            if ($changes !== []) {
                InvoiceChangeLog::record($invoice, 'updated', $changes);
            }
        });
    }

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'amount',
        'status',
        'due_date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function changeLogs()
    {
        return $this->hasMany(InvoiceChangeLog::class);
    }
}