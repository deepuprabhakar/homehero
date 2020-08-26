<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('type_id')->unsigned()->index();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->integer('sub_type_id')->unsigned()->index();
            $table->foreign('sub_type_id')->references('id')->on('sub_types')->onDelete('cascade');
            $table->text('detail');
            $table->string('item_id')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('est_hrs', 5, 2);
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
        Schema::drop('work_items');
    }
}
