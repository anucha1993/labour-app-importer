<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\New_;

use App\Exports\ExportCustom\LabourExportCustom;
use App\Http\Controllers\PDF\labourCustomController;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Support\Facades\DB;

class ReportLabourController extends Controller
{
    protected $fpdf;
    public function __construct()
  {
      $this->fpdf = new Fpdf;
  }

    public function index()
    {
        $company = DB::table('company')->get();
        return view('report.index',compact('company'));
    }

   

}
