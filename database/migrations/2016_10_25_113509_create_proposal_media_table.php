<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_media', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('proposal_entry_id')->unsigned()->index();
            $table->foreign('proposal_entry_id')->references('id')->on('proposal_entries')->onDelete('cascade');
            $table->string('media');
            $table->string('type');
            $table->text('description')->nullable();
            $table->integer('flag')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('proposal_media');
    }
}
