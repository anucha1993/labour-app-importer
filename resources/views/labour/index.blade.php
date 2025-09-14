@extends('layouts.main_layout')

@section('content')
    <h3>ข้อมูลคนงานต่างด้าว</h3>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <a href="{{ route('labour.create') }}" class="btn btn-primary">เพิ่มข้อมูลคนต่างด้าว</a>
    <a href="{{ route('labour.importform') }}" class="btn btn-success text-white">เพิ่มข้อมูลจาก Excel</a>
    <a href="{{ URL::asset('../public/file/from-import.xlsx') }}" class="btn btn-info "
        download="">ดาวน์โหลดฟอร์มกรอกข้อมูล</a>
    <br>
    <br>
    <br>
    <h3>Search Data labours</h3>

    {{-- <form method="GET"  class="mb-3" id="page">
        <label for="per_page">แสดงจำนวน:</label>
        <select name="per_page" id="per_page" class="form-select" style="width: auto;" onchange="this.form.submit()">
            <option value="50" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
        </select>
    </form> --}}



    <form action="" method="get" id="form-search">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    

                    <div class="col-md-4">
                        <label for="">บริษัท :</label>
                        <select name="company_id" class="form-control form-select select2">
                            <option value="">ทั้งหมด</option>
                            @forelse ($companys as $item)
                            <option @if($request->company_id == $item->company_id ) selected @endif value="{{ $item->company_id }}">{{ $item->company_name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="">เงือนไข :</label>
                        <select name="column_name_type" class="form-select form-control">
                            <option value="">ไม่เลือก</option>
                            <option @if($request->column_name_type === 'labour_day90_date_end' ) selected @endif  value="labour_day90_date_end">วันที่หมดอายุรายงานตัว 90 วัน</option>
                            <option @if($request->column_name_type === 'labour_visa_date_end') selected @endif  value="labour_visa_date_end">วันที่หมดอายุวีซ่า</option>
                            <option @if($request->column_name_type ===  'labour_workpremit_date_end' ) selected @endif  value="labour_workpremit_date_end">วันที่หมดอายุใบอนุญาตทำงาน</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="">วันที่เริ่มต้น:</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $request->start_date }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="">วันที่สิ้นสุด:</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $request->end_date }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="">Action</label>
                        <br>
                        <button type="submit" class="btn btn-primary" form="form-search"> ค้นหาข้อมูล</button>
                        <a href="{{ route('labour.index') }}" class="btn btn-danger" > ล้างการค้นหา</a>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="">สถานะคนงาน</label>
                        <select name="labour_status_job" id="labour_status" onchange="selectstatus(this)"
                            class="form-control form-select">
                            <option value="">ทั้งหมด</option>
                            <option   @if($request->labour_status_job === 'job' ) selected @endif value="job">ทำงาน</option>
                            <option   @if($request->labour_status_job === 'resign' ) selected @endif value="resign">ลาออก</option>
                            <option   @if($request->labour_status_job === 'escape' ) selected @endif value="escape">หลบหนี</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="">สถานะการชำระเงิน</label>
                        <select name="payment_status" class="form-select form-control" onchange="this.form.submit()">
                            <option value="">ทั้งหมด</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>มียอดค้างชำระ</option>
                            <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>ชำระครบ</option>
                        </select>
                    </div>            
                    
                    <div class="col-md-2 mb-2">
    <label for="">ประเภทหักชำระ</label>
    <select name="payment_type_id" class="form-select form-control" onchange="this.form.submit()">
        <option value="">ทั้งหมด</option>
        @foreach($paymentTypeOptions as $pt)
            <option value="{{ $pt->id }}"
                {{ request('payment_type_id') == $pt->id ? 'selected' : '' }}>
                {{ $pt->payment_name }}
            </option>
        @endforeach
    </select>
