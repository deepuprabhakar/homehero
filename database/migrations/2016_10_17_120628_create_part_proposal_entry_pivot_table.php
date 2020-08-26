<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartProposalEntryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_proposal_entry', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('part_id')->unsigned()->index();
            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
            $table->integer('proposal_entry_id')->unsigned()->index();
            $table->foreign('proposal_entry_id')->references('id')->on('proposal_entries')->onDelete('cascade');
            $table->primary(['part_id', 'proposal_entry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('part_proposal_entry');
    }
}
