<?php

namespace App\Models\labour;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\company\CompanyModel;
use App\Models\agency\AgencyModel;
use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;
use App\Models\labour\LabourPaymentType;
use App\Models\labour\LabourPaymentHistory;

class LabourModel extends Model
{
    use HasFactory;
    protected $table = 'labour';
    protected $primaryKey = 'labour_id';
    protected $fillable = [
        "labour_prefix",
        "labour_number",
        "labour_fullname",
        "labour_fullname_th",
        "labour_sex",
        "labour_nationality",
        "labour_agency",
        "labour_birthday",
        "company_id",
        "labour_passport_number",
        "labour_passport_date_start",
        "labour_passport_date_end",
        "labour_visa_number",
        "labour_visa_date_in",
        "labour_visa_date_start",
        "labour_visa_date_end",
        "labour_workpremit_number",
        "labour_labour_number",
        "labour_workpremit_date_start",
        "labour_workpremit_date_end",
        "labour_day90_date_start",
        "labour_day90_date_end",
        "labour_tm_number",
        "labour_status",
        "labour_jobdate_start",
        "labour_status_job",
        "labour_resing_date",
        "labour_escape_date",
        "labour_note",
        'labour_file_passport',
        'labour_file_visa',
        'labour_file_work',
        "created_by",
        "updated_by",
    ];


    public function company()
    {
        return $this->belongsTo(CompanyModel::class, 'company_id', 'company_id');
    }
    public function agency()
    {
        return $this->belongsTo(AgencyModel::class, 'labour_agency', 'agency_id');
    }

    public function paymentTypes()
    {
        return $this->hasMany(LabourPaymentType::class, 'labour_id', 'labour_id');
    }

    public function paymentHistories()
    {
        return $this->hasManyThrough(
            LabourPaymentHistory::class,
            LabourPaymentType::class,
            'labour_id',
            'payment_type_id',
            'labour_id',
            'payment_type_id'
        );
    }
    
    protected static function booted()
    {
        static::created(function (LabourModel $labour) {

            $newValues = $labour->getAttributes(); 
            ActionLog::create([
                'user_id'    => Auth::id() ?: null,
                'action_type'=> 'created',
                'model_type' => self::class,
                'model_id'   => $labour->labour_id,
                'old_values' => null,               
                'new_values' => json_encode($newValues), 
                'created_at' => now(),
            ]);
        });


        static::updated(function (LabourModel $labour) {
            $oldValues = $labour->getOriginal();
            $changedValues = $labour->getChanges(); 
            ActionLog::create([
                'user_id'    => Auth::id() ?: null,
                'action_type'=> 'updated',
                'model_type' => self::class,
                'model_id'   => $labour->labour_id,
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($changedValues),
                'created_at' => now(),
            ]);
        });
        static::deleted(function (LabourModel $labour) {
            $oldValues = $labour->getOriginal(); 
            ActionLog::create([
                'user_id'    => Auth::id() ?: null,
                'action_type'=> 'deleted',
                'model_type' => self::class,
                'model_id'   => $labour->labour_id,
                'old_values' => json_encode($oldValues),
                'new_values' => null,
                'created_at' => now(),
            ]);
        });
    }


}
