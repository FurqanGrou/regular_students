<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->float('discount_value')->nullable();
            $table->float('discount_percent')->nullable();
            $table->string('discount_reason');
            $table->enum('status', ['0', '1'])->default('1');

            $table->softDeletes();
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
        Schema::dropIfExists('custom_prices');
    }
}
