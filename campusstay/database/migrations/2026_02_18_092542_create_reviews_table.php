<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating');
            $table->text('comment');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->unique(['student_id', 'hostel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
