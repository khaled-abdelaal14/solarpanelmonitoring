<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained('panels')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('energy_stored');
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
        Schema::dropIfExists('panel_readings');
    }
}
