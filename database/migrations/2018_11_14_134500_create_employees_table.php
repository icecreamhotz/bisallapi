<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('emp_code');
            $table->string('emp_password')->nullable();
            $table->string('emp_name');
            $table->string('emp_lastname');
            $table->string('emp_tel');
            $table->string('emp_passport');
            $table->string('emp_address');
            $table->string('avatar');
            $table->tinyInteger('work_id')->references('work_id')->on('worktimes');
            $table->tinyInteger('pos_id')->references('pos_id')->on('positions');
            $table->char('status', 1);
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
