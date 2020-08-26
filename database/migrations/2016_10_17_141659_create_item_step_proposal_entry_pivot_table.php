<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemStepProposalEntryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_step_proposal_entry', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('item_step_id')->unsigned()->index();
            $table->foreign('item_step_id')->references('id')->on('item_steps')->onDelete('cascade');
            $table->integer('proposal_entry_id')->unsigned()->index();
            $table->foreign('proposal_entry_id')->references('id')->on('proposal_entries')->onDelete('cascade');
            $table->primary(['item_step_id', 'proposal_entry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('item_step_proposal_entry');
    }
}
