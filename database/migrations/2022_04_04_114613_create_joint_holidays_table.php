<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJointHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joint_holidays', function (Blueprint $table) {
            $table->id('joint_holiday_id');
            $table->string('occasion');
            $table->date('from_date');
            $table->date('to_date');
            $table->text('description')->nullable();
            $table->integer('count_day');
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
        Schema::dropIfExists('joint_holidays');
    }
}
