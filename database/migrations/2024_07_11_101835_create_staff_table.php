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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('gender');
            $table->string('contact_no');
            $table->string('second_contact_no')->nullable();
            $table->date('birthday')->nullable();
            $table->string('nic_no');
            $table->text('address')->nullable();
            $table->decimal('basic', 8, 2);
            $table->decimal('fixed_allowance', 8, 2)->nullable();
            $table->string('working_days_and_hours')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
