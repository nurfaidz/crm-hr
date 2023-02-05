<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id('overtime_id');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->string('work_shift_name');
            $table->time('work_shift_start');
            $table->time('work_shift_end');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status', 5)->default('opd');
            $table->dateTime('update_time')->nullable();
            $table->unsignedBigInteger('update_by')->nullable();
            $table->foreign('update_by')->references('id')->on('users');
            $table->text('notes')->nullable();
            $table->text('reject_reason')->nullable();
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
        Schema::dropIfExists('overtimes');
    }
}
