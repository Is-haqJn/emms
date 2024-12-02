<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipDocs extends Model
{
    use HasFactory;
    protected $fillable = ['equip_id','document_name','document_file','user_id'];
    protected $table = "equip_docs";
}