</div>

                    
                    <div class="col-md-2 mb-2 float-end">
                        <label for="">Search :</label>
                         <input type="text" class="form-control" name="keyword" placeholder="ค้นหาข้อมูล" value="{{ $request->keyword }}">
                    </div>

                    <div class="col-md-12 mb-2 float-end">
                        <div class="float-end">
                        <label for="per_page">แสดงจำนวน:</label>
                        <select name="per_page" id="per_page" class="form-select" style="width: auto;" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="50" {{ request('per_page')  == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
                        </select>
                      </div>
                      </div>

                      
                </div>
            </div>
        </div>
    </form>
    <hr>



    <input type="checkbox" id="show-massupdate"> <label for="">Mass Update</label>

    <div id="div-massupdate" style="display: none">
        {{-- <h3>Mass Update</h3> --}}

        <form action="{{ route('labour.massupdate') }}" method="post" id="mass-update">
            @csrf
            @method('post')
            <div class="row">
                <div class="col-md-3 mb-2">

                    <label for="">เงือนไข :</label>
                    <select name="column_name" class="form-select form-control" required>
                        <option value="">ไม่เลือก</option>
                        <option value="labour_day90_date_end">วันที่หมดอายุรายงานตัว 90 วัน</option>
                        <option value="labour_visa_date_end">วันที่หมดอายุวีซ่า</option>
                        <option value="labour_workpremit_date_end">วันที่หมดอายุใบอนุญาตทำงาน</option>
                    </select>

                </div>

                <div class="col-md-3 mb-2">
                    <label for="">วันที่ Update</label>
                    <input type="date" name="mass_date" class="form-control" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="">Action</label>
                    <br>
                    <button type="submit" class="btn btn-primary" form="mass-update">Update</button>
                </div>
            </div>
    </div>

    <br>

   
    <br>




    <table class="table labour table-striped table-bordered" id="labour">
        <thead>
            <tr>
                <th>เลือก</th>
                <th>ลำดับ</th>
                <th>ชื่อ-สกุล</th>
                <th>เลขที่หนังสือเดินทาง</th>
                <th>เลขที่วีซ่า</th>
                <th>บริษัท</th>
              
                 @if ($request->column_name_type === 'labour_day90_date_end')<th>วันที่หมด 90 วัน</th> @endif
                 @if ($request->column_name_type === 'labour_visa_date_end')<th>วันที่หมดอายุวีซ่า</th> @endif
                 @if ($request->column_name_type === 'labour_workpremit_date_end')<th>วันที่หมดอายุ Work</th> @endif
       
                <th>เอเจนซี่</th>
                <th>สถานะแรงงาน</th>
                <th>Qr Code</th>
                <th>ยอดค้างชำระ</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($labours as $key => $item)
                <tr>
                    <td><input type="checkbox" name="labour_ids[]"  value="{{ $item->labour_id }}"></td>
                    <td>{{++$key}}</td>
                    <td>{{$item->labour_prefix}}.{{$item->labour_fullname}}</td>
                    <td>{{$item->labour_passport_number}}</td>
                    <td>{{$item->labour_visa_number}}</td>

                    <td>{{$item->company->company_name}}</td>

                    @if ($request->column_name_type === 'labour_day90_date_end') <td>{{date('d/m/Y',strtotime($item->labour_day90_date_end))}}</td> @endif
                    @if ($request->column_name_type === 'labour_visa_date_end') <td>{{date('d/m/Y',strtotime($item->labour_visa_date_end))}}</td> @endif
                    @if ($request->column_name_type === 'labour_workpremit_date_end') <td>{{date('d/m/Y',strtotime($item->labour_workpremit_date_end))}}</td> @endif

                    <td>{{ isset($item->agency) ? $item->agency->agency_name : 'ไม่มีข้อมูล' }}</td>
                    <td>
                        @if ($item->labour_status == 'enable')
                        <label class="badge rounded-pill bg-success text-white">Enable</label>
                        @else
                        <label class="badge rounded-pill bg-danger text-white">Disable</label>
                        @endif
                       
                    </td>
                        <td>
                            {{-- QR Code ปุ่ม --}}
                            <a href="{{ route('labour.qrcodeDetail', $item->labour_id) }}" target="_blank">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode(route('labour.qrcodeDetail', $item->labour_id)) }}" alt="QR" width="60" height="60" />
                            </a>
                        </td>
                        <td>
                            @php
                                $pendingTypes = $item->paymentTypes->where('status', '!=', 'completed');
                            @endphp
                            @if($pendingTypes->count() > 0)
                                <button type="button" 
                                        class="btn btn-warning btn-sm" 
                                        data-bs-toggle="popover"
                                        data-bs-html="true"
                                        title="รายการค้างชำระ"
                                        data-bs-content="@foreach($pendingTypes as $type)
                                            <div>
                                                {{ $type->payment_name }}: 
                                                {{ number_format($type->total_amount - $type->calculatePaidAmount(), 2) }} บาท
                                                ({{ $type->status }})
                                            </div>
                                        @endforeach">
                                    {{ $pendingTypes->count() }} รายการ
                                </button>
                            @else
                                <span class="badge bg-success">ไม่มีค้างชำระ</span>
                            @endif
                        </td>
                        <td>
                            @php
                    if (Auth::user()->type == 'MasterAdmin') {
                        $btn = '<a href="' . route('labour.show', $item->labour_id) . '" class="btn btn-info btn-sm">ดูข้อมูล</a> &nbsp;'; 
                        $btn .= '<a href="' . route('labour.edit', $item->labour_id) . '" class="btn btn-success btn-sm text-white">แก้ไข</a> &nbsp;'; 
                        $btn .= '<a href="' . route('labour.paymentEdit', $item->labour_id) . '" class="btn btn-primary btn-sm text-white">การชำระเงิน</a> &nbsp;';
                        $btn .= '<a href="' . route('labour.delete', $item->labour_id) . '" onclick="return confirm(`คุณต้องการลบข้อมูล ' . $item->labour_fullname . ' ใช่ไหม ?`)" class="btn btn-danger btn-sm">ลบ</a> &nbsp;';
                        
                    } elseif (Auth::user()->type == 'Admin') {
                        $btn = '<a href="' . route('labour.show', $item->labour_id) . '" class="btn btn-info btn-sm">ดูข้อมูล</a> &nbsp;';
                        $btn .= '<a href="' . route('labour.edit', $item->labour_id) . '" class="btn btn-success btn-sm text-white">แก้ไข</a> &nbsp;';
                         $btn .= '<a href="' . route('labour.paymentEdit', $item->labour_id) . '" class="btn btn-primary btn-sm text-white">การชำระเงิน</a> &nbsp;';
                    } else {
                        $btn = '<a href="' . route('labour.show', $item->labour_id) . '" class="btn btn-info btn-sm">ดูข้อมูล</a> &nbsp;';
                    }
                    echo $btn;
                @endphp
                        </td>
                </tr>
            @empty
                
            @endforelse
        </tbody>
    </table>
    {!! $labours->withQueryString()->links('pagination::bootstrap-4') !!}

    <br>


    </form>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            // Popover สำหรับยอดค้างชำระ
            $('[data-bs-toggle="popover"]').popover();
            //  $('#labour').DataTable();

            // $('#labour').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: "{{ route('labour.index') }}",
            //     columns: [{
            //             data: 'checkbox',
            //             name: 'checkbox'
            //         },
            //         {
            //             data: 'DT_RowIndex',
            //             name: 'DT_RowIndex'
            //         },
            //         {
            //             data: 'labour_fullname',
            //             name: 'labour_fullname'
            //         },
            //         {
            //             data: 'labour_passport_number',
            //             name: 'labour_passport_number'
            //         },
            //         {
            //             data: 'labour_visa_number',
            //             name: 'labour_visa_number'
            //         },
            //         {
            //             data: 'company_name',
            //             name: 'company_name'
            //         },
            //         {
            //             data: 'agency_name',
            //             name: 'agency_name'
            //         },
            //         {
            //             data: 'status',
            //             name: 'status'
            //         },

            //         {
            //             data: 'action',
            //             name: 'action'
            //         },
            //     ],
            //     // initComplete: function() {
            //     //     this.api().columns().every(function() {
            //     //         var column = this;
            //     //         var title = $(column.header()).text();
            //     //         if (title !== '') {
            //     //             $('<input type="text" placeholder="Search ' + title + '" />')
            //     //                 .appendTo($(column.header()).append(
            //     //                 '<br>')) // เพิ่มช่องค้นหาใต้ header เดิม
            //     //                 .on('keyup change clear', function() {
            //     //                     // ...
            //     //                 });
            //     //         }
            //     //     });
            //     // }
            // });


            // Hide And Show
            $('#show-massupdate').change(function() {
                if (this.checked) {
                    $('#div-massupdate').show();
                } else {
                    $('#div-massupdate').hide();
                }
            });
        });
    </script>
@endsection
