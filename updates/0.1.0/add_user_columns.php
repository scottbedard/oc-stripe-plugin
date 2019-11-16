<?php

namespace Bedard\Saas\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class add_user_columns extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'bedard_saas_customer_id')) {
            Schema::table('users', function ($table) {
                $table->string('bedard_saas_customer_id')->default('');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'bedard_saas_customer_id')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('bedard_saas_customer_id');
            });
        }
    }
}
