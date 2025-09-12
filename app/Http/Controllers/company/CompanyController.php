<?php

namespace App\Http\Controllers\company;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\company\CompanyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\User;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
       
        if ($request->ajax()) {
            $data = DB::table('company')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addcolumn('action', function ($row){
                    if(Auth::user()->type == 'MasterAdmin')
                    {
                        $btn = '<a href="'.route('company.edit',$row->company_id).'" class="btn btn-success text-white btn-sm ">แก้ไข</a>
                        <a href="'.route('company.delete',$row->company_id).'" onclick="return confirm(`คุณต้องการลบสมาชิก '.$row->company_name.' ใช่ไหม ?`)" class="delete btn btn-danger btn-sm "><i class="fa fa-trash"> ลบ</i></a>';
                         return $btn;
                    }else
                    {
                        $btn = '<a href="'.route('company.edit',$row->company_id).'" class="btn btn-success text-white btn-sm ">แก้ไข</a>';
                         return $btn;
                    }
                  
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('company.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $provinces = DB::table('provinces')->get();
        return view('company.form-add',compact('provinces'));
    }

    public function province(Request $request)
    {
        $amphur = DB::table('amphures')->where('province_id',$request->province)->get();
        $output = '<option>Select a Amphur</option>';
        
        foreach ($amphur as $item)
        {
            $output.= '<option value="'.$item->id.'">'.$item->name_th.'</option>';
        }
        echo $output;
    }

    public function amphur(Request $request)
    {
        $district = DB::table('districts')->where('amphure_id',$request->amphur)->get();
        $output = '<option>Select a District</option>';
        
        foreach ($district as $item)
        {
            $output.= '<option value="'.$item->id.'">'.$item->name_th.'</option>';
        }
        echo $output;
    }

    public function district(Request $request)
    {
        $zipcode = DB::table('zipcodes')->where('district_code',$request->district)->first();
        // $output = '<option>Select a District</option>';
        
         $output = $zipcode->zipcode;

        echo $output;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $company_insert = array(
            "company_name"=>$request->company_name,
            "company_tax"=>$request->company_tax,
            "company_address"=>$request->company_address,
            "company_province"=>$request->company_province,
            "company_amphur"=>$request->company_amphur,
            "company_district"=>$request->company_district,
            "company_zipcode"=>$request->company_zipcode,
            "company_contact"=>$request->company_contact,
            "company_phone"=>$request->company_phone,
            "company_email"=>$request->company_email ,
            "company_fax"=>$request->company_fax ,
            "company_contact1"=>$request->company_contact1,
            "company_contact2"=>$request->company_contact2,
            "company_contact3"=>$request->company_contact3,
            "company_contact4"=>$request->company_contact4,
            "company_contact5"=>$request->company_contact5,
            "company_type"=>$request->company_type,
            "company_contact_sale"=>$request->company_cotact_sale,
            "company_note"=>$request->company_note,
            "company_status"=>$request->company_status,
            "created_by"=>Auth::user()->name,
        );

        CompanyModel::create($company_insert);
        return view('company.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyModel  $companyModel
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyModel $companyModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyModel  $companyModel
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyModel $companyModel)
    {
        //
        $provinces = DB::table('provinces')->get();
        
        $province = DB::table('provinces')->where('id', $companyModel->company_province)->first();
        $amphur = DB::table('amphures')->where('id', $companyModel->company_amphur)->first();
        $district = DB::table('districts')->where('id', $companyModel->company_district)->first();
 
        return  view('company.form-edit',compact('companyModel','provinces','province','amphur','district'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyModel  $companyModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyModel $companyModel)
    {
        //
        // dd($request);
        $companyModel->update($request->all());
        CompanyModel::where('company_id',$companyModel->company_id)->update(['updated_by'=>Auth::user()->name]);



            


        return redirect()->route('company.index')->with('success','Company Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyModel  $companyModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyModel $companyModel)
    {
        CompanyModel::where('company_id',$companyModel->company_id)->delete();
        return redirect()->route('company.index')->with('success','Company Delete successfully.');
    }
}
