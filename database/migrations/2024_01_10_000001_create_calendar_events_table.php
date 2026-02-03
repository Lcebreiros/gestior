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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('organization_id')->nullable();

            // Tipo de evento
            $table->enum('event_type', [
                'order_delivery',
                'payment_deadline',
                'tax_due',
                'supply_reorder',
                'expense_payment',
                'reminder',
                'custom'
            ]);

            // Información del evento
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#7534C9'); // Color violeta por defecto

            // Fechas
            $table->dateTime('event_date'); // Fecha principal del evento
            $table->dateTime('due_date')->nullable(); // Fecha límite si aplica
            $table->dateTime('reminder_date')->nullable(); // Fecha para recordatorio
            $table->dateTime('completed_at')->nullable(); // Fecha de completado

            // Estado
            $table->enum('status', [
                'pending',
                'completed',
                'overdue',
                'cancelled'
            ])->default('pending');

            // Relación polimórfica con la entidad relacionada
            $table->morphs('related'); // Crea related_id y related_type

            // Metadatos adicionales
            $table->json('metadata')->nullable(); // Para datos adicionales flexibles
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, yearly

            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('event_date');
            $table->index('due_date');
            $table->index(['user_id', 'event_date']);
            $table->index(['event_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
