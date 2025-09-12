<?php

namespace App\Http\Controllers\notification;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\settings\settingsModel;


class NotificationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ninety = settingsModel::where('set_name','ninety')->first();
        $visa = settingsModel::where('set_name','visa')->first();
        $passport = settingsModel::where('set_name','passport')->first();
        $work = settingsModel::where('set_name','work')->first();
  

        $notify_visa = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_visa_date_end','<=', Carbon::now()->subDays(-$visa->set_expire)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->paginate(10);

        $notify_passport = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_passport_date_end','<=', Carbon::now()->subDays(-$passport->set_expire)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->paginate(10);

        $notify_day90 = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_day90_date_end','<=', Carbon::now()->subDays(-$ninety->set_expire)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->paginate(10);

        $notify_work = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_workpremit_date_end','<=', Carbon::now()->subDays(-$work->set_expire)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->paginate(10);



        return view('notification.index',compact('notify_passport','notify_visa','notify_day90','notify_work'))
        ->with('i',(request()->input('page',1)-1)*10);
    }

    public function notify()
    {
        $notify_visa = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_visa_date_end','<=', Carbon::now()->subDays(-15)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->get();

        $notify_passport = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_passport_date_end','<=', Carbon::now()->subDays(-60)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->get();

        $notify_day90 = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_day90_date_end','<=', Carbon::now()->subDays(-15)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->get();

        $notify_work = DB::table('labour')
        ->leftJoin('company','company.company_id','=','labour.company_id')
        ->where('labour.labour_workpremit_date_end','<=', Carbon::now()->subDays(-15)) 
        ->where('labour.labour_status_job','job')
        ->groupBy('labour.labour_id')
        ->get();


       
        return  $notify_visa->count()+$notify_passport->count()+$notify_day90->count()+$notify_work->count();
    }

}

