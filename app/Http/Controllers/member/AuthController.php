<?php

namespace App\Http\Controllers\member;

use App\Models\User;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
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
        return view('member.index');
    }

    public function member_ajax(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('users')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $status_enable = '';
                    $status_disable = '';
                    if($row->status === "enable")
                    {
                        $status_enable = "checked";
                    }
                    if($row->status === "disable")
                    {
                        $status_disable = "checked";
                    }
                    $actionBtn = ' <a href="javascript:void(0)" class="delete btn btn-success btn-sm text-white"  data-toggle="modal" data-target="#exampleModal'.$row->id .'">แก้ไข</a>
                                       <!-- Modal -->
                                       <div class="modal fade" id="exampleModal'.$row->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">ข้อมูลสามาชิก</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">
                                                  
                                              <form action="'.route('member.update',$row->id).'" method="POST">
                                              <div class="text-left">
                                              <input type="hidden" name ="_token" value="'.csrf_token().'" class="form-control" />
                                              <div class="form-group">
                                              <label >ชื่อ-สกุล</label>
                                              <input type="text" name ="name" value="'.$row->name.'" class="form-control" required />
                                              </div>
                                              <div class="form-group">
                                              <label>Email</label>
                                              <input type="email" name ="email" value="'.$row->email.'" class="form-control" required />
                                              </div>
                                              <div class="form-group">
                                              <label>รหัสผ่าน</label>
                                              <input id="password" type="password" name ="password" class="form-control" placeholder="********" required />
                                              </div>
                                              <div class="form-group">
                                              <label>ยืนยันรหัสผ่าน</label>
                                              <input id="password" type="password" name ="password_confirmation" class="form-control" placeholder="********" required />
                                              </div>
                                              <div class="form-group">
                                              <label>ระดับการใช้งาน</label>
                                              <select name="type" class="form-control" id="">
                                              <option value="'.$row->type.'">'.$row->type.'</option>
                                              <option></option>
                                              <option value="Member">Member</option>
                                              <option value="Admin">Admin</option>
                                              <opti value="MasterAdmin">MasterAdmin</opti on>
                                               </select>
                                               </div>
                                               <input type="radio" name="status" value="enable" '.$status_enable.'  required >
                                               <label for="">เปิดใช้งาน</label>
                                               <input type="radio" name="status" value="disable" '.$status_disable.' required>
                                               <label for="">ปิดใช้งาน</label>
                                               </div>
                                               <div class="modal-footer">
                                               <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                               <button type="submit" class="btn btn-primary">บันทึก</button>
                                             </div>
                                                </div>
                                              </div>
                                            </div>
                                            
                                            </form>
                                          </div>
                                       <!-- ลบข้อมูล -->
                                      <a href="'.route('member.delete',$row->id).'" onclick="return confirm(`คุณต้องการลบสมาชิก '.$row->name.' ใช่ไหม ?`)" class="delete btn btn-danger btn-sm ">Delete</a>
                                  ';
                    return $actionBtn;
                })
                ->addColumn('email_label', function ($row) {
                    $email_label = "<label class='badge rounded-pill bg-primary'>" . $row->email . "</label>";
                    return $email_label;
                })
                ->addColumn('btn_type', function ($row) {
                    if ($row->type === 'MasterAdmin') {
                        $btn_type = "<label class='badge rounded-pill bg-danger text-white'>" . $row->type . "</label>";
                    }
                    if ($row->type === 'Admin') {
                        $btn_type = "<label class='badge rounded-pill bg-success text-white'>" . $row->type . "</label>";
                    }
                    if ($row->type === 'Member') {
                        $btn_type = "<label class='badge rounded-pill bg-info text-white'>" . $row->type . "</label>";
                    }
                    return $btn_type;
                })
                ->addColumn('status_label', function ($row) {
                    if ($row->status === 'enable') {
                        $status_label = "<label class='badge rounded-pill bg-success'>" . $row->status . "</label>";
                        return $status_label;
                    }
                     if ($row->status === 'disable') {
                        $status_label = "<label class='badge rounded-pill bg-danger'>" . $row->status . "</label>";
                        return $status_label;
                    }
                })

                ->rawColumns(['action', 'email_label', 'btn_type', 'status_label', 'status_label'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    protected function update(Request $request, $id)
    {
              $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => 'required|confirmed|min:6',
            'type' => ['required', 'string', 'max:20'],
            'status' => ['required', 'string', 'max:20'],
         
        ]);
        // Update DB User
       User::where('id',$id)->update([
        'name' => $request->name,
        'email' => $request->email,
        'type' => $request->type,
        'status' => $request->status,
        'password' => Hash::make($request->password),
         ]);
         

        return redirect()->route('member.index')->with('success','Member Update successfully.');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function destroy($id)
    {
        //
       User::where('id',$id)->delete();

       return redirect()->route('member.index')->with('success','Member Delete successfully.');

    }
}
