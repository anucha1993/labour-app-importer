<?php

namespace App\Models\agency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyModel extends Model
{
    use HasFactory;
    protected $table = 'agency';
    protected $primaryKey = 'agency_id';
    protected $fillable = [
    "agency_name",
    "agency_tax",
    "agency_nationality",
    "agency_address",
    "agency_email",
    "agency_contact",
    "agency_phone",
    "agency_note",
    'created_by',
    'updated_by',
    ];
}
