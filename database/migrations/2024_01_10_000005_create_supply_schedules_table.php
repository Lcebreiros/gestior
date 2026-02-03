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
        Schema::create('supply_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('organization_id')->nullable();

            // Insumo y proveedor
            $table->foreignId('supply_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');

            // Niveles de stock
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->decimal('reorder_level', 10, 2); // Nivel mínimo para reordenar
            $table->decimal('reorder_quantity', 10, 2); // Cantidad a pedir
            $table->string('unit')->default('kg'); // Unidad de medida

            // Fechas de compra
            $table->dateTime('last_purchase_date')->nullable(); // Última compra
            $table->dateTime('next_reorder_date')->nullable(); // Próxima compra sugerida
            $table->dateTime('expected_delivery_date')->nullable(); // Entrega esperada
            $table->dateTime('expiration_date')->nullable(); // Vencimiento (perecederos)

            // Costos
            $table->decimal('last_purchase_cost', 10, 2)->nullable();
            $table->decimal('estimated_next_cost', 10, 2)->nullable();

            // Estado
            $table->enum('status', [
                'in_stock',      // En stock
                'low_stock',     // Stock bajo
                'out_of_stock',  // Sin stock
                'ordered',       // Pedido realizado
                'in_transit'     // En tránsito
            ])->default('in_stock');

            // Configuración de reorden automático
            $table->boolean('auto_reorder')->default(false);
            $table->integer('lead_time_days')->default(7); // Días de espera para entrega

            // Notas
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Índices
            $table->index('supply_id');
            $table->index('supplier_id');
            $table->index('next_reorder_date');
            $table->index('expiration_date');
            $table->index(['status', 'next_reorder_date']);
            $table->index(['user_id', 'supply_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_schedules');
    }
};
