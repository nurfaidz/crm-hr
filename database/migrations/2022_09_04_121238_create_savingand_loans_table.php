<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingandLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savingand_loans', function (Blueprint $table) {
            $table->id('cooperative_id');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->date('date');
            $table->tinyInteger('type')->nullable();
            $table->integer('balance')->nullable();
            $table->integer('expenses')->nullable();
            // $table->tinyInteger('outpatient_type')->nullable();
            $table->integer('amount');
            $table->integer('total_saving_loan')->nullable();
            $table->text('note');
            $table->text('attachment');
            $table->text('payment_evidence')->nullable();
            $table->char('status', 5);
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
        Schema::dropIfExists('savingand_loans');
    }
}
