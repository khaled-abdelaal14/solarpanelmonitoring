<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->enum('status',['on','off'])->default('off');
            $table->enum('mode',['auto','manual'])->default('auto');        
            $table->string('ip_address')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->unique()->nullonDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('devices');
    }
}
