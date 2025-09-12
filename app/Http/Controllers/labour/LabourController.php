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

class LabourController extends Controller
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
    public function index(Request $request )
    {
        $labour_status_job = $request->input('labour_status_job');
        $company_id = $request->input('company_id');
        $company_id = trim($company_id);
        $company_id = (int) $company_id; // แปลงเป็น integer
        $keyword = $request->input('keyword');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $column_name_type = $request->input('column_name_type');
        $perPage = $request->input('per_page', 50);

        //dd($request);
       // dd($company_id, gettype($company_id));
     
       $labours = LabourModel::when($company_id, function ($query) use ($company_id) {
            return $query->where('company_id', $company_id);
        })
        ->when($labour_status_job, function ($query) use ($labour_status_job) {
            return $query->where('labour_status_job', $labour_status_job);
        })
       
        ->when($column_name_type === 'labour_day90_date_end' && $start_date && $end_date, function ($query) use ($start_date, $end_date) {
            return $query->whereBetween('labour_day90_date_end', [$start_date, $end_date]);
        })
        ->when($column_name_type === 'labour_visa_date_end' && $start_date && $end_date, function ($query) use ($start_date, $end_date) {
            return $query->whereBetween('labour_visa_date_end', [$start_date, $end_date]);
        })
        ->when($column_name_type === 'labour_workpremit_date_end' && $start_date && $end_date, function ($query) use ($start_date, $end_date) {
            return $query->whereBetween('labour_workpremit_date_end', [$start_date, $end_date]);
        })

      

        ->when($keyword, function ($query, $keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->whereHas('company', function ($q1) use ($keyword) {
                    $q1->where('company_name', 'LIKE', '%' . $keyword . '%');
                })
                    ->orWhere('labour_passport_number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('labour_visa_number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('labour_fullname', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('agency', function ($q2) use ($keyword) {
                        $q2->where('agency_name', 'LIKE', '%' . $keyword . '%');
                    });
            });
        })

       ->paginate($perPage);


        // DB::enableQueryLog();
        // $labours->get();
        // dd(DB::getQueryLog());

  

        //
        // if ($request->ajax()) {
            
        //     return datatables::of($labour)
        //         ->addIndexColumn()
        //         ->addcolumn('action', function ($row) {
        //             if (Auth::user()->type == 'MasterAdmin') {
        //                 $btn =
        //                     '<a href="' .
        //                     route('labour.show', $row->labour_id) .
        //                     '" class="btn btn-info btn-sm">ดูข้อมูล</a>
        //         <a href="' .
        //                     route('labour.edit', $row->labour_id) .
        //                     '" class="btn btn-success btn-sm text-white">แก้ไข</a>
        //         <a href="' .
        //                     route('labour.delete', $row->labour_id) .
        //                     '"  onclick="return confirm(`คุณต้องการลบข้อมูล ' .
        //                     $row->labour_fullname . 
        //                     ' ใช่ไหม ?`)" class="btn btn-danger btn-sm">ลบ</a>
        //         ';
        //                 return $btn;
        //             } elseif (Auth::user()->type == 'Admin') {
        //                 $btn =
        //                     '<a href="' .
        //                     route('labour.show', $row->labour_id) .
        //                     '" class="btn btn-info btn-sm">ดูข้อมูล</a>
        //         <a href="' .
        //                     route('labour.edit', $row->labour_id) .
        //                     '" class="btn btn-success btn-sm text-white">แก้ไข</a>
        //         ';
        //                 return $btn;
        //             } else {
        //                 $btn =
        //                     '<a href="' .
        //                     route('labour.show', $row->labour_id) .
        //                     '" class="btn btn-info btn-sm">ดูข้อมูล</a>
        //         ';
        //                 return $btn;
        //             }
        //         })

        //         ->addcolumn('checkbox', function ($row) {
        //            return '<input type="checkbox" name="labour_ids[]" value="'.$row->labour_id.'">';
        //         })
        //         ->addcolumn('status', function ($row) {
        //             if ($row->labour_status == 'enable') {
        //                 $status = '<label class="badge rounded-pill bg-success text-white">Enable</label>';
        //                 return $status;
        //             } else {
        //                 $status = '<label class="badge rounded-pill bg-danger text-white">Disable</label>';
        //                 return $status;
        //             }
        //         })
        //         ->rawColumns(['action', 'status','checkbox'])
        //         ->make(true);
        // }

        $companys = CompanyModel::latest()->get();

        return view('labour.index',compact('companys','labours','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $nationality = DB::table('nationality')->get();
        $agency = DB::table('agency')->get();
        $company = DB::table('company')->get();
        return view('labour.form-add', compact('nationality', 'agency', 'company'));
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
        $labourModel = LabourModel::create($request->all());

        //Upload File Passport
        // ตรวจสอบและอัปโหลดไฟล์ Passport
        if ($request->file('file_passport')) {
            // ตั้งชื่อไฟล์
            $filePassport = $labourModel->labour_passport_number . '_Passport.' . $request->file('file_passport')->getClientOriginalExtension();

            // เก็บไฟล์
            $filesPathPassport = $request->file('file_passport')->storeAs($labourModel->labour_passport_number, $filePassport, 'public');

            // อัปเดต path ของไฟล์ในฐานข้อมูล (ถ้ามีฟิลด์สำหรับเก็บ path ของไฟล์)
            $labourModel->update(['labour_file_passport' => $filePassport]);
        }
        // ตรวจสอบและอัปโหลดไฟล์ Visa
        if ($request->file('file_visa')) {
            // ตั้งชื่อไฟล์
            $fileVisa = $labourModel->labour_passport_number . '_Visa.' . $request->file('file_visa')->getClientOriginalExtension();

            // เก็บไฟล์
            $filesPathVisa = $request->file('file_visa')->storeAs($labourModel->labour_passport_number, $fileVisa, 'public');

            // อัปเดต path ของไฟล์ในฐานข้อมูล (ถ้ามีฟิลด์สำหรับเก็บ path ของไฟล์)
            $labourModel->update(['labour_file_visa' => $fileVisa]);
        }

        // ตรวจสอบและอัปโหลดไฟล์ work
        if ($request->file('file_work')) {
            // ตั้งชื่อไฟล์
            $fileWork = $labourModel->labour_passport_number . '_Work.' . $request->file('file_work')->getClientOriginalExtension();

            // เก็บไฟล์
            $filesPathVisa = $request->file('file_work')->storeAs($labourModel->labour_passport_number, $fileWork, 'public');

            // อัปเดต path ของไฟล์ในฐานข้อมูล (ถ้ามีฟิลด์สำหรับเก็บ path ของไฟล์)
            $labourModel->update(['labour_file_work' => $fileWork]);
        }

        return redirect()->route('labour.index')->with('success', 'Create Labour Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\labour\LabourModel  $labourModel
     * @return \Illuminate\Http\Response
     */
    public function show(LabourModel $labourModel)
    {
        //
        $nationality = DB::table('nationality')->get();
        $agency = DB::table('agency')->get();
        $company = DB::table('company')->get();

        $labour = LabourModel::where('labour_id', $labourModel->labour_id)->leftJoin('company', 'company.company_id', 'labour.company_id')->leftJoin('agency', 'agency.agency_id', 'labour.labour_agency')->leftJoin('nationality', 'nationality.code', 'labour.labour_nationality')->groupBy('labour.labour_id')->first();

        return view('labour.form-show', compact('nationality', 'agency', 'company', 'labour'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\labour\LabourModel  $labourModel
     * @return \Illuminate\Http\Response
     */
    public function edit(LabourModel $labourModel)
    {
        //
        $nationality = DB::table('nationality')->get();
        $agency = DB::table('agency')->get();
        $company = DB::table('company')->get();

        $labour = LabourModel::where('labour_id', $labourModel->labour_id)->leftJoin('company', 'company.company_id', 'labour.company_id')->leftJoin('agency', 'agency.agency_id', 'labour.labour_agency')->leftJoin('nationality', 'nationality.code', 'labour.labour_nationality')->groupBy('labour.labour_id')->first();

        return view('labour.form-edit', compact('nationality', 'agency', 'company', 'labour'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\labour\LabourModel  $labourModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LabourModel $labourModel)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา
        $request->validate([
            'file_passport' => 'nullable|mimes:pdf,doc,docx,txt|max:2048',
        ]);

        // อัปเดตข้อมูลในฐานข้อมูล
        $labourModel->update($request->all());

        // ตรวจสอบและอัปโหลดไฟล์ Passport
        if ($request->file('file_passport')) {
            // ตั้งชื่อไฟล์
            $filePassport = $labourModel->labour_passport_number . '_Passport.' . $request->file('file_passport')->getClientOriginalExtension();

            // เก็บไฟล์
            $filesPathPassport = $request->file('file_passport')->storeAs($labourModel->labour_passport_number, $filePassport, 'public');

            // อัปเดต path ของไฟล์ในฐานข้อมูล (ถ้ามีฟิลด์สำหรับเก็บ path ของไฟล์)
            $labourModel->update(['labour_file_passport' => $filePassport]);
        }
        // ตรวจสอบและอัปโหลดไฟล์ Visa
        if ($request->file('file_visa')) {
            // ตั้งชื่อไฟล์
            $fileVisa = $labourModel->labour_passport_number . '_Visa.' . $request->file('file_visa')->getClientOriginalExtension();

            // เก็บไฟล์
            $filesPathVisa = $request->file('file_visa')->storeAs($labourModel->labour_passport_number, $fileVisa, 'public');

            // อัปเดต path ของไฟล์ในฐานข้อมูล (ถ้ามีฟิลด์สำหรับเก็บ path ของไฟล์)
            $labourModel->update(['labour_file_visa' => $fileVisa]);
        }

        // ตรวจสอบและอัปโหลดไฟล์ work
        if ($request->file('file_work')) {
            // ตั้งชื่อไฟล์
            $fileWork = $labourModel->labour_passport_number . '_Work.' . $request->file('file_work')->getClientOriginalExtension();

            // เก็บไฟล์
            $filesPathVisa = $request->file('file_work')->storeAs($labourModel->labour_passport_number, $fileWork, 'public');

            // อัปเดต path ของไฟล์ในฐานข้อมูล (ถ้ามีฟิลด์สำหรับเก็บ path ของไฟล์)
            $labourModel->update(['labour_file_work' => $fileWork]);
        }

        return redirect()->route('labour.index')->with('success', 'Update Labour Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\labour\LabourModel  $labourModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(LabourModel $labourModel)
    {
        // folder
        if (Storage::exists('public/' . $labourModel->labour_passport_number)) {
            // ลบโฟลเดอร์และไฟล์ทั้งหมดในโฟลเดอร์
            Storage::deleteDirectory('public/' . $labourModel->labour_passport_number);
        }

        $labourModel->delete();

        return redirect()->route('labour.index')->with('success', 'Delete Labour Successfully');
    }

    //delete file passport

    public function deleteFilePassport(LabourModel $labourModel)
    {
        // ตรวจสอบว่าไฟล์มีอยู่ก่อนหรือไม่
        if (Storage::exists('public/' . $labourModel->labour_passport_number . '/' . $labourModel->labour_file_passport)) {
            // ลบไฟล์
            Storage::delete('public/' . $labourModel->labour_passport_number . '/' . $labourModel->labour_file_passport);
        }
        // อัปเดตฐานข้อมูล (ถ้าจำเป็น)
        $labourModel->labour_file_passport = null;
        $labourModel->save();

        return redirect()->back()->with('success', 'File deleted successfully');
    }

    //delete file Visa
    public function deleteFileVisa(LabourModel $labourModel)
    {
        // ตรวจสอบว่าไฟล์มีอยู่ก่อนหรือไม่
        if (Storage::exists('public/' . $labourModel->labour_passport_number . '/' . $labourModel->labour_file_visa)) {
            // ลบไฟล์
            Storage::delete('public/' . $labourModel->labour_passport_number . '/' . $labourModel->labour_file_visa);
        }
        // อัปเดตฐานข้อมูล (ถ้าจำเป็น)
        $labourModel->labour_file_visa = null;
        $labourModel->save();

        return redirect()->back()->with('success', 'File deleted successfully');
    }
    //delete file Work
    public function deleteFileWork(LabourModel $labourModel)
    {
        // ตรวจสอบว่าไฟล์มีอยู่ก่อนหรือไม่
        if (Storage::exists('public/' . $labourModel->labour_passport_number . '/' . $labourModel->labour_file_work)) {
            // ลบไฟล์
            Storage::delete('public/' . $labourModel->labour_passport_number . '/' . $labourModel->labour_file_work);
        }
        // อัปเดตฐานข้อมูล (ถ้าจำเป็น)
        $labourModel->labour_file_work = null;
        $labourModel->save();

        return redirect()->back()->with('success', 'File deleted successfully');
    }
}
