<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOrderToExtraSteps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extra_steps', function (Blueprint $table) {
            $table->string('type', 10)->default('extra_step');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extra_steps', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
