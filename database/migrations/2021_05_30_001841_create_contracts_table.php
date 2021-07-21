<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->date("request_date");
            $table->date("term_date")->nullable();
            $table->string("code")->nullable();
            $table->string("objective")->nullable();
            $table->string("resolution")->nullable();
            $table->string("observations")->nullable();
            $table->enum('state',['en proceso','archivado'])->default('en proceso');
            $table->unsignedBigInteger('id_user_assigned');
            $table->unsignedBigInteger('id_user_creator');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('contract_type_id')->nullable();
            $table->foreign('id_user_assigned')->references('id')->on('users');
            $table->foreign('id_user_creator')->references('id')->on('users');
            $table->foreign('business_id')->references('id')->on('businesses');
            $table->foreign('contract_type_id')->references('id')->on('contract_types');
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
        Schema::dropIfExists('contracts');
    }
}
