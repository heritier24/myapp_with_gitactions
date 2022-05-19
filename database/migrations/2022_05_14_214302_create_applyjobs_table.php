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
        Schema::create('applyjobs', function (Blueprint $table) {
            $table->id();
            // foreign key candidate id 
            $table->bigInteger('candidateid')->unsigned();
            $table->foreign('candidateid')->references('id')->on('candidates')->onUpdte('cascade')->onDelete('cascade');
            $table->bigInteger('jobid')->unsigned();  // foreign key 
            $table->foreign('jobid')->references('id')->on('jobs')->onUpdte('cascade')->onDelete('cascade');
            $table->date('date_applied');
            $table->string('status')->default('Pending');  // pending , Selected , Cancelled
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
        Schema::dropIfExists('applyjobs');
    }
};
