<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('roll_number')->unique();
            $table->string('email')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('class');
            $table->string('section')->nullable();
            $table->date('admission_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('blood_group', 10)->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'passed_out'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
