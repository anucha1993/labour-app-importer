<?php

namespace App\Exports\ExportCustom;

use Carbon\Carbon;
use App\Models\labour\LabourModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;

class LabourExportCustom implements FromCollection,WithHeadings,WithMapping,WithColumnFormatting
{

   /**
    * @return \Illuminate\Support\Collection
    */

    private $No = 0;

    public function __construct($company,$status)
    {
        $this->company = $company;
        $this->status = $status;

        
        return $this;
    }


    public function collection()
    {
       $data = DB::table('labour')
       ->leftJoin('company','company.company_id','=','labour.company_id')
       ->when($this->company, function ($query){
        if($this->company == 'ALL'){
            return '';
        }else{
            return $query->where('company.company_id',$this->company);
        }
       })
       ->when($this->status, function ($query){
        if($this->status == 'ALL'){
            return '';
        }else{
            return $query->where('labour.labour_status_job',$this->status);
        }
       })
       ->groupBy('labour.labour_id')
       ->get();

       return $data;

    }

    public function headings(): array
    {
        return [
            'ลำดับ', 
            'รหัสพนักงาน', 
            'คำนำหน้า', 
            'ชื่อ-สกุล',
            'เพศ', 
            'สัญชาติ',
            'ชื่อเอเจซี่',
            'บริษัท',
            'วันเกิด',
            'เลขที่หนังสือเดินทาง',
            'วันที่ออกเล่ม',
            'วันที่หมดอายุ',
            'เลขที่วีซ่า',
            'วันที่เดินทางเข้ามา',
            'วันที่เริ่มวิซ่า',
            'วันที่หมดอายุวิซ่า',
            'เลขที่ใบอนุญาตทำงาน',
            'รหัสพนักงาน',
            'วันที่ใบอนุญาตเริ่มต้น',
            'วันที่ใบอนุญาตสิ้นสุด',
            'วันที่รายงานตัว 90 วัน เริ่มต้น',
            'วันที่รายงานตัว 90 วัน สิ้นสุด',
            'เลขที่ ตม.',
            'รหัสพนักงาน',
            'วันที่เริ่มเข้าทำงาน',
            'สถานะแรงงาน',
            'วันที่ลาออก',
            'หมายเหตุ',

            
        ];
    }
    
    public function  ColumnFormats(): array
    {
        return [
            'I' => "0",
            'L' => "0",
            'P' => "0",
            'Q' => "0",
            'V' => "0",
        ];
    }

    public function map($data): array
    {
      return [
        ++$this->No,
        $data->labour_number, //A
        $data->labour_prefix, //B
        $data->labour_fullname,//C
        $data->labour_sex, //D
        $data->labour_nationality, //E
        $data->labour_agency, //F
        $data->company_name, //G
        date('d-m-Y',strtotime($data->labour_birthday)), //H
        $data->labour_passport_number, //I
        date('d-m-Y',strtotime($data->labour_passport_date_start)),//J
        date('d-m-Y',strtotime($data->labour_passport_date_end)), //K
        $data->labour_visa_number,//L
        date('d-m-Y',strtotime($data->labour_visa_date_in)),//M
        date('d-m-Y',strtotime($data->labour_visa_date_start)),//N
        date('d-m-Y',strtotime($data->labour_visa_date_end)),//O
        $data->labour_workpremit_number,//P
        $data->labour_labour_number,//Q
        date('d-m-Y',strtotime($data->labour_workpremit_date_start)),//R
        date('d-m-Y',strtotime($data->labour_workpremit_date_end)),//S
        date('d-m-Y',strtotime($data->labour_day90_date_start)),//T
        date('d-m-Y',strtotime($data->labour_day90_date_end)),//U
        $data->labour_tm_number,//V
        $data->labour_number,//V
        date('d-m-Y',strtotime($data->labour_jobdate_start)),//W
        $data->labour_status_job,//X
        $data->labour_escape_date,//Y
        $data->labour_note,
      ];
    }
}
