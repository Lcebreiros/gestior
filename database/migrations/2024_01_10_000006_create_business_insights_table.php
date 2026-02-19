<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('business_insights')) {
            return;
        }

        Schema::create('business_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('organization_id')->nullable()->index();
            $table->string('type', 50)->index(); // stock_alert, revenue_opportunity, cost_warning, client_retention, trend, prediction, reminder
            $table->string('priority', 20)->index(); // critical, high, medium, low
            $table->string('title');
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->string('action_label')->nullable();
            $table->string('action_route')->nullable();
            $table->boolean('is_dismissed')->default(false)->index();
            $table->string('dedupe_key')->nullable()->index(); // Para evitar duplicados
            $table->string('priority_color', 10)->default('#6B7280');
            $table->string('type_icon', 50)->default('lightbulb');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'organization_id', 'is_dismissed']);
            $table->index(['user_id', 'type', 'is_dismissed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_insights');
    }
};
