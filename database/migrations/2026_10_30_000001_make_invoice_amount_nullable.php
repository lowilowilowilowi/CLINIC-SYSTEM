<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table): void {
            $table->decimal('amount', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('invoices')->whereNull('amount')->update(['amount' => 0]);

        Schema::table('invoices', function (Blueprint $table): void {
            $table->decimal('amount', 10, 2)->nullable(false)->change();
        });
    }
};