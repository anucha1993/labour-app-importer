@extends('layouts.main_layout')

@section('content')
<a href="{{ URL::asset('../public/file/from-import.xlsx')}}" class="btn btn-info float-end" download="">ดาวน์โหลดฟอร์มกรอกข้อมูล</a>
    <h3>นำเข้าข้อมูล Excel</h3>
   
    <div class="border-top border-success">
        <div class="card-body">
        </div>
    </div>
    <form action="{{route('import-excel.labour')}}" method="post" enctype="multipart/form-data">
        @csrf
    <div class="row">
       <div class="col-md-3">
        <label for="">บริษัท</label>
         <select class="form-control" name="company" id="company" required>
            <option></option>
            @foreach($company as $item)
            <option value="{{$item->company_id}}">{{$item->company_name}}</option>
            @endforeach
         </select>

       </div>
       <div class="col-md-2">
        <label for="">เอเจนซี่</label>
        <select name="agency" id="agency" class="form-control" required>
            <option></option>
            @foreach ($agency as $item)
                <option value="{{$item->agency_id}}">{{$item->agency_name}}</option>
            @endforeach
        </select>
       </div>
       <div class="col-md-2">
        <label for="">สัญชาติ</label>
      <select name="nationality" id="nationality" class="form-control" required>
        <option></option>
        @foreach($nationality as $item)
        <option value="{{$item->code}}">{{$item->name_th}}</option>
        @endforeach
      </select>
       </div>
       <div class="col-md-2">
        <label for="">Excel file</label>
        <input type="file" name="file" class="form-control" required >
       </div>
       <div class="col-md-2">
        <label for="">Action</label>
        <footer>
            <button type="submit" class="btn btn-primary">Insert</button>
           </footer>
       </div>

    </div>

</form>

 

    <script>
        $('#nationality').select2({
            placeholder : 'Select a Nationality',
        });
        $('#agency').select2({
            placeholder : 'Select a Agency',
        });
        $('#company').select2({
            placeholder : 'Select a Company',
        });
    </script>
@endsection