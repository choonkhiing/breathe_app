<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->integer('permission')->default('0');  // 0 for view, 1 for edit
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups');
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
        Schema::dropIfExists('group_details');
    }
}
