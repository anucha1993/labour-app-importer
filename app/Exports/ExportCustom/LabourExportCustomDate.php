<?php

namespace App\Exports\ExportCustom;

use Carbon\Carbon;
use App\Models\labour\LabourModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;

class LabourExportCustomDate implements FromCollection,WithHeadings,WithMapping,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $No = 0;
      public function __construct($company,$status,$type_date,$date_start,$date_end)
    {
        $this->company = $company;
        $this->status = $status;
        $this->type_date = $type_date;
        $this->date_start = $date_start;
        $this->date_end = $date_end;

        

        
        return $this;
    }
    

    public function collection()
    {
        // $start =date('Y-m-d 00:00:00', strtotime($request->date_start));
        // $end= date('Y-m-d 23:59:59', strtotime($request->date_end));
        // ->whereBetween('dataimportgroup.created_at', [ $start,$end] )

       $labours = DB::table('labour')
       ->leftJoin('company','company.company_id','=','labour.company_id')
       ->leftJoin('agency','agency.agency_id','=','labour.labour_agency')
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

            return $query->where('labour.labour_status_job','=',$this->status);
        }
       })
       ->when($this->type_date, function ($query){
        if($this->type_date == 'ALL'){
            return '';
        }else{
         $start =date('Y-m-d 00:00:00', strtotime($this->date_start));
         $end= date('Y-m-d 23:59:59', strtotime($this->date_end));
        //  dd($this->date_end);
         return $query ->whereBetween($this->type_date, [ $start,$end] );
        }
       })
      

       ->groupBy('labour.labour_id')
       ->get();
       return $labours;

    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'รหัสพนักงาน',
            'ชื่อ-สกุล','เพศ','สัญชาติ','ชื่อเอเจซี่','วันเกิด','บริษัท',
            'เลขที่หนังสือเดินทาง','วันที่ออกหนังสือเดินทาง','วันที่หนังสือเดินทางหมดอายุ','เลขที่วีซ่า', 'วันที่เดินทางเข้ามา','วันที่เริ่มวิซ่า',
            'วันที่หมดอายุวีซ่า','เลขที่ใบอนุญาตทำงาน','รหัสพนักงาน (ถ้ามี)','วันที่ใบอนุญาตเริ่มต้น',
            'วันที่ใบอนุญาตสิ้นสุด','วันที่รายงานตัว 90 วัน เริ่มต้น','วันที่รายงานตัว 90 วัน สิ้นสุด',
            'เลขที่-ตม','วันที่เริ่มเข้าทำงาน','สถานะแรงงาน','วันที่หลบหนี','สถานะในระบบ','หมายเหตุ',
             
            
        ];
    }
    
    public function  ColumnFormats(): array
    {
        return [
            'E' => "0",
        ];
    }

    public function map($labours): array
    {

    return[
        ++$this->No,//ลำดับ
        $labours->labour_number,
        $labours->labour_fullname, //ชื่อ-สกุล
        $labours->labour_prefix.'.',//เพศ
        $labours->labour_nationality,//สัญชาติ
        $labours->agency_name,  //ชื่อเอเจซี่
        date("d/m/Y", strtotime($labours->labour_birthday)),  //วันเกิด
        $labours->company_name,  //บริษัท
        $labours->labour_passport_number,  //เลขที่หนังสือเดินทาง
        date("d/m/Y", strtotime($labours->labour_passport_date_start)),//วันที่ออกเล่ม
        date("d/m/Y", strtotime($labours->labour_passport_date_end)), //วันที่หมดอายุ
        $labours->labour_visa_number,  //เลขที่วีซ่า
        date("d/m/Y", strtotime($labours->labour_visa_date_in)), //วันที่เดินทางเข้ามา
        date("d/m/Y", strtotime($labours->labour_visa_date_start)),  //วันที่เริ่มวิซ่า
        date("d/m/Y", strtotime($labours->labour_visa_date_end)), //วันที่หมดอายุ
        $labours->labour_workpremit_number,//เลขที่ใบอนุญาตทำงาน
        $labours->labour_labour_number, //รหัสพนักงาน (ถ้ามี)
        date("d/m/Y", strtotime($labours->labour_workpremit_date_start)),  //วันที่ใบอนุญาตเริ่มต้น
        date("d/m/Y", strtotime($labours->labour_workpremit_date_end)),  //วันที่ใบอนุญาตสิ้นสุด
        date("d/m/Y", strtotime($labours->labour_day90_date_start)), //วันที่รายงานตัว 90 วัน เริ่มต้น
        date("d/m/Y", strtotime($labours->labour_day90_date_end)),  //วันที่รายงานตัว 90 วัน สิ้นสุด
        $labours->labour_tm_number,//เลขที่-ตม
        date("d/m/Y", strtotime($labours->labour_jobdate_start)),  //วันที่เริ่มเข้าทำงาน
        $labours->labour_status_job,  //สถานะแรงงาน
        date("d/m/Y", strtotime($labours->labour_resing_date)), //วันที่หลบหนี
        $labours->labour_status,  //สถานะในระบบ
        $labours->labour_note, //หมายเหตุ

             
    ];
    }
}
