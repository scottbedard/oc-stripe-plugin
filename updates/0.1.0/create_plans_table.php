<?php

namespace Bedard\Saas\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class create_plans_table extends Migration
{
    public function up()
    {
        Schema::create('bedard_saas_plans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_saas_plans');
    }
}
