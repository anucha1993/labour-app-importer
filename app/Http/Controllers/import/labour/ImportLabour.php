<?php

namespace App\Http\Controllers\import\labour;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\labour\ImportLabour as LabourImportLabour;
use Illuminate\Support\Facades\Auth;


class ImportLabour extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //

    public function importform()
{
    $nationality = DB::table('nationality')->get();
    $agency = DB::table('agency')->get();
    $company = DB::table('company')->get();

    return view('labour.import-form',compact('nationality','agency','company'));
}

public function import(Request $request)
{

    //dd($request);

    // dd($request);
    $company = $request->company;
    $agency  = $request->agency;
    $nationality = $request->nationality;

    $excel =  Excel::import(new LabourImportLabour($company,$agency,$nationality), request()->file('file'));
   
    return redirect()->route('labour.index')->with('success', 'Insert file Excel Labour Successfully');

}




}
