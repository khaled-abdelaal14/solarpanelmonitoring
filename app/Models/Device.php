<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $guarded =[]; 

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function batteries(){
        return $this->hasMany(Battery::class);
    }
    public function sensors(){
        return $this->hasMany(Sensor::class);
    }
    public function panels(){
        return $this->hasMany(Panel::class);
    }

}
