<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrGenerate extends Model
{
    use HasFactory;
    protected $table = 'qr';
    public function equipment(){
        return $this->hasOne('App\Equipment','id','assign_to');
    }
}
