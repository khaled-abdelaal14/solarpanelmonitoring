<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryReading extends Model
{
    use HasFactory;
    protected $guarded =[]; 
    public function battery(){
        return $this->belongsTo(Battery::class);
    }
}
