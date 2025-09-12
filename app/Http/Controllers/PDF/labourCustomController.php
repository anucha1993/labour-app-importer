<?php

namespace App\Http\Controllers\PDF;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportCustom\LabourExportCustom;


class labourCustomController extends Controller
{
  protected $fpdf;
 
  public function __construct()
  {
      $this->fpdf = new Fpdf;
  }

  public function exportPDF(Request $request) 
  {
    // dd($request);
    $company = $request->company;
    $status  = $request->status;


  if($request->type == 'excel')
  {
    return Excel::download(new LabourExportCustom($company,$status), 'LabourExportCustom.xlsx');
  }
  if($request->type == 'pdf')
  {
   
    $this->fpdf->AddPage("L", 'A4');
    $this->fpdf->Image("../public/images/HeaderText.jpg", 10.5, 10, 100);
    $this->fpdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->fpdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->fpdf->AddFont('THSarabunNew','I','THSarabunNew_i.php');
    $this->fpdf->SetFont('THSarabunNew', 'B', 16);
    // $this->fpdf->Cell(0,0,iconv('UTF-8','TIS-620',"บริษัท ดิ อิมพอร์ตเตอร์ 168 จำกัด"),0,1,'L'); 

    // $this->fpdf->ln(7);
    // $this->fpdf->SetFont('THSarabunNew', '', 14);
    // $this->fpdf->Cell(0,0,iconv('UTF-8','TIS-620',"เลขที่ 62/231-232 ซอยประเสริฐมนูกิจ 27 ถนน ประเสริฐมนูกิจ"),0,1,'L'); 
    // $this->fpdf->ln(5);
    // $this->fpdf->Cell(0,0,iconv('UTF-8','TIS-620',"แขวงจรเข้บัว เขตลาดพร้าว กรุงเทพมหานคร 10230 โทร.081-171894"),0,1,'L'); 

    $this->fpdf->SetFont('THSarabunNew', 'B', 14);
    $this->fpdf->ln(25);
    $this->fpdf->Cell(0,0,iconv('UTF-8','TIS-620',"รายงานข้อมูลคนต่างด้าว"),0,1,'C'); 

    $this->fpdf->ln(10);
    $this->fpdf->SetFont('THSarabunNew', 'B', 10);
    $width_cell = array(8,30,10,15,10,60,20,15,30,22,17,17,22,22,22,22);
    $this->fpdf->SetFillColor(193,229,252);

    $this->fpdf->Cell($width_cell[0],6,iconv('UTF-8','TIS-620','ลำดับ'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[1],6,iconv('UTF-8','TIS-620','ชื่อ-สกุล'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[2],6,iconv('UTF-8','TIS-620','เพศ'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[3],6,iconv('UTF-8','TIS-620','วันเกิด'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[4],6,iconv('UTF-8','TIS-620','สัญชาติ'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[5],6,iconv('UTF-8','TIS-620','บริษัท'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[6],6,iconv('UTF-8','TIS-620','เลขที่หนังสือเดินทาง'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[7],6,iconv('UTF-8','TIS-620','เลขที่วิซ่า'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[8],6,iconv('UTF-8','TIS-620','เลขทีเลขที่ใบอนุญาตทำงาน'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[9],6,iconv('UTF-8','TIS-620','รายงานตัว90หมดอายุ'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[9],6,iconv('UTF-8','TIS-620','วีซ่าหมด'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[10],6,iconv('UTF-8','TIS-620','เลขที่ ตม.'),1,0,'C',true);
    $this->fpdf->Cell($width_cell[11],6,iconv('UTF-8','TIS-620','สถานะการทำงาน'),1,1,'C',true);
    
    $data = DB::table('labour')
    ->leftJoin('company','company.company_id','=','labour.company_id')
    ->leftJoin('nationality','nationality.code','=','labour.labour_nationality')
    ->when($request->company, function ($query) use ($request){
     if($request->company == 'ALL'){
         return '';
     }else{
         return $query->where('company.company_id',$request->company);
     }
    })
    ->when($request->status, function ($query) use ($request) {
     if($request->status == 'ALL'){
         return '';
     }else{
         return $query->where('labour.labour_status_job',$request->status);
     }
    })
    ->groupBy('labour.labour_id')
    ->get();
    
    $this->fpdf->SetFillColor(999,999,999);
    $this->fpdf->SetFont('THSarabunNew', '', 10);
    $DT_Index = 0;

    foreach ($data as $item)
    {
      $this->fpdf->Cell($width_cell[0],6,iconv('UTF-8','TIS-620', ++$DT_Index),1,0,'C',true);
          $this->fpdf->Cell($width_cell[1],6,iconv('UTF-8','TIS-620',$item->labour_prefix.'.'.$item->labour_fullname),1,0,'L',true);
          $this->fpdf->Cell($width_cell[2],6,iconv('UTF-8','TIS-620', $item->labour_sex =='male' ? 'ชาย':'หญิง'),1,0,'C',true);
          $this->fpdf->Cell($width_cell[3],6,iconv('UTF-8','TIS-620',date('d/m/Y',strtotime($item->labour_birthday))),1,0,'C',true);
          $this->fpdf->Cell($width_cell[4],6,iconv('UTF-8','TIS-620',$item->name_th),1,0,'C',true);
          $this->fpdf->Cell($width_cell[5],6,iconv('UTF-8','TIS-620',$item->company_name),1,0,'C',true);
          $this->fpdf->Cell($width_cell[6],6,iconv('UTF-8','TIS-620',$item->labour_passport_number),1,0,'C',true);
          $this->fpdf->Cell($width_cell[7],6,iconv('UTF-8','TIS-620',$item->labour_visa_number),1,0,'C',true);
          $this->fpdf->Cell($width_cell[8],6,iconv('UTF-8','TIS-620',$item->labour_workpremit_number),1,0,'C',true);
          $this->fpdf->Cell($width_cell[9],6,iconv('UTF-8','TIS-620',date('d/m/Y',strtotime($item->labour_day90_date_end))),1,0,'C',true);
          $this->fpdf->Cell($width_cell[9],6,iconv('UTF-8','TIS-620',date('d/m/Y',strtotime($item->labour_visa_date_end))),1,0,'C',true);
          $this->fpdf->Cell($width_cell[10],6,iconv('UTF-8','TIS-620',$item->labour_tm_number),1,0,'C',true);
          $this->fpdf->Cell($width_cell[11],6,iconv('UTF-8','TIS-620',($item->labour_status_job == "job" ? 'ทำงาน' : '').($item->labour_status_job == "resign" ? 'ลาออก' : '').($item->labour_status_job == "escape" ? 'หลบหนี' : '') ),1,1,'C',true);
    }

    
    $this->fpdf->Output();
    exit;
  }
}
  
}
