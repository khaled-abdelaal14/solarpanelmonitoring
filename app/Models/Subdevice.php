<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdevice extends Model
{
    use HasFactory;
    protected $guarded =[]; 
    public function subsubdevices(){
        return $this->hasMany(Subsubdevice::class,'subsubdeviceid');
    }
}
