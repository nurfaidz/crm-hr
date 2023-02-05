<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id('leave_application_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->unsignedBigInteger('leave_period_id');
            $table->dateTime('application_from_date');
            $table->dateTime('application_to_date');
            $table->date('application_date');
            $table->text('purpose');
            $table->integer('number_of_day');
            $table->char('status', 5)->default('lpd');
            $table->tinyInteger('option_request')->nullable();
            $table->unsignedBigInteger('option_leave_id')->nullable();
            $table->unsignedBigInteger('approve_by')->nullable();
            $table->date('approve_date')->nullable();
            $table->unsignedBigInteger('cancel_by')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->date('cancel_date')->nullable();
            $table->unsignedBigInteger('close_by')->nullable();
            $table->date('close_date')->nullable();
            $table->unsignedBigInteger('reject_by')->nullable();
            $table->date('reject_date')->nullable();
            $table->text('reject_reason')->nullable();
            $table->string('sick_letter', 255);
            $table->integer('sick_active')->nullable();
            $table->date('sick_expired')->nullable();
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
        Schema::dropIfExists('leave_applications');
    }
}
