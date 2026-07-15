<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jj_task_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('jj_tasks')->onDelete('cascade');
            $table->string('type'); // antes, despues
            $table->string('filename');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jj_task_photos');
    }
};
