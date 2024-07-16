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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('username')->unique();
            $table->string('password');
            $table->string('student_code')->unique();
            $table->string('full_name');
            $table->string('grade');
            $table->date('birthday');
            $table->string('gender');
            $table->string('address');
            $table->string('school');
            $table->string('district');
            $table->string('city');
            $table->string('parent_phone');
            $table->string('avatar')->nullable();
            $table->integer('status')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
