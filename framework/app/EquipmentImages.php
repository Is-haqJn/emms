<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentImages extends Model
{
    use HasFactory;
    protected $fillable = ['equip_id','thumbnail_image','multiple_images'];
    protected $table = "equip_img";
}
