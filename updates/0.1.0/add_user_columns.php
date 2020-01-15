<?php

namespace Bedard\Stripe\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class add_user_columns extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'bedard_stripe_customer_id')) {
            Schema::table('users', function ($table) {
                $table->string('bedard_stripe_customer_id')->default('');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'bedard_stripe_customer_id')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('bedard_stripe_customer_id');
            });
        }
    }
}
