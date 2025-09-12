@extends('layouts.main_layout')
@section('content')

    @yield('content')
    <style>
        th.dt-center,
        td.dt-center {
            text-align: center;
        }
    </style>
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



    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <div class="pull-right">
                    <a href="{{route('company.create')}}" class="btn btn-primary pull-right text-right ">เพิ่มบริษัท</a>
                   
                </div>
               <br>
               <br>
                <table id="example" class="table table-striped table-bordered ">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อบริษัท</th>
                            <th>ทะเบียนบริษัท</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->


    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('company.index') }}",
                columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex'},  
                        { data: 'company_name', name: 'company_name'},  
                        { data: 'company_tax', name: 'company_tax'},  
                        { data: 'action', name: 'action'},  
                         ],
            });
        });
    </script>

@endsection
