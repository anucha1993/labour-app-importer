@extends('layouts.main_layout')

@section('content')
<div class="card">
<div class="card-body">
<div class="col-lg-12 border">
  <div class="row">
    <div class="col">
      <div class="bg-dark p-10 text-white text-center ">
        <i class="mdi mdi-account fs-3 mb-1 font-16 "></i>
        <h5 class="mb-0 mt-1">
          @php
             echo  DB::table('labour')->count();
          @endphp
        </h5>
        <small class="font-light">จำนวนข้อมูลคนงานทั้งหมด</small>
      </div>
    </div>
    <div class="col">
      <div class="bg-dark p-10 text-white text-center">
        <i class="mdi mdi-account fs-3 font-16"></i>
        <h5 class="mb-0 mt-1">
        @php
             echo  DB::table('labour')->where('labour_status_job','job')->count();
        @endphp</h5>
        <small class="font-light">จำนวนข้อมูลคนงานยังทำงานอยู่</small>
      </div>
    </div>
    <div class="col">
      <div class="bg-dark p-10 text-white text-center">
        <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
        <h5 class="mb-0 mt-1">
          @php
          echo  DB::table('labour')->where('labour_status_job','resign')->count();
           @endphp
        </h5>
        <small class="font-light">จำนวนข้อมูลคนงานลาออก</small>
      </div>
    </div>
    <div class="col">
      <div class="bg-dark p-10 text-white text-center">
        <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
        <h5 class="mb-0 mt-1">
          @php
          echo  DB::table('labour')->where('labour_status_job','escape')->count();
           @endphp
        </h5>
        <small class="font-light">จำนวนข้อมูลคนงานหลบหนี</small>
      </div>
    </div>
    <div class="col">
      <div class="bg-dark p-10 text-white text-center">
        <i class="mdi mdi-table fs-3 mb-1 font-16"></i>
        <h5 class="mb-0 mt-1">
          @php
          echo  DB::table('company')->count();
           @endphp
        </h5>
        <small class="font-light">จำนวนนายจ้างทังหมด</small>
      </div>
    </div>
    <div class="col">
      <div class="bg-dark p-10 text-white text-center">
        <i class="mdi mdi-web fs-3 mb-1 font-16"></i>
        <h5 class="mb-0 mt-1">
          @php
          echo  DB::table('agency')->count();
           @endphp
        </h5>
        <small class="font-light">จำนวนเอเจนซี่ทังหมด</small>
      </div>
    </div>
  </div>
</div>
</div>
</div>


<br>
<br>

<div class="card">
  <div class="card-body">

