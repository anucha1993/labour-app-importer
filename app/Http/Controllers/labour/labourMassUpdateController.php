<?php

namespace App\Http\Controllers\labour;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\labour\LabourModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\company\CompanyModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class labourMassUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function massupdate(Request $request)
    {
        $column_name = $request->input('column_name');
        $mass_date = $request->input('mass_date');

        if($column_name === 'labour_day90_date_end' && $mass_date){
            $query = LabourModel::whereIn('labour_id', $request->labour_ids)
            ->update([
                'labour_day90_date_end' => $mass_date
            ]);
        }
        if($column_name === 'labour_workpremit_date_end' && $mass_date){
            $query = LabourModel::whereIn('labour_id', $request->labour_ids)
            ->update([
                'labour_workpremit_date_end' => $mass_date
            ]);
        }
        if($column_name === 'labour_visa_date_end' && $mass_date){
            $query = LabourModel::whereIn('labour_id', $request->labour_ids)
            ->update([
                'labour_visa_date_end' => $mass_date
            ]);
        }
    
        // if ($request) {
        //     $query = LabourModel::whereIn('labour_id', $request->labour_ids)->getQuery();
    
        //     $query->when($column_name === 'labour_day90_date_end' && $mass_date, function ($query) use ($mass_date) {
        //         return $query->update([
        //             'labour_day90_date_end' => $mass_date,
        //         ]);
        //     })

        //     ->when($column_name === 'labour_workpremit_date_end' && $mass_date, function ($query) use ($mass_date) {
        //         return $query->update([
        //             'labour_workpremit_date_end' => $mass_date,
        //         ]);
        //     })
        //     ->when($column_name === 'labour_visa_date_end' && $mass_date, function ($query) use ($mass_date) {
        //         return $query->update([
        //             'labour_visa_date_end' => $mass_date,
        //         ]);
        //     });
        // }
    
        return redirect()->back()->with('success', 'Mass Upadte Successfully');
    }
}