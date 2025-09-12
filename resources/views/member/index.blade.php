
@extends('layouts.main_layout')
@section('content')

@yield('content')
<style>
    th.dt-center, td.dt-center { text-align: center; }
</style>
@if (count($errors) > 0)
   <div class = "alert alert-danger">
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




      <h5 class="card-title">รายชื่อบริษัท</h5>
      <div class="table-responsive">
        <a href="{{ route('register') }}" class="btn btn-primary pull-right text-right ">เพิ่มสมาชิก</a>
        <br>
        <br>
        <table id="example" class="table table-striped table-bordered ">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>อีเมล์</th>
                    <th>สิทธิ์การใช้งาน</th>
                    <th>สถานะ</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
      </div>
    </div>




<script>
    $(document).ready(function() {
        $('#example').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('member.indexAjax') }}",
            columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex'},  
                    { data: 'name', name: 'name'},  
                    { data: 'email_label', name: 'email_label'},  
                    { data: 'btn_type', name: 'btn_type'},  
                    { data: 'action', name: 'action'},  
                   
                     ],
                   
        });
    });
</script>


@endsection
