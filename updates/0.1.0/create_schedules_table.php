<?php

namespace Bedard\Saas\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class create_schedules_table extends Migration
{
    public function up()
    {
        Schema::create('bedard_saas_schedules', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->smallInteger('calendar_duration')->unsigned();
            $table->enum('calendar_unit', ['day', 'month', 'year']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_saas_schedules');
    }
}
