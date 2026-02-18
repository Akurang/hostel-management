<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->enum('role', ['student', 'manager', 'admin'])->default('student')->after('email');
            $table->string('student_id')->nullable()->unique()->after('role');
            $table->string('phone')->nullable()->after('student_id');
            $table->string('university')->nullable()->after('phone');
            $table->string('academic_year')->nullable()->after('university');
            $table->boolean('is_active')->default(true)->after('academic_year');
            $table->timestamp('approved_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'role',
                'student_id',
                'phone',
                'university',
                'academic_year',
                'is_active',
                'approved_at',
            ]);
        });
    }
};
