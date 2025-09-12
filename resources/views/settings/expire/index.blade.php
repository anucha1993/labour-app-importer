@extends('layouts.main_layout')

@section('content')
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
<div class="row">
    <div class="col-md-3">
        <h5>กำหนดวันแจ้งเตือนล่วงหน้า</h5>
      <form action="{{route('setting.update')}}" method="post">
        @csrf
      <table class="table table">
        <thead>
            <tr>
                <th>Setting Name</th>
                <th>expire/วัน</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $v)
            <tr>
                <td><input type="hidden" name="set_id[]" value="{{$v->set_id}}">{{$v->set_name}} แจ้งเตือนก่อนหมดอายุ</td>
                <td><input type="number" value="{{$v->set_expire}}" name="set_expire[]" class='form-control' style="width: 70px" ></td>
                <td> วัน</td>
            </tr>
            @endforeach
        </tbody>
      </table>
      <button type="submit" class="btn btn-success float-end text-white">บันทึกข้อมูล</button>
      </form>
    </div>
</div>

@endsection