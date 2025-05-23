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
            $table->string('email')->unique(); 
            $table->string('phone')->unique();
            $table->string('city');    
            $table->string('image')->nullable();
            $table->text('fcm_token')->nullable();          
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null')->onUpdate('cascade'); 

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
        Schema::dropIfExists('users');
    }
}
