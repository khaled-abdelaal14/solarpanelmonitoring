<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;
    protected $guarded =[]; 
    public function device(){
        return $this->belongsTo(Device::class);
    }
    public function sensorReadings(){
        return $this->hasMany(SensorReading::class);
    }
}
