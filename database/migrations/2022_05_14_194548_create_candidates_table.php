<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('candidate_userid')->unsigned();  // candidate user id 
            $table->foreign('candidate_userid')->references('id')->on('candidateusers')->onUpdte('cascade')->onDelete('restrict');
            $table->string('candidate_names');
            $table->string('candidate_phonenumber');
            $table->string('candidate_email');
            $table->string('nationalid');
            $table->string('cv');
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
        Schema::dropIfExists('candidates');
    }
};
