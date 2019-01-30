<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorktimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worktimes', function (Blueprint $table) {
            $table->tinyInteger('work_id')->autoIncrement();
            $table->date('work_date');
            $table->timestampTz('work_timein')->nullable();
            $table->timestampTz('work_timeout')->nullable();
            $table->timestampTz('time_in')->nullable();
            $table->timestampTz('time_out')->nullable();
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
        Schema::dropIfExists('worktimes');
    }
}
