<?php

namespace App\Imports\labour;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\labour\LabourModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ImportLabour implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function __construct($company,$agency,$nationality)
    {
        $this->company  = $company;
        $this->agency = $agency;
        $this->nationality = $nationality;
        return $this;

        //dd(__construct);
    }

     
    // public function rules():array{
    //     return[
    //         'required'=>'labour_prefix',
    //     ];
    // }
public function collection(Collection $collection)
{
    //dd($collection);
}
    public function model(array $row)
    {
    //   dd($row);
     
     //return NULL;
      
      

        if(!empty($row['labour_passport_number']) && $row['labour_passport_number'] !== NULL)
        {
            // echo "<pre>",var_dump($row),"</pre>";

             return new LabourModel([
            //"labour_number"                =>$row['labour_number'],
            "labour_prefix"                =>$row['labour_prefix'],
            "labour_fullname"              =>$row['labour_fullname'],
            "labour_sex"                   =>$row['labour_sex'],
            "labour_nationality"           =>$this->nationality,
            "labour_agency"                =>$this->agency,
            "labour_birthday"              =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_birthday']),
            "company_id"                   =>$this->company,
            "labour_passport_number"       =>$row['labour_passport_number'],
            "labour_passport_date_start"   =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_passport_date_start']),
            "labour_passport_date_end"     =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_passport_date_end']),
            "labour_visa_number"           =>$row['labour_visa_number'],
            "labour_visa_date_in"          =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_visa_date_in']),
            "labour_visa_date_start"       =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_visa_date_start']),
            "labour_visa_date_end"         =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_visa_date_end']),
            "labour_workpremit_number"     =>$row['labour_workpremit_number'],
            "labour_labour_number"         =>$row['labour_labour_number'],
            "labour_workpremit_date_start" =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_workpremit_date_start']),
            "labour_workpremit_date_end"   =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_workpremit_date_end']),
            "labour_day90_date_start"      =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_day90_date_start']),
            "labour_day90_date_end"        =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_day90_date_end']),
            "labour_tm_number"             =>$row['labour_tm_number'],
            "labour_status"                =>$row['labour_status'],
            "labour_jobdate_start"         =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['labour_jobdate_start']),
            "labour_status_job"            =>$row['labour_status_job'],
            "labour_note"                  =>$row['labour_note'],
            "created_by"                   =>Auth::user()->name,
        ]);
        }

        // if (!empty($row['labour_passport_number'])) {
        //     $labourData = [
        //         "labour_prefix" => $row['labour_prefix'],
        //         "labour_fullname" => $row['labour_fullname'],
        //         "labour_sex" => $row['labour_sex'],
        //         "labour_nationality" => $this->nationality,
        //         "labour_agency" => $this->agency,
        //         "company_id" => $this->company,
        //         "labour_passport_number" => $row['labour_passport_number'],
        //         "labour_visa_number" => $row['labour_visa_number'],
        //         "labour_workpremit_number" => $row['labour_workpremit_number'],
        //         "labour_labour_number" => $row['labour_labour_number'],
        //         "labour_tm_number" => $row['labour_tm_number'],
        //         "labour_status" => $row['labour_status'],
        //         "labour_status_job" => $row['labour_status_job'],
        //         "labour_note" => $row['labour_note'],
        //         "created_by" => Auth::user()->name,
        //     ];
        
        //     // ฟังก์ชันสำหรับแปลงค่าวันที่/เวลา
        //     $processDate = function ($value, $columnName) use ($row) { // เพิ่ม $columnName และ use $row
        //         if (is_numeric($value)) {
        //             try {
        //                 return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        //             } catch (\Exception $e) {
        //                 dd("Error processing date in column: " . $columnName, $row, $e->getMessage()); // แสดงข้อมูลเมื่อเกิดข้อผิดพลาด
        //                 return null; // หรือค่าเริ่มต้นอื่น ๆ
        //             }
        //         }
        //         return null;
        //     };
        
        //     // แปลงค่าวันที่/เวลา
        //     $labourData["labour_birthday"] = $processDate($row['labour_birthday'], 'labour_birthday');
        //     $labourData["labour_passport_date_start"] = $processDate($row['labour_passport_date_start'], 'labour_passport_date_start');
        //     $labourData["labour_passport_date_end"] = $processDate($row['labour_passport_date_end'], 'labour_passport_date_end');
        //     $labourData["labour_visa_date_in"] = $processDate($row['labour_visa_date_in'], 'labour_visa_date_in');
        //     $labourData["labour_visa_date_start"] = $processDate($row['labour_visa_date_start'], 'labour_visa_date_start');
        //     $labourData["labour_visa_date_end"] = $processDate($row['labour_visa_date_end'], 'labour_visa_date_end');
        //     $labourData["labour_workpremit_date_start"] = $processDate($row['labour_workpremit_date_start'], 'labour_workpremit_date_start');
        //     $labourData["labour_workpremit_date_end"] = $processDate($row['labour_workpremit_date_end'], 'labour_workpremit_date_end');
        //     $labourData["labour_day90_date_start"] = $processDate($row['labour_day90_date_start'], 'labour_day90_date_start');
        //     $labourData["labour_day90_date_end"] = $processDate($row['labour_day90_date_end'], 'labour_day90_date_end');
        //     $labourData["labour_jobdate_start"] = $processDate($row['labour_jobdate_start'], 'labour_jobdate_start');
        
        //     return new LabourModel($labourData);
        // }

       // dump($labourData);
       
        
    }
 

}
