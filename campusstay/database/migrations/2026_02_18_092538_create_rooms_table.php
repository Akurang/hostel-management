<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['1-in-a-room', '2-in-a-room', '3-in-a-room']);
            $table->decimal('price_per_semester', 10, 2);
            $table->integer('total_beds');
            $table->integer('available_beds');
            $table->json('room_amenities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
