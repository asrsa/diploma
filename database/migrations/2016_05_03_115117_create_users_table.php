<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100)->unique;
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('password', 100);
            $table->string('gender', 1)->default('U');
            $table->date('birthday');
            $table->string('avatar', 200)->nullable();
            $table->string('activate_token', 100)->nullable();
            $table->tinyInteger('active')->default(0);
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
