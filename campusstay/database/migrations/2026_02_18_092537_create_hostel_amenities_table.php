<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_amenities', function (Blueprint $table): void {
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
            $table->primary(['hostel_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_amenities');
    }
};
