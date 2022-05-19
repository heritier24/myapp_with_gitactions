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
        Schema::create('candidateselecteds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('applied_id')->unsigned();  // foreign key applyjobs
            $table->foreign('applied_id')->references('id')->on('applyjobs')->onUpdte('cascade')->onDelete('restrict');
            $table->date('date_selected');
            $table->string('status');  // aproved 
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
        Schema::dropIfExists('candidateselecteds');
    }
};
