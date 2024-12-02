<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportBackup extends Model
{
    use HasFactory;
    protected $table = 'export_backup';
    protected $fillable = ['type','title','subtitle','columns'];
}
