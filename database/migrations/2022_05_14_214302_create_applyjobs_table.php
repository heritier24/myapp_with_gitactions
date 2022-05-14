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
            $table->integer('candidateid');  // foreign key candidate id 
            $table->integer('jobid');  // foreign key 
            $table->date('date_applied');
            $table->string('status');  // pending , Selected , Cancelled
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
