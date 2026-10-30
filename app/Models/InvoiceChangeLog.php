<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InvoiceChangeLog extends Model
{
    protected $fillable = [
        'invoice_id',
        'user_id',
        'action',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    public static function record(Invoice $invoice, string $action, array $changes): self
    {
        return static::create([
            'invoice_id' => $invoice->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}