<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name');
            $table->string('dni');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('state')->default(true);
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('supervisor_to_report')->nullable();
            $table->rememberToken();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('supervisor_to_report')->references('id')->on('users');
            $table->text('profile_photo_path')->nullable();
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
        Schema::dropIfExists('users');
    }
}
