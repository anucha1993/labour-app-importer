<?php

namespace App\Exports\labourexpire;

use Carbon\Carbon;
use App\Models\labour\LabourModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use App\Models\settings\settingsModel;

class LabourExpireDay90 implements FromCollection,WithHeadings,WithMapping,WithColumnFormatting
{


 /**
    * @return \Illuminate\Support\Collection
    */

    private $No = 0;
    public function collection()
    {
       $ninety = settingsModel::where('set_name','ninety')->first();
       $ExpireVisa = DB::table('labour')
       ->leftJoin('company','company.company_id','=','labour.company_id')
       ->where('labour.labour_day90_date_end','<=', Carbon::now()->subDays(-$ninety->set_expire)) 
       ->where('labour.labour_status_job','job')
       ->groupBy('labour.labour_id')
       ->get();
       return $ExpireVisa;

    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'รหัสพนักงาน',
            'คำนำหน้า',
            'ชื่อ-สกุล',
            'นายจ้าง',
            'เลขที่หนังสือเดินทาง',
            'วันที่รายงานตัว 90 วัน เริ่มต้น',
            'วันที่รายงานตัว 90 วัน สิ้นสุด',
            'จำนวนวันคงเหลือ',
            'สถานะการทำงาน',
            
        ];
    }
    
    public function  ColumnFormats(): array
    {
        return [
            'E' => "0",
        ];
    }

    public function map($ExpireVisa): array
    {

      return [
        ++$this->No,
        $ExpireVisa->labour_number,
        $ExpireVisa->labour_prefix.'.',
        $ExpireVisa->labour_fullname,
        $ExpireVisa->company_name,
        ''.$ExpireVisa->labour_passport_number,
        date("d/m/Y", strtotime($ExpireVisa->labour_day90_date_start)),
        date("d/m/Y", strtotime($ExpireVisa->labour_day90_date_end)),
        now()->diffInDays(Carbon::parse($ExpireVisa->labour_day90_date_end), false),
        'ทำงาน',
        $ExpireVisa->labour_note,
         
      ];
    }
}
