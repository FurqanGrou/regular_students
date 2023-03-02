<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribes', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('country_id');
            $table->string('email');
            $table->string('money_transfer_image_path')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_owner')->nullable();
            $table->date('transfer_date')->nullable();
            $table->string('bank_reference_number')->nullable();
            $table->string('payment_method');
            $table->string('payment_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamps();

            // first_step => intro info             1
            // الاسئلة الشاعئعة غير مطلوبة
            // الاستعلام عن بيانات الطالب + تسجيل البريد              2
            // ازالة تغيير الموعد
            // ازالة الملاحظات

            // الموافقة على الشروط والاحكام - اخر خطوة
            // الخطوة الاخيرة اختيار نوع طريقة الدفع + اتمام الدفع
            //  (الدفع عبر البطاقة التشيك اووت + الدفع عبر الحوالة البنكي HSBC)

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribes');
    }
}
