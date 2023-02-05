<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('gender', 6);
            $table->string('blood_type', 2);
            $table->string('nik', 16)->unique();
            $table->unsignedBigInteger('religion_id');
            $table->integer('marital_status_id');
            $table->text('address');
            $table->integer('postal_code');
            $table->string('phone', 13);
            $table->string('passport', 7)->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('github_link')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('job_class_id');
            $table->unsignedBigInteger('job_position_id');
            $table->unsignedBigInteger('employment_status_id');
            $table->unsignedBigInteger('work_shift_id');
            $table->date('date_of_joining');
            $table->date('date_of_leaving')->nullable();
            $table->tinyInteger('status');
            $table->string('nip', 10)->unique()->comment("MMYY0000-X");
            $table->string('basic_salary');
            $table->unsignedBigInteger('salary_type_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('bank_account_number', 16);
            $table->string('bank_account_holder');
            $table->string('npwp', 17);
            $table->string('image')->nullable();
            $table->string('vaccine_images')->nullable();
            $table->string('nationality')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
