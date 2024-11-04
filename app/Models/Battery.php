<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    use HasFactory;
    protected $guarded =[]; 
    public function device(){
        return $this->belongsTo(Battery::class);
    }
    public function batteryReadings(){
        return $this->hasMany(BatteryReading::class);
    }
}
