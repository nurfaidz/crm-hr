<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->unsignedBigInteger('overtime_id')->nullable();
            $table->foreign('overtime_id')->references('overtime_id')->on('overtimes');
            $table->date('date');
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->integer('late_duration')->nullable()->comment("minutes");
            $table->integer('overtime_duration')->nullable()->comment("minutes");
            $table->time('working_hour')->nullable();
            $table->string('note_check_in')->nullable();
            $table->string('note_check_out')->nullable();
            $table->text('note')->nullable();
            $table->json('location')->nullable();
            $table->string('image')->nullable();
            $table->string('image_manual_attendance')->nullable();
            $table->char('status', 5)->nullable();
            $table->unsignedBigInteger('status_by')->nullable();
            $table->date('status_date')->nullable();
            $table->text('cancel_reason')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
