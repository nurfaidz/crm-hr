<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalReimbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_reimbursements', function (Blueprint $table) {
            $table->id('medical_reimbursement_id');
            $table->bigInteger('employee_id');
            $table->date('reimbursement_date');
            $table->tinyInteger('category');
            $table->tinyInteger('outpatient_type')->nullable();
            $table->integer('amount');
            $table->integer('total_reimburse')->nullable();
            $table->text('attachment');
            $table->text('notes');
            $table->char('status', 5);
            $table->bigInteger('cancel_by')->nullable();
            $table->date('cancel_date')->nullable();
            $table->bigInteger('approve_by_manager')->nullable();
            $table->date('approve_manager_date')->nullable();
            $table->bigInteger('approve_by_human_resources')->nullable();
            $table->date('approve_human_resources_date')->nullable();
            $table->bigInteger('approve_by_finance')->nullable();
            $table->date('approve_finance_date')->nullable();
            $table->bigInteger('reject_by')->nullable();
            $table->date('reject_date')->nullable();
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
        Schema::dropIfExists('medical_reimbursements');
    }
}
