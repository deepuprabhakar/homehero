<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToItemStepProposalEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_step_proposal_entry', function (Blueprint $table) {
            $table->string('type', 5)->default('step');
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
            $table->dropColumn('type');
        });
    }
}
