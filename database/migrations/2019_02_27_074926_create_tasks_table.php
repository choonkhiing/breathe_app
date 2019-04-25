<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('collection_id')->nullable();

            $table->string('title');
            $table->string('description')->nullable();
            
            $table->unsignedInteger('priority');
            $table->datetime('due_date')->nullable();
            $table->integer('status')->default('0');
            $table->unsignedInteger('group_id')->default('0');
            $table->unsignedInteger('user_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
