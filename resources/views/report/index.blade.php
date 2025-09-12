@extends('layouts.main_layout')

@section('content')
<div class="card">
    <div class="card-body">
      <h3 for="">รายงานข้อมูลคนงาน <i class="mdi mdi-file-pdf text-danger"></i> <i class="mdi mdi-file-excel text-success"></i></h3>
       <form action="{{route('report.customPDF')}}" method="POST">
        @csrf
         <div class="row">
          <div class="col-md-3">
            <label for="">บริษัท :</label>
             <select name="company" id="company" class="form-control">
               <option value="ALL">ทั้งหมด</option>
               @foreach ($company as $item)
                   <option value="{{$item->company_id}}">{{$item->company_name}}</option>
               @endforeach
             </select>
          </div>
          <div class="col-md-3">
            <label for="">สถานะ</label>
            <select name="status" id="status" class="form-control form-select">
              <option value="ALL">ทั้งหมด</option>
              <option value="job">ทำงาน</option>
              <option value="resign">ลาออก</option>
              <option value="escape">หลบหนี</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="">ประเภทรายงาน</label>
            <select name="type"  id="" class="form-select form-control">
                <option value="excel">Excel</option>
                <option value="pdf">pdf</option>
            </select>
          </div>
          <div class="col-md-1">
            <label for="">Report</label>
            <button type="submit" class="btn btn-danger">ดาวน์โหลด</button>
          </div>
         </div>
       </form>

       <br>
       <br>
       <br>
       <h3 for="">รายงานข้อมูลคนงานตามวันที่ที่เลือก <i class="mdi mdi-file-pdf text-danger"></i> <i class="mdi mdi-file-excel text-success"></i></h3>
       <form action="{{route('report.customdate')}}" method="POST">
        @csrf
         <div class="row">
          <div class="col-md-3">
            <label for="">บริษัท :</label>
             <select name="company" id="company" class="form-control">
               <option value="ALL">ทั้งหมด</option>
               @foreach ($company as $item)
                   <option value="{{$item->company_id}}">{{$item->company_name}}</option>
               @endforeach
             </select>
          </div>
          <div class="col-md-1">
            <label for="">สถานะ</label>
            <select name="status" id="status" class="form-control form-select">
              <option value="ALL">ทั้งหมด</option>
              <option value="job">ทำงาน</option>
              <option value="resign">ลาออก</option>
              <option value="escape">หลบหนี</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="">ประเภท</label>
            <select name="type_date" id="type" class="form-control form-select">
              <option value="labour.labour_visa_date_end">วีซ่าหมดอายุ</option>
              <option value="labour.labour_workpremit_date_end">ใบอนุญาตทำงานหมดอายุ</option>
              <option value="labour.labour_day90_date_end">รายงานตัว 90 วันหมดอายุ</option>
            </select>
          </div>

          <div class="col-md-1">
            <label for="">วันที่เริ่มต้น</label>
             <input type="date" class="form-control" name="date_start" required>
          </div>
          <div class="col-md-1">
            <label for="">วันที่สิ้นสุด</label>
             <input type="date" class="form-control" name="date_end" required>
          </div>

          <div class="col-md-2">
            <label for="">ประเภทรายงาน</label>
            <select name="type"  id="" class="form-select form-control">
                <option value="excel">Excel (ข้อมูลออกทั้งหมด)</option>
                <option value="pdf">pdf (ข้อมูลออกบางส่วน)</option>
            </select>
          </div>
          <div class="col-md-1">
            <label for="">Report</label>
            <button type="submit" class="btn btn-danger">ดาวน์โหลด</button>
          </div>
         </div>
       </form>

    </div>
  </div> 

  <script>
    $('#company').select2();
  </script>
@endsection