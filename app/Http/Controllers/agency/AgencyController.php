<?php

namespace App\Http\Controllers\agency;

use Illuminate\Http\Request;
use App\Models\agency\AgencyModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = DB::table('agency')
            ->leftJoin('nationality','nationality.code','agency.agency_nationality')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addcolumn('action', function ($row){
                    if(Auth::user()->type == 'MasterAdmin')
                    {
                        $btn = '<a href="'.route('agency.edit',$row->agency_id).'" class="btn btn-success text-white btn-sm ">แก้ไข</a>
                        <a href="'.route('agency.delete',$row->agency_id).'" onclick="return confirm(`คุณต้องการลบสมาชิก  ใช่ไหม ?`)" class="delete btn btn-danger btn-sm "><i class="fa fa-trash"> ลบ</i></a>';
                         return $btn;
                    }else
                    {
                        $btn = '<a href="'.route('agency.edit',$row->agency_id).'" class="btn btn-success text-white btn-sm ">แก้ไข</a>';
                         return $btn;
                    }
                  
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('agency.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('agency.form-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $agency = array(
            "agency_name"=>$request->agency_name,
            "agency_tax"=> $request->agency_tax,
            "agency_nationality" => $request->agency_nationality,
            "agency_address" => $request->agency_address,
            "agency_email" =>$request->agency_email,
            "agency_contact" =>$request->agency_contact,
            "agency_phone" =>$request->agency_phone,
            "agency_note" =>$request->agency_note,
            "created_by"=>Auth::user()->name,
        );
        AgencyModel::create($agency);
        return redirect()->route('agency.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\agency\AgencyModel  $agencyModel
     * @return \Illuminate\Http\Response
     */
    public function show(AgencyModel $agencyModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\agency\AgencyModel  $agencyModel
     * @return \Illuminate\Http\Response
     */
    public function edit(AgencyModel $agencyModel)
    {
        //

        $agency = DB::table('agency')
        ->where('agency_id',$agencyModel->agency_id)
        ->leftJoin('nationality','nationality.code','agency.agency_nationality')
        ->first();

        return view('agency.form-edit',compact('agency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\agency\AgencyModel  $agencyModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgencyModel $agencyModel)
    {
        //
        $agencyModel->update($request->all());
        AgencyModel::where('agency_id',$agencyModel->agency_id)->update(['updated_by'=>Auth::user()->name ]);

        return redirect()->route('agency.index')->with('success','Agency Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\agency\AgencyModel  $agencyModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgencyModel $agencyModel)
    {
        //
        AgencyModel::where('agency_id',$agencyModel->agency_id)->delete();
        return redirect()->route('agency.index')->with('success','Agency Delete successfully.');
    }
}
