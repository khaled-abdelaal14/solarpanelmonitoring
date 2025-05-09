<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsubdevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsubdevices', function (Blueprint $table) {
            $table->id();
            $table->string('name');             
            $table->integer('watt_per_hour'); 
            $table->enum ('status',['on','off'])->default('off'); 
            $table->enum ('has_device',['yes','no'])->default('no'); 

            $table->foreignId('subdevice_id')->references('id')->on('subdevices')->cascadeOnDelete();
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
        Schema::dropIfExists('subsubdevices');
    }
}
