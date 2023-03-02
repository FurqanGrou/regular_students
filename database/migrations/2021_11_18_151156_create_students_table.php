<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
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
            $table->string('serial_number');
            $table->string('name');
            $table->enum('section', [1, 2]); // 1 => Male, 2 => Female
            $table->enum('status', [0, 1]);  // 0 => Dropped Out, 1 => Regular
            $table->unique(['serial_number', 'section']);
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
}
