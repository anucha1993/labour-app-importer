@extends('layouts.main_layout')

@section('content')
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="{{route('agency.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title">ข้อมูลเอเจนซี่</h4>
                        <div class="form-group row">
                            <label for="fname" class="col-sm-3 text-end control-label col-form-label">ชื่อบริษัท/เอเจนซี่</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="fname" placeholder="ชื่อบริษัท/เอเจนซี่" name="agency_name" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lname" class="col-sm-3 text-end control-label col-form-label">เลขทะเบียนบริษัท</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="lname" placeholder="เลขทะเบียนบริษัท" name="agency_tax" required />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lname" class="col-sm-3 text-end control-label col-form-label">สัญชาติ</label>
                            <div class="col-sm-9">
                               <select name="agency_nationality" id="" class="form-control form-select" required>

                                <option selected disabled></option>
                                <option value="myanmar">พม่า</option>
                                <option value="cambodia">กัมพูชา</option>
                                <option value="lao">ลาว</option>
                                <option value="vietnam">เวียดนาม</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lname" class="col-sm-3 text-end control-label col-form-label">ที่อยู่</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control" id="lname" placeholder="ที่อยู่" name="agency_address" required ></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email1" class="col-sm-3 text-end control-label col-form-label">อีเมล์</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email1" placeholder="@email" name="agency_email" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cono1" class="col-sm-3 text-end control-label col-form-label">ชื่อ กรรมการบริษัท</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="cono1" placeholder="ชื่อ กรรมการบริษัท" name="agency_contact" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cono1" class="col-sm-3 text-end control-label col-form-label">เบอร์ติดต่อ</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="cono1" placeholder="บอร์ติดต่อ" name="agency_phone" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cono1" class="col-sm-3 text-end control-label col-form-label">หมายเหตุ</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="agency_note"></textarea>
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
                </form>
            </div>
        </div>
    </div>
@endsection
