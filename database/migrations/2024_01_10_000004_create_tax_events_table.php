<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tax_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('organization_id')->nullable();

            // Tipo de impuesto
            $table->enum('tax_type', [
                'income_tax',    // Impuesto a las ganancias
                'vat',           // IVA
                'payroll_tax',   // Impuesto sobre nómina
                'sales_tax',     // Impuesto sobre ventas
                'property_tax',  // Impuesto inmobiliario
                'other'          // Otro
            ]);

            // Información del impuesto
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('estimated_amount', 10, 2)->nullable();
            $table->decimal('actual_amount', 10, 2)->nullable();

            // Período fiscal
            $table->enum('period', [
                'monthly',
                'quarterly',
                'biannual',
                'yearly'
            ])->default('monthly');

            $table->date('period_start');
            $table->date('period_end');

            // Fechas importantes
            $table->dateTime('filing_deadline'); // Fecha límite de presentación
            $table->dateTime('payment_deadline'); // Fecha límite de pago
            $table->dateTime('filed_date')->nullable(); // Fecha de presentación
            $table->dateTime('paid_date')->nullable(); // Fecha de pago

            // Estado
            $table->enum('filing_status', [
                'pending',
                'filed',
                'overdue',
                'exempted'
            ])->default('pending');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'overdue',
                'exempted'
            ])->default('pending');

            // Información adicional
            $table->string('reference_number')->nullable(); // Número de referencia o comprobante
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Índices
            $table->index('tax_type');
            $table->index('filing_deadline');
            $table->index('payment_deadline');
            $table->index(['user_id', 'period_start']);
            $table->index(['filing_status', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_events');
    }
};
