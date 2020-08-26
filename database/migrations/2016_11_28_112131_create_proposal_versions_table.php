<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_versions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('proposal_id')->unsigned()->index();
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            $table->string('title');
            $table->string('remarks');
            $table->string('file')->nullable();
            $table->string('sent')->default('No');
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
        Schema::drop('proposal_versions');
    }
}
