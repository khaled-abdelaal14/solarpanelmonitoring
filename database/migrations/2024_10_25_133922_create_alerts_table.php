<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert_type'); // نوع التنبيه (مثل: "Battery Low", "Sensor Failure")
            $table->text('message'); // رسالة التنبيه
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade'); // الجهاز المعني
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
        Schema::dropIfExists('alerts');
    }
}
