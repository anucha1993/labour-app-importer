<?php

namespace App\Models\labour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourPaymentType extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_type_id';
    protected $fillable = [
        'labour_id',
        'payment_name',
        'total_amount',
        'deduction_type',
        'status',
        'note'
    ];

    public function histories()
    {
        return $this->hasMany(LabourPaymentHistory::class, 'payment_type_id');
    }

    public function labour()
    {
        return $this->belongsTo(LabourModel::class, 'labour_id');
    }

    public function calculatePaidAmount()
    {
        return $this->histories()->sum('amount');
    }

    public function updateStatus()
    {
        $paidAmount = $this->calculatePaidAmount();
        
        if ($paidAmount >= $this->total_amount) {
            $this->status = 'completed';
        } elseif ($paidAmount > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'pending';
        }
        
        $this->save();
    }
}
