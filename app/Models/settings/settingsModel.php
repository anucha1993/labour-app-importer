<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settingsModel extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'settings';
    protected $primaryKey = 'set_id';
    protected $fillable = [
        'set_id',
        'set_name',
        'set_expire',
    ];
}
