@extends('layouts.main_layout')

@section('content')
    <div class="row">
  
        {{ Auth::user()->name }}
        <div class="col-md-12">
            <div class="card">
                <form action="{{route('company.store')}}" method="POST">
                    <div class="card-body">
                            @csrf
                            <h4 class="card-title">รายละเอียด</h4>
                            <div class="border-top border-success">
                                <div class="card-body">
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">ชื่อบริษัท / ชื่อนายจ้าง </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="lname" name="company_name" placeholder="ชื่อบริษัท" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">เลขทะเบียนบริษัท</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="company_tax" placeholder="เลขทะเบียนบริษัท" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">ที่อยู่</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="company_address" placeholder="ที่อยู่" required/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">จังหวัด</label>
                                            <div class="col-sm-9">
                                                <select name="company_province" id="province" class="form-control province form-select" required>
                                                    @foreach ($provinces as $item)
                                                        <option></option>
                                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">อำเภอ </label>
                                            <div class="col-sm-9">
                                                <select name="company_amphur" id="amphur" class="form-control amphur" required></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">ตำบล</label>
                                            <div class="col-sm-9">
                                                <select name="company_district" id="district" class="form-control district" required></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">รหัสไปรษณี </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="company_zipcode" class="form-control zipcode" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">ชื่อ-สกุล / นายจ้าง(สำหรับติดต่อ)</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="company_contact" class="form-control" placeholder="ชื่อ-สกุล" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">เบอร์ติดต่อ </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="company_phone" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">แฟกซ์</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="company_fax" class="form-control"
                                                    placeholder="Fax++" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">อีเมล์ </label>
                                            <div class="col-sm-9">
                                                <input type="email" name="company_email" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <h4 class="card-title">กรรมการผู้ลงนาม</h4>
                            <div class="border-top border-primary">
                                <div class="card-body">

                                </div>
                            </div>
                            <div class="form-group row ">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="col-sm-9" for="">ชื่อ-สกุล </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="company_contact1" class="form-control"
                                                placeholder="ชื่อ-สกุล ท่านที่ 1">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="col-sm-9" for="">ชื่อ-สกุล </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="company_contact2" class="form-control"
                                                placeholder="ชื่อ-สกุล ท่านที่ 2">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="col-sm-9" for="">ชื่อ-สกุล </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="company_contact3" class="form-control"
                                                placeholder="ชื่อ-สกุล ท่านที่ 3">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="col-sm-9" for="">ชื่อ-สกุล </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="company_contact4" class="form-control"
                                                placeholder="ชื่อ-สกุล ท่านที่ 4">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label class="col-sm-9" for="">ชื่อ-สกุล </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="company_contact5" class="form-control"
                                                placeholder="ชื่อ-สกุล ท่านที่ 5">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="card-title">อื่นๆ</h4>
                            <div class="border-top border-warning">
                                <div class="card-body">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">ประเภทกิจการ</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="company_type" class="form-control" placeholder="ประเภทกิจการ" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">ผู้ประสานงานการตลาด</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="company_contact_sale" class="form-control" placeholder="ผู้ประสานงานการตลาด" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">หมายเหตุ</label>
                                            <div class="col-sm-9">
                                                <textarea name="company_note" class="form-control" id="" cols="10" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label class="col-sm-9" for="">สถานะ</label>
                                            <div class="col-sm-9">
                                                
                                                <input type="radio" name="company_status" value="enable">
                                                <label for="">เปิดใช้งาน</label>
                                                <input type="radio" name="company_status" value="disable">
                                                <label for="">ปิดใช้งาน</label>
                                            </div>
                                        </div>
                                    </div>



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

            </div>
            </form>


        </div>
    </div>
    </div>




    <script>
        $("#province").select2({
            placeholder: "Select a Province"
        });
        $("#amphur").select2({
            placeholder: "Select a Amphur"
        });
        $("#district").select2({
            placeholder: "Select a District"
        });


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.province').change(function() {

            if ($(this).val() != '') {
                var province = $(this).val();
                console.log(province);
                $.ajax({
                    url: "{{ route('province') }}",
                    method: "POST",
                    data: {
                        province: province,
                    },
                    success: function(result) {
                        $('.amphur').html(result);
                    }
                })
            }
        });
        $('.amphur').change(function() {

            if ($(this).val() != '') {
                var amphur = $(this).val();
                console.log(amphur);
                $.ajax({
                    url: "{{ route('amphur') }}",
                    method: "POST",
                    data: {
                        amphur: amphur,
                    },
                    success: function(result) {
                        $('.district').html(result);
                    }
                })
            }
        });

        $('.district').change(function() {
if ($(this).val() != '') {
    var district = $(this).val();
    console.log(amphur);
    $.ajax({
        url: "{{ route('district') }}",
        method: "POST",
        data: {
            district: district,
        },
        success: function(result) {
            $('.zipcode').val(result);
        }
    })
}
});
    </script>
@endsection
