@extends('layouts.main_layout')

@section('content')

     <form action="{{route('labour.store')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        <input type="hidden" name="created_by" value="{{Auth::user()->name}}">
        <div class="col-md-12">
            <h3>ข้อมูลส่วนตัว</h3>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>

            <div class="row">
                <div class="col-md-1">
                    <label for="">คำนำหน้า :</label>
                    <select name="labour_prefix" class="form-select" required>
                        <option value="Mr">Mr.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Mrs">Mrs.</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="">ชื่อ-สกุล EN:</label>
                    <input type="text" name="labour_fullname" placeholder="Fullname EN" class="form-control" required>
                </div>
                  <div class="col-md-4">
                    <label for="">ชื่อ-สกุล TH :</label>
                    <input type="text" name="labour_fullname_th" placeholder="ชื่อภาษาไทย" class="form-control" >
                </div>

                <div class="col-md-1">
                    <label for="">เพศ</label>
                    <select name="labour_sex" class="form-control form-select" id="sex" required  placeholder="Fullname">
                        <option value="male">ชาย</option>
                        <option value="female">หญิง</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="nationality">สัญชาติ :</label>
                   <select name="labour_nationality" class="form-control form-select" id="nationality" required >
                    @foreach ($nationality as $item)
                        <option value="{{$item->code}}">{{$item->name_th}}</option>
                    @endforeach
                    <option value=""></option>
                   </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 pt-3">
                    <label for="">ชื่อเอเจซี่ :</label>
                    <select name="labour_agency" id="agency" class="form-control" >
                       @foreach ($agency as $item)
                           <option></option>
                           <option value="{{$item->agency_id}}">{{$item->agency_name}}</option>
                       @endforeach
                    </select>
                </div>
                <div class="col-md-3 pt-3">
                    <label for="">วันเกิด :</label>
                    <input type="date" name="labour_birthday"  onchange="calAge(this)" id="birthday" class="form-control">
                </div>
                <div class="col pt-3">
                    <label for="">อายุ</label>
                    <input type="text" id="age_year" class="form-control" disabled>
                </div>
            </div>
            <h4 class="card-title pt-4">ข้อมูลบริษัท</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label for="">บริษัท</label>
                <select name="company_id" id="company" class="form-control">
                    <option></option>
                    @foreach ($company as $item)
                        <option value="{{$item->company_id}}">{{$item->company_name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <h4 class="card-title pt-4">ข้อมูลหนังสือเดินทาง</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="">เลขที่หนังสือเดินทาง</label>
                    <input type="text" name="labour_passport_number" placeholder="PassportNumber" class="form-control">
                </div>
                <div class="col">
                    <label for="">วันที่ออกเล่ม</label>
                    <input type="date" name="labour_passport_date_start" placeholder="PassportNumber" class="form-control">
                </div>
                <div class="col">
                    <label for="">วันที่หมดอายุ</label>
                    <input type="date" name="labour_passport_date_end" onchange="passend(this)" id="pass-date-end" placeholder="Passport" class="form-control">
                    <p><label id="out1"></label></p>
                </div>
                <div class="col">
                    <label for="">จำนวนวันที่เหลือ ก่อนหมดอายุ</label>
                    <input type="text" id="PassEndDate" placeholder="PassportDateEnd" class="form-control" readonly>
                </div>
            </div>
            <h4 class="card-title pt-4">ข้อมูลวีซ่า</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="">เลขที่วีซ่า</label>
                    <input type="text" name="labour_visa_number" placeholder="PassportNumber" class="form-control">
                </div>
                <div class="col">
                    <label for="">วันที่เดินทางเข้ามา</label>
                    <input type="date" name="labour_visa_date_in"  class="form-control">
                </div>
                <div class="col">
                    <label for="">วันที่เริ่มวิซ่า</label>
                    <input type="date" name="labour_visa_date_start" class="form-control">
                </div>
                <div class="col">
                    <label for="">วันที่หมดอายุ</label>
                    <input type="date" name="labour_visa_date_end" onchange="visaend(this)" id="visa-date-end" placeholder="Visa" class="form-control">
                    <p><label id="out1"></label></p>
                </div>
                <div class="col">
                    <label for="">จำนวนวันที่เหลือ ก่อนหมดอายุ</label>
                    <input type="text" id="VisaEndDate" placeholder="VisaDateEnd" class="form-control" readonly>
                </div>
            </div>
            
            <h4 class="card-title pt-4">ข้อมูลใบอนุญาตทำงาน</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label for="">เลขที่ใบอนุญาตทำงาน</label>
                    <input type="text" name="labour_workpremit_number" placeholder="WorkPremitNumber" class="form-control">
                </div>
                <div class="col">
                    <label for="">รหัสพนักงาน (ถ้ามี)</label>
                    <input type="text" name="labour_labour_number"  class="form-control">
                </div>
                <div class="col">
                    <label for="">วันที่ใบอนุญาตเริ่มต้น</label>
                    <input type="date" name="labour_workpremit_date_start" class="form-control">
                </div>
                <div class="col">
                    <label for="">วันที่ใบอนุญาตสิ้นสุด</label>
                    <input type="date" name="labour_workpremit_date_end" onchange="Workpremitend(this)" id="workpremit-date-end" placeholder="WorkPreMit" class="form-control">
                    <p><label id="out1"></label></p>
                </div>
                <div class="col">
                    <label for="">จำนวนวันที่เหลือ ก่อนหมดอายุ</label>
                    <input type="text" id="workpremitEndDate" placeholder="workpremitDateEnd" class="form-control" readonly>
                </div>
            </div>

            <h4 class="card-title pt-4">ข้อมูลรายงานตัว 90 วัน</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="">วันที่รายงานตัว 90 วัน เริ่มต้น</label>
                    <input type="date" name="labour_day90_date_start" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">วันที่รายงานตัว 90 วัน สิ้นสุด</label>
                    <input type="date" name="labour_day90_date_end" id="day90-date-end" onchange="day90end(this)" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">จำนวนวันที่เหลือ ก่อนหมดอายุ</label>
                    <input type="text" id="day90End" class="form-control" placeholder="Day90DateEnd" readonly>
                </div>
            </div>

            <h4 class="card-title pt-4">ข้อมูล ตม.</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="">เลขที่ ตม.</label>
                    <input type="text" name="labour_tm_number" class="form-control" placeholder="เลขที่ ตม.">
                </div>
{{-- 
                <div class="col-md-3">
                    <label for="">รหัสพนักงาน </label>
                    <input type="text" name="labour_number" class="form-control" placeholder="Numner" >
                </div> --}}

            </div>

            <h4 class="card-title pt-4">สถานะแรงงาน</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="">วันที่เริ่มเข้าทำงาน</label>
                    <input type="date" name="labour_jobdate_start" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">สถานะแรงงาน</label>
                    <select name="labour_status_job" id="labour_status" class="form-control form-select">
                        <option value="job">ทำงาน</option>
                        <option value="resign">ลาออก</option>
                        <option value="escape">หลบหนี</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div id="status_labour"></div>
                </div>
            </div>

            <h4 class="card-title pt-4">ไฟล์เอกสาร</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label>ไฟล์เอกสารหนังสือเดินทาง</label>
                    <input type="file" name="file_passport" class="form-control">


                </div>
                <div class="col-md-3">
                    <label>ไฟล์เอกสารวีซ่า</label>
                   
                    <input type="file" name="file_visa" class="form-control">


                </div>
                <div class="col-md-3">
                    <label>ไฟล์เอกสารใบอนุญาตทำงาน</label>
                    <input type="file" name="file_work" class="form-control">
                </div>
            </div>




            <h4 class="card-title pt-4">หมายเหตุ</h4>
            <div class="border-top border-primary">
                <div class="card-body">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input type="radio" name="labour_status" value="enable">
                    <label for="">เปิดใช้งาน</label>
                    <input type="radio" name="labour_status" value="disable">
                    <label for="">ปิดใช้งาน</label>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="">หมายเหตุ</label>
                    <textarea name="labour_note" class="form-control" cols="15" rows="10"></textarea>
                </div>
            </div>

            <div class="border-top">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        บันทึกข้อมูล
                    </button>
                </div>
            </div>



        </div>
     </form>




     <script>
      $('#agency').select2({
        placeholder: 'Select a Agency',
      });
      $('#company').select2({
        placeholder: 'Select a Company',
      });

    $('#labour_status').change(function(){
        var select = $(this).val();
        console.log(select);
        $.ajax({
          success: function(result){
            if(select == 'job'){
            $('#status_labour').html('');
            }

            if(select == 'resign'){
            $('#status_labour').html('<label for="">วันที่ลาออก</label> <input type="date" class="form-control" name="labour_resing_date">');
            }
            if(select == 'escape'){
            $('#status_labour').html('<label for="">วันที่หลบหนี</label> <input type="date" class="form-control" name="labour_escape_date">');
            }
          }
        })
    });


      moment.updateLocale('th', {
    durationLabelsStandard: {
        S: 'millisecond',
        SS: 'milliseconds',
        s: 'ว',
        ss: 'วินาที',
        m: 'นาที',
        mm: 'นาที',
        h: 'ชม.',
        hh: 'ชั่วโมง',
        d: 'ว',
        dd: 'วัน',
        w: 'สัปดาห์',
        ww: 'สัปดาห์',
        M: 'เดือน',
        MM: 'เดือน',
        y: 'ป',
        yy: 'ปี'
    }
});

function calAge() {
  var date = document.getElementById("birthday").value;
  console.log(date)
  var diff = moment(date).diff(moment(), 'milliseconds');
  var duration = moment.duration(diff);
  document.getElementById("age_year").value =duration.format().replace("-","");
}

calAge();


Date.getFormattedDateDiff = function(date1, date2) {
  var b = moment(date1),
      a = moment(date2),
      intervals = ['years','months','weeks','days'],
      intervals_th = ['ปี','เดือน','สัปดาห์','วัน'],
      out = [];
      out = [];

  for(var i=0; i<intervals.length; i++){
      var diff = a.diff(b, intervals[i]);
      b.add(diff, intervals[i]);
      out.push(diff + ' ' + intervals_th[i]);
  }
  return out.join(', ');
};
// คำนวนวันหมดอายุ Passport
function passend() {
   var end = new Date(document.getElementById('pass-date-end').value),
        start   = new Date();
  
   document.getElementById('PassEndDate').value 
     = 'จำนวนวันที่เหลือ'
     + Date.getFormattedDateDiff(start, end);
}
// คำนวนวันหมดอายุ Visa
function visaend() {
   var end = new Date(document.getElementById('visa-date-end').value),
        start   = new Date();
  
   document.getElementById('VisaEndDate').value 
     = 'จำนวนวันที่เหลือ'
     + Date.getFormattedDateDiff(start, end);
}

// คำนวนวันหมดอายุ WorkPreMit
function Workpremitend() {
   var end = new Date(document.getElementById('workpremit-date-end').value),
        start   = new Date();
  
   document.getElementById('workpremitEndDate').value 
     = 'จำนวนวันที่เหลือ'
     + Date.getFormattedDateDiff(start, end);
}
// คำนวนวันหมดอายุ 90day
function day90end() {
   var end = new Date(document.getElementById('day90-date-end').value),
        start   = new Date();
  
   document.getElementById('day90End').value 
     = 'จำนวนวันที่เหลือ'
     + Date.getFormattedDateDiff(start, end);
}




     </script>
@endsection