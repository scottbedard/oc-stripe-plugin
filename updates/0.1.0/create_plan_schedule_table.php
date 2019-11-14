<?php

namespace Bedard\Saas\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class create_plan_schedule_table extends Migration
{
    public function up()
    {
        Schema::create('bedard_saas_plan_schedule', function (Blueprint $table) {
            $table->integer('plan_id')->unsigned();
            $table->integer('schedule_id')->unsigned();
            $table->primary(['plan_id', 'schedule_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_saas_plan_schedule');
    }
}