<h3>แจ้งเตือนเอกสารหมดอายุ <span class="text-danger">(แจ้งเตือนก่อนวันหมดอายุ 15 วัน)</span></h3>
<!-- Tabs -->

    <!-- Nav tabs -->
    <ul class="nav nav-tabs " role="tablist">
      <li class="nav-item">
        <a
          class="nav-link active"
          data-bs-toggle="tab"
          href="#visa"
          role="tab"
          ><span class="hidden-sm-up"></span>
          <span class="hidden-xs-down"><i class="mdi mdi-bell font-24"></i>วิซ่า </span> <span class="badge rounded-pill bg-danger"> {{ $notify_visa->total() }}</span></a >
      </li>
      <li class="nav-item">
        <a
          class="nav-link"
          data-bs-toggle="tab"
          href="#passport"
          role="tab"
          ><span class="hidden-sm-up"></span>
          <span class="hidden-xs-down"><i class="mdi mdi-bell font-24"></i> หนังสือเดินทาง </span><span class="badge rounded-pill bg-danger"> {{ $notify_passport->total() }}</span></a
        >
      </li>
      <li class="nav-item">
        <a
          class="nav-link"
          data-bs-toggle="tab"
          href="#work"
          role="tab"
          ><span class="hidden-sm-up"></span>
          <span class="hidden-xs-down"><i class="mdi mdi-bell font-24"></i> ใบอนุญาตทำงาน <span class="badge rounded-pill bg-danger"> {{ $notify_work->total() }}</span></span></a
        >
      </li>
      <li class="nav-item">
        <a
          class="nav-link"
          data-bs-toggle="tab"
          href="#day90"
          role="tab"
          ><span class="hidden-sm-up"></span>
          <span class="hidden-xs-down"> <i class="mdi mdi-bell font-24"></i> รายงานตัว 90 วัน <span class="badge rounded-pill bg-danger"> {{ $notify_day90->total() }}</span></span></a
        >
      </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content tabcontent-border">
      <div class="tab-pane active form-group " id="visa" role="tabpanel">
        <br>
        <a href="{{route('ExportExpire.Visa')}}" class="btn btn btn-success text-white"><i class="fa fa-file-excel"></i> ดาวน์โหลดข้อมูล</a>
        <br>
        <br>
        @foreach ($notify_visa as $visa)
         <div class="card border border-warning">
           <div class="card-body">
            <p><b>บริษัท :</b> {{$visa->company_name}}</p>
            <p><b>ชื่อแรงงาน :</b> {{$visa->labour_fullname}} <b>เลขที่วีซ่า :</b> {{$visa->labour_visa_number}}</p>
            <p><b>วันหมดอายุ :</b> {{date('d/m/Y', strtotime($visa->labour_visa_date_end));}} <b>จำนวนวันที่เหลือ : </b> {{now()->diffInDays(Carbon\Carbon::parse($visa->labour_visa_date_end), false)}} วัน <a href="{{route('labour.show',$visa->labour_id)}}" class="btn btn-sm btn-danger text-white">ดูข้อมูล</a></p>
      
           </div>
         </div>
        @endforeach

          {{-- paginate --}}
          {{ $notify_visa->links() }}
          
      </div>
      <div class="tab-pane p-20" id="passport" role="tabpanel">
        <br>
        <a href="{{route('ExportExpire.passport')}}" class="btn btn btn-success text-white"><i class="fa fa-file-excel"></i> ดาวน์โหลดข้อมูล</a>
        <br>
        <br>
        @foreach ($notify_passport as $passport)
         <div class="card border border-warning">
           <div class="card-body">
            <p><b>บริษัท :</b> {{$passport->company_name}}</p>
            <p><b>ชื่อแรงงาน :</b> {{$passport->labour_fullname}} <b>เลขที่หนังสือเดินทาง :</b> {{$passport->labour_passport_number}}</p>
            <p><b>วันหมดอายุ :</b> {{date('d/m/Y', strtotime($passport->labour_passport_date_end));}} <b>จำนวนวันที่เหลือ : </b> {{now()->diffInDays(Carbon\Carbon::parse($passport->labour_passport_date_end), false)}} วัน <a href="{{route('labour.show',$passport->labour_id)}}" class="btn btn-sm btn-danger text-white">ดูข้อมูล</a></p>
           </div>
         </div>
        @endforeach

          {{-- paginate --}}
          {{ $notify_passport->links() }}
          
      </div>
      <div class="tab-pane p-20" id="work" role="tabpanel">
        <br>
        <a href="{{route('ExportExpire.workpremit')}}" class="btn btn btn-success text-white"><i class="fa fa-file-excel"></i> ดาวน์โหลดข้อมูล</a>
        <br>
        <br>
        @foreach ($notify_work as $work)
         <div class="card border border-warning">
           <div class="card-body">
            <p><b>บริษัท :</b> {{$work->company_name}}</p>
            <p><b>ชื่อแรงงาน :</b> {{$work->labour_fullname}} <b>เลขที่ใบอนุญาตทำงาน :</b> {{$work->labour_workpremit_number}}</p>
            <p><b>วันหมดอายุ :</b> {{date('d/m/Y', strtotime($work->labour_workpremit_date_end));}} <b>จำนวนวันที่เหลือ : </b>{{now()->diffInDays(Carbon\Carbon::parse($work->labour_workpremit_date_end), false)}} วัน <a href="{{route('labour.show',$work->labour_id)}}" class="btn btn-sm btn-danger text-white">ดูข้อมูล</a></p>
        
           </div>
         </div>
        @endforeach

          {{-- paginate --}}
          {{ $notify_work->links() }}
          
      </div>
      <div class="tab-pane p-20" id="day90" role="tabpanel">
        <br>
        <a href="{{route('ExportExpire.day90')}}" class="btn btn btn-success text-white"><i class="fa fa-file-excel"></i> ดาวน์โหลดข้อมูล</a>
        <br>
        <br>
        @foreach ($notify_day90 as $day90)
         <div class="card border border-warning">
           <div class="card-body">
            <p><b>บริษัท :</b> {{$day90->company_name}}</p>
            <p><b>ชื่อแรงงาน :</b> {{$day90->labour_fullname}} <b>เลขที่หนังสือเดินทาง :</b> {{$day90->labour_passport_number}}</p>
            <p><b>วันหมดอายุ :</b> {{date('d/m/Y', strtotime($day90->labour_day90_date_end));}} <b>จำนวนวันที่เหลือ : </b>{{now()->diffInDays(Carbon\Carbon::parse($day90->labour_day90_date_end), false)}} วัน <a href="{{route('labour.show',$day90->labour_id)}}" class="btn btn-sm btn-danger text-white">ดูข้อมูล</a></p>
        
           </div>
         </div>
        @endforeach

          {{-- paginate --}}
          {{ $notify_day90->links() }}
          
      </div>
    </div>
  </div>
</div>
</div>
</div>


<script>
  $('#company').select2();
</script>

@endsection