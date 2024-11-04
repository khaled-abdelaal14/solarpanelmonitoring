<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelReading extends Model
{
    use HasFactory;
    public function panelReadings(){
        return $this->hasMany(PanelReading::class);
    }
}
