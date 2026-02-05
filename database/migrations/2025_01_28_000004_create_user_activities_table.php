<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('activity', 255);
            $table->timestamp('activity_time')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
