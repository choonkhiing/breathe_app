<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupinvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupinvitations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inviter');
            $table->unsignedInteger('invitee');
            $table->unsignedInteger('group_id');
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('inviter')->references('id')->on('users');
            $table->foreign('invitee')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groupinvitations');
    }
}
