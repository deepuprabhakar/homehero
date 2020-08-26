<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartWorkItemPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_work_item', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('part_id')->unsigned()->index();
            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
            $table->integer('work_item_id')->unsigned()->index();
            $table->foreign('work_item_id')->references('id')->on('work_items')->onDelete('cascade');
            $table->primary(['part_id', 'work_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('part_work_item');
    }
}
