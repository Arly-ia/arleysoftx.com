<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_key', 24)->unique();          // TASK-XXXX-XXXX-XXXX
            $table->string('email')->nullable();                   // Email del comprador
            $table->string('stripe_session_id')->unique()->nullable(); // Session ID de Stripe
            $table->json('device_fingerprints')->nullable();       // Array de huellas (máx. 2)
            $table->boolean('is_active')->default(true);          // Se puede revocar
            $table->integer('max_devices')->default(2);           // Dispositivos permitidos
            $table->timestamp('first_accessed_at')->nullable();   // Primera vez que usó la clave
            $table->timestamps();

            $table->index('email');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_licenses');
    }
};
