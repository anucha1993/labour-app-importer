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
use App\Models\labour\LabourPaymentType;

class LabourController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


        /**
     * Show QR code detail page for a worker
     */
    public function qrcodeDetail($identifier)
    {
        // ตรวจสอบว่า $identifier เป็น ID หรือเลขที่หนังสือเดินทาง
        $labour = LabourModel::with(['company', 'agency', 'paymentTypes.histories'])
            ->where(function($query) use ($identifier) {
                $query->where('labour_id', $identifier)
                      ->orWhere('labour_passport_number', $identifier);
            })
            ->firstOrFail();

        // คำนวณยอดค้างชำระแต่ละ payment type
        $pendingTypes = $labour->paymentTypes->where('status', '!=', 'completed');

        // ถ้าเป็น AJAX request ให้ส่งเฉพาะ HTML content
        if(request()->ajax()) {
            return view('labour.qrcode-detail', compact('labour', 'pendingTypes'))->render();
        }

        // ถ้าเป็น regular request ให้ส่งหน้าเต็ม
        return view('labour.qrcode-detail', compact('labour', 'pendingTypes'));
    }

    /**
     * Show specific payment type detail
     */
    public function paymentDetail($labourId, $paymentTypeId)
    {
        $labour = LabourModel::with(['company', 'agency'])
            ->findOrFail($labourId);

        $paymentType = $labour->paymentTypes()
            ->with('histories')
            ->findOrFail($paymentTypeId);

        if(request()->ajax()) {
            return view('labour.payment-detail', compact('labour', 'paymentType'))->render();
        }

        return view('labour.payment-detail', compact('labour', 'paymentType'));
    }

    /**
     * API endpoint for searching by passport number
     */
    public function findByPassport($passportNumber)
    {
        try {
            $labour = LabourModel::where('labour_passport_number', $passportNumber)
                ->with(['company', 'agency', 'paymentTypes.histories'])
                ->firstOrFail();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'url' => route('labour.qrcodeDetail', $labour->labour_id)
                ]);
            }
            
            return redirect()->route('labour.qrcodeDetail', $labour->labour_id);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลเลขที่หนังสือเดินทาง'
                ], 404);
            }
            return back()->with('error', 'ไม่พบข้อมูลเลขที่หนังสือเดินทาง');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
{
    $labour_status_job = $request->input('labour_status_job');
    $company_id        = (int) trim($request->input('company_id'));
    $keyword           = $request->input('keyword');
    $start_date        = $request->input('start_date');
    $end_date          = $request->input('end_date');
    $column_name_type  = $request->input('column_name_type');
    $perPage           = $request->input('per_page', 50);

    // ใหม่: รับพารามิเตอร์ประเภทหักชำระ และสถานะยอดค้าง
    $payment_type_id   = $request->input('payment_type_id'); // id ของประเภทหักชำระ
    $payment_status    = $request->input('payment_status');  // 'pending' หรือ 'completed'

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
            // ตัด space หน้าหลัง และแปลง space หลายตัวเป็น % wildcard สำหรับ LIKE
            $cleanKeyword = trim($keyword);
            // แทนที่ space หลายตัวด้วย % เพื่อให้ LIKE ค้นหาได้ยืดหยุ่น
            $searchPattern = preg_replace('/\s+/', '%', $cleanKeyword);
            
            return $query->where(function ($q) use ($searchPattern) {
                $q->whereHas('company', function ($q1) use ($searchPattern) {
                        $q1->where('company_name', 'LIKE', '%' . $searchPattern . '%');
                    })
                  ->orWhere('labour_passport_number', 'LIKE', '%' . $searchPattern . '%')
                  ->orWhere('labour_visa_number', 'LIKE', '%' . $searchPattern . '%')
                  ->orWhere('labour_fullname', 'LIKE', '%' . $searchPattern . '%')
                  ->orWhereHas('agency', function ($q2) use ($searchPattern) {
                        $q2->where('agency_name', 'LIKE', '%' . $searchPattern . '%');
                  });
            });
        })

        // ใหม่: ค้นหาตาม "ประเภทหักชำระ"
        ->when($payment_type_id, function ($query) use ($payment_type_id) {
            $query->whereHas('paymentTypes', function ($qq) use ($payment_type_id) {
                $qq->where('id', $payment_type_id);
            });
        })

        // ใหม่: ค้นหาตาม "สถานะยอดค้างชำระ"
        // สมมติว่า paymentTypes.status = 'completed' เมื่อจ่ายครบ, อื่นๆ แปลว่ายังค้าง
        ->when($payment_status === 'pending', function ($query) {
            $query->whereHas('paymentTypes', function ($qq) {
                $qq->where('status', '!=', 'completed');
            });
        })
        ->when($payment_status === 'completed', function ($query) {
            $query->whereDoesntHave('paymentTypes', function ($qq) {
                $qq->where('status', '!=', 'completed');
            });
        })

        ->with(['company','agency','paymentTypes']) // เผื่อใช้แสดงผล
        ->paginate($perPage);

    // ดึงรายการประเภทหักชำระมาใส่ใน select (แก้โมเดล/คอลัมน์ให้ตรงของจริง)
    $paymentTypeOptions = LabourPaymentType::orderBy('payment_name')
        ->get(['payment_type_id','payment_name']);

    $companys = CompanyModel::latest()->get();

    return view('labour.index', compact('companys','labours','request','paymentTypeOptions'));
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

        $labour = LabourModel::with(['paymentTypes.histories'])
            ->where('labour_id', $labourModel->labour_id)
            ->leftJoin('company', 'company.company_id', 'labour.company_id')
            ->leftJoin('agency', 'agency.agency_id', 'labour.labour_agency')
            ->leftJoin('nationality', 'nationality.code', 'labour.labour_nationality')
            ->groupBy('labour.labour_id')
            ->first();

        // Format payment data for view
        $paymentTypes = $labour->paymentTypes ?? collect([]);

        return view('labour.form-edit', compact('nationality', 'agency', 'company', 'labour', 'paymentTypes'));
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
