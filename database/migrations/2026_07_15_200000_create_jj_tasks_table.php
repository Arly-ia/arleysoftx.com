<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jj_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('instalacion');
            $table->string('priority')->default('media');
            $table->string('status')->default('pendiente');
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jj_tasks');
    }
};
