<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->string('sex')->nullable();
            $table->string('period')->nullable();
            $table->string('dob')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country_of_residence')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('id_passport_number')->nullable();
            $table->string('student_fathers_mobile_number')->nullable();
            $table->string('student_mothers_mobile_number')->nullable();
            $table->string('student_fathers_email')->nullable();
            $table->string('student_mothers_email')->nullable();
            $table->string('preferred_language')->nullable();
            $table->string('student_fathers_name')->nullable();
            $table->string('student_fathers_employer')->nullable();
            $table->string('student_mothers_name')->nullable();
            $table->string('student_mothers_employer')->nullable();
            $table->string('student_social_status')->nullable();
            $table->string('student_disease')->nullable();
            $table->string('participated')->nullable();
            $table->string('al_nooraniah')->nullable();
            $table->string('student_id')->nullable();
            $table->string('parent_id')->nullable();
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
        Schema::dropIfExists('registers');
    }
}
