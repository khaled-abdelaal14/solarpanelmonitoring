<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanelReading extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function panel(){
        return $this->belongsTo(Battery::class);
    }
}
