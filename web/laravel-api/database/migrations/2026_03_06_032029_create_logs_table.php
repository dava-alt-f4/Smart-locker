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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('locker_id')->nullable()->constrained()->nullOnDelete();
            $table->string('rfid_uid');
            $table->enum('status', ['success', 'failed']);
            $table->timestamp('access_time')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
