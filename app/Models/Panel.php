<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;
    protected $guarded =[]; 

    public function device(){
        return $this->belongsTo(Device::class);
    }

    public function panelReadings(){
        return $this->hasMany(PanelReading::class);
    }


}
