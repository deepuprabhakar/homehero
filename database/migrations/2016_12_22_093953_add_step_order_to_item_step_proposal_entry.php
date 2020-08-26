<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStepOrderToItemStepProposalEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_step_proposal_entry', function (Blueprint $table) {
            $table->integer('step_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_step_proposal_entry', function (Blueprint $table) {
            $table->dropColumn('step_order');
        });
    }
}
