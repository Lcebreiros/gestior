<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('trial_requests', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (!Schema::hasColumn('trial_requests', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trial_requests', function (Blueprint $table) {
            $table->dropColumn(['password', 'user_id']);
        });
    }
};
