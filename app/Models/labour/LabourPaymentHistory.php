<?php

namespace App\Models\labour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourPaymentHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_history_id';
    protected $fillable = [
        'payment_type_id',
        'amount',
        'payment_date',
        'proof_file'
    ];

    protected $casts = [
        'payment_date' => 'datetime'
    ];

    public function paymentType()
    {
        return $this->belongsTo(LabourPaymentType::class, 'payment_type_id');
    }
}
