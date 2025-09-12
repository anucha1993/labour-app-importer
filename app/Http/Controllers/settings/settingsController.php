<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\settings\settingsModel;
class settingsController extends Controller
{
    //
    public function index()
    {
        $settings = settingsModel::all();
        return view('settings.expire.index',compact('settings'));
    }

    public function update(Request $request)
    {
        //dd($request);
        foreach ($request->set_id as $key => $v) {
            settingsModel::where('set_id', $request->set_id[$key])->update([
                'set_expire' => $request->set_expire[$key]
            ]);
        }
        
        return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จ!!');
        
    }
}
