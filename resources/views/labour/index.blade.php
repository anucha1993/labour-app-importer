@extends('layouts.main_layout')

@section('content')
<style>
/* QR Code Styles */
.qr-code-img {
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.qr-code-img:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.2);
}

.qr-fallback {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed #6c757d !important;
    border-radius: 4px;
    color: #6c757d;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-family: monospace;
}

.qr-fallback:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: #495057 !important;
    color: #495057;
    transform: scale(1.05);
}

.qr-code-link {
    text-decoration: none;
    display: inline-block;
}

.qr-code-link:hover {
    text-decoration: none;
}

/* Loading animation for QR codes */
.qr-loading {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.qr-loading::after {
    content: '';
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: qr-spin 1s linear infinite;
}

@keyframes qr-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* QR Code status indicators */
.qr-status {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.qr-status.success { background: #28a745; }
.qr-status.error { background: #dc3545; }
.qr-status.loading { background: #ffc107; }
</style>
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

                    
                    <!-- QR Code Scanner Section -->
                    <div class="col-md-3 mb-2">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="useQrScanner" name="use_qr_scanner">
                            <label class="form-check-label" for="useQrScanner">
                                ค้นหาด้วย QR Code/เลขที่หนังสือเดินทาง
                            </label>
                        </div>
                        <input type="text" class="form-control" id="qrScannerInput" name="qr_code" 
                               placeholder="สแกน QR Code หรือป้อนเลขหนังสือเดินทาง..." 
                               style="display: none;"
                               value="{{ $request->qr_code }}">
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



    {{-- <input type="checkbox" id="show-massupdate"> <label for="">Mass Update</label> --}}

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




    <!-- Print QR Codes Button -->
    <div class="mb-3">
        <div class="btn-group" role="group">
            <button id="printSelectedQRCodes" class="btn btn-primary" disabled>
                <i class="fas fa-print"></i> พิมพ์ QR Codes ที่เลือก
            </button>
            <button id="previewSelectedQRCodes" class="btn btn-info" disabled>
                <i class="fas fa-eye"></i> ดูตัวอย่าง QR Codes
            </button>
            {{-- <button id="testSelectedQRCodes" class="btn btn-warning" disabled>
                <i class="fas fa-vial"></i> ทดสอบ QR Codes
            </button> --}}
        </div>
        <span id="selectedCount" class="ms-2">(0 รายการที่เลือก)</span>
    </div>

    <table class="table labour table-striped table-bordered" id="labour">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="selectAll">
                </th>
                <th>ลำดับ</th>
                <th>ชื่อ-สกุล</th>
                <th>เลขที่หนังสือเดินทาง</th>
                <th>รหัสพนักงาน</th>
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
                    <td>{{$item->labour_number}}</td>

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
                            <a href="{{ route('labour.qrcodeDetail', $item->labour_id) }}" class="qr-code-link" title="คลิกเพื่อดูข้อมูลคนงาน">
                                <div class="position-relative d-inline-block">
                                    <div class="qr-loading" id="qr-loading-{{ $item->labour_id }}"></div>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode($item->labour_passport_number)}}" 
                                         alt="QR Code: {{ $item->labour_passport_number }}" 
                                         width="60" 
                                         height="60" 
                                         class="qr-code-img"
                                         data-passport="{{ $item->labour_passport_number }}"
                                         data-labour-id="{{ $item->labour_id }}"
                                         style="display: none;"
                                         onload="handleQRLoad(this)"
                                         onerror="handleQRError(this)" />
                                    <div class="qr-fallback" style="display: none; width: 60px; height: 60px;" onclick="retryQRCode(this)">
                                        <strong style="font-size: 12px;">QR</strong>
                                        <small style="font-size: 9px;">{{ substr($item->labour_passport_number, 0, 6) }}</small>
                                        <div style="font-size: 8px; margin-top: 2px;">📱 คลิก</div>
                                    </div>
                                    <div class="qr-status loading" id="qr-status-{{ $item->labour_id }}"></div>
                                </div>
                            </a>
                        </td>
                        <td>
                            @php
                                $pendingTypes = $item->paymentTypes->where('status', '!=', 'completed');
                            @endphp
                            @if($pendingTypes->count() > 0)
                                <button type="button" 
                                        class="btn btn-warning btn-sm payment-pending-btn" 
                                        data-labour-id="{{ $item->labour_id }}"
                                        data-bs-toggle="popover"
                                        data-bs-html="true"
                                        title="รายการค้างชำระ - คลิกเพื่อดูรายละเอียด"
                                        data-bs-content="@foreach($pendingTypes as $type)
                                            <div class='payment-item' style='cursor: pointer; padding: 5px; border-radius: 4px; margin: 2px 0;' 
                                                 onclick='loadPaymentDetail({{ $item->labour_id }}, {{ $type->id }})' 
                                                 onmouseover='this.style.backgroundColor=&quot;#f0f0f0&quot;' 
                                                 onmouseout='this.style.backgroundColor=&quot;transparent&quot;'>
                                                <strong>{{ $type->payment_name }}</strong><br>
                                                <small>ยอดค้าง: {{ number_format($type->total_amount - $type->calculatePaidAmount(), 2) }} บาท ({{ $type->status }})</small>
                                                <br><small style='color: #666;'>👆 คลิกเพื่อดูรายละเอียด</small>
                                            </div>
                                        @endforeach
                                        <hr style='margin: 8px 0;'>
                                        <div style='text-align: center;'>
                                            <button class='btn btn-primary btn-sm' onclick='loadQrCodeData(&quot;{{ route('labour.qrcodeDetail', $item->labour_id) }}&quot;)'>
                                                ดูประวัติทั้งหมด
                                            </button>
                                        </div>">
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
    
    <!-- QR Code Control Panel -->
    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">QR Code Controls</h6>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-primary" onclick="forceLoadAllQRCodes()">
                            <i class="fas fa-sync-alt"></i> โหลด QR ทั้งหมดใหม่
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="retryFailedQRCodes()">
                            <i class="fas fa-exclamation-triangle"></i> ลองโหลดที่ล้มเหลวใหม่
                        </button>
                        <button type="button" class="btn btn-sm btn-info" onclick="checkQRStatus()">
                            <i class="fas fa-info-circle"></i> ตรวจสอบสถานะ
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">
                        หาก QR Code ไม่แสดงในหน้าใหม่ ให้กดปุ่ม "โหลด QR ทั้งหมดใหม่"
                    </small>
                </div>
            </div>
        </div>
    </div>

    <br>


    </form>

    <!-- Modal แสดงข้อมูล QR Code -->
    <div class="modal fade" id="qrCodeDetailModal" tabindex="-1" aria-labelledby="qrCodeDetailModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="height: 90vh;">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="qrCodeDetailModalLabel">
                        <i class="fas fa-user me-2"></i>ข้อมูลแรงงาน
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
                </div>
                <div class="modal-body p-3" style="height: calc(90vh - 130px); overflow-y: auto;">
                    <div id="qrCodeDetailContent" class="position-relative h-100">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCloseModal" onclick="closeModal()">
                        <i class="fas fa-times me-1"></i>ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>

     <script>
    // ฟังก์ชันปิด modal แบบแน่นอน
    function closeModal() {
        console.log('closeModal() function called');
        
        try {
            const modalElement = document.getElementById('qrCodeDetailModal');
            if (!modalElement) {
                console.error('Modal element not found');
                return;
            }
            
            // วิธีที่ 1: ใช้ Bootstrap Modal instance
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                console.log('Using existing modal instance');
                modalInstance.hide();
            } else {
                console.log('Creating new modal instance');
                const newModal = new bootstrap.Modal(modalElement);
                newModal.hide();
            }
            
            // วิธีที่ 2: Force close ด้วย jQuery (backup)
            setTimeout(() => {
                if (modalElement.classList.contains('show')) {
                    console.log('Force closing modal with jQuery');
                    $('#qrCodeDetailModal').modal('hide');
                }
            }, 100);
            
            // วิธีที่ 3: Manual close (last resort)
            setTimeout(() => {
                if (modalElement.classList.contains('show')) {
                    console.log('Manual close - last resort');
                    modalElement.classList.remove('show');
                    modalElement.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    
                    // ลบ backdrop
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                    
                    // Clear modal content
                    $('#qrCodeDetailContent').html('');
                }
            }, 500);
            
        } catch (error) {
            console.error('Error closing modal:', error);
            
            // Emergency close
            const modalElement = document.getElementById('qrCodeDetailModal');
            if (modalElement) {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                $('.modal-backdrop').remove();
                $('#qrCodeDetailContent').html('');
            }
        }
    }
    
    // Event listeners สำหรับการปิด modal
    $(document).ready(function() {
        // ปุ่มปิด
        $(document).on('click', '#btnCloseModal', function(e) {
            e.preventDefault();
            console.log('Close button clicked via event listener');
            closeModal();
        });
        
        // ปุ่ม X
        $(document).on('click', '.btn-close', function(e) {
            e.preventDefault();
            console.log('X button clicked via event listener');
            closeModal();
        });
        
        // ESC key
        $(document).on('keydown', function(e) {
            if ((e.key === 'Escape' || e.keyCode === 27)) {
                const modal = document.getElementById('qrCodeDetailModal');
                if (modal && modal.classList.contains('show')) {
                    console.log('ESC key pressed');
                    closeModal();
                }
            }
        });
        
        // Backdrop click
        $(document).on('click', '#qrCodeDetailModal', function(e) {
            if (e.target === e.currentTarget) {
                console.log('Backdrop clicked');
                closeModal();
            }
        });
    });
    </script>

    <script>
        // ฟังก์ชันสำหรับ initialize accordion ใน modal
        function initializeAccordions() {
            // ลบ event listeners เดิม
            $('.accordion-header').off('click');
            
            // เพิ่ม event listeners ใหม่
            $('.accordion-header').on('click', function() {
                const accordionId = $(this).attr('onclick');
                if (accordionId) {
                    // Extract ID from onclick attribute
                    const match = accordionId.match(/toggleAccordion\('(.+)'\)/);
                    if (match) {
                        const id = match[1];
                        toggleAccordion(id);
                    }
                } else {
                    // Fallback for Bootstrap accordion
                    $(this).toggleClass('collapsed');
                    const target = $(this).attr('data-bs-target');
                    if (target) {
                        $(target).toggleClass('show');
                    }
                }
            });
            
            console.log('Accordion event listeners attached:', $('.accordion-header').length);
        }

        // Global toggleAccordion function for modal content
        function toggleAccordion(id) {
            console.log('Toggling accordion:', id);
            const acc = document.getElementById(id);
            if (acc) {
                console.log('Accordion found, current state:', acc.classList.contains('active') ? 'active' : 'inactive');
                
                if (acc.classList.contains('active')) {
                    acc.classList.remove('active');
                    console.log('Accordion closed:', id);
                } else {
                    // Close all accordions first
                    const allAccordions = document.querySelectorAll('.accordion');
                    console.log('Found accordions:', allAccordions.length);
                    allAccordions.forEach(function(a) { 
                        a.classList.remove('active'); 
                    });
                    // Open the clicked accordion
                    acc.classList.add('active');
                    console.log('Accordion opened:', id);
                    
                    // Force show content
                    const content = acc.querySelector('.accordion-content');
                    if (content) {
                        content.style.display = 'block';
                        console.log('Content forced to display');
                    }
                }
            } else {
                console.error('Accordion not found:', id);
                // Try to find it with different selector
                const allElements = document.querySelectorAll('[id*="acc-"]');
                console.log('Available accordion IDs:', Array.from(allElements).map(el => el.id));
            }
        }

        $(document).ready(function() {
            console.log('Document ready - Starting initialization');
            $('.select2').select2();

            // ไม่ต้องสร้าง modal instance ล่วงหน้า - ใช้ built-in Bootstrap behavior
            const modalElement = document.getElementById('qrCodeDetailModal');
            if (modalElement) {
                console.log('Modal element found');
                
                // เพิ่ม event listener สำหรับการปิด modal
                modalElement.addEventListener('hidden.bs.modal', function() {
                    console.log('Modal hidden event triggered');
                    $('#qrCodeDetailContent').html('');
                });
                
                // เพิ่ม event listener สำหรับการแสดง modal
                modalElement.addEventListener('shown.bs.modal', function() {
                    console.log('Modal shown event triggered');
                });
                
                // เพิ่ม event listener สำหรับการซ่อน modal
                modalElement.addEventListener('hide.bs.modal', function() {
                    console.log('Modal hide event triggered');
                });
            }
            
            // ฟังก์ชันทดสอบ modal - Version 2
            window.testModalOpen = function() {
                console.log('Testing modal open...');
                const modalElement = document.getElementById('qrCodeDetailModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                    $('#qrCodeDetailContent').html('<div class="p-4 text-center">ทดสอบเปิด Modal<br><button onclick="closeModal()" class="btn btn-danger mt-3">ปิด Modal</button></div>');
                }
            };
            
            // ฟังก์ชันทดสอบปิด modal
            window.testModalClose = function() {
                console.log('Testing modal close...');
                closeModal();
            };

            // QR Scanner Toggle
            $('#useQrScanner').off('change').on('change', function() {
                const isChecked = $(this).is(':checked');
                console.log('QR Scanner checkbox changed:', isChecked);
                
                const qrInput = $('#qrScannerInput');
                qrInput.toggle(isChecked);
                
                if (isChecked) {
                    // Focus ทันทีและใช้ setTimeout เพื่อให้แน่ใจว่า focus ได้
                    setTimeout(function() {
                        qrInput.focus();
                        qrInput.select(); // เลือกข้อความทั้งหมดถ้ามี
                        console.log('QR Scanner input focused');
                    }, 100);
                } else {
                    qrInput.val('').blur();
                    console.log('QR Scanner input cleared and blurred');
                }
            });

            // เมื่อคลิกที่ input ให้ focus อีกครั้ง
            $('#qrScannerInput').off('click').on('click', function() {
                $(this).focus();
            });

                // ป้องกันการ submit ฟอร์มเมื่อกด Enter ในช่อง qrScannerInput
                $('#qrScannerInput').on('keydown', function(e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        e.preventDefault();
                        return false;
                    }
                });

            // เมื่อ input สูญเสีย focus แล้วยังติ๊กอยู่ ให้ focus กลับมา
            $('#qrScannerInput').off('blur').on('blur', function() {
                const $this = $(this);
                if ($('#useQrScanner').is(':checked')) {
                    setTimeout(function() {
                        if (!$this.is(':focus') && $('#useQrScanner').is(':checked')) {
                            $this.focus();
                            console.log('Re-focused QR Scanner input');
                        }
                    }, 100);
                }
            });

            // QR Scanner Input Handler - ใช้ debounce เพื่อป้องกันการเรียกซ้ำ
            let inputTimer;
            $('#qrScannerInput').off('input').on('input', function() {
                const $this = $(this);
                const input = $this.val().trim();
                
                clearTimeout(inputTimer);
                
                if (!input || !$('#useQrScanner').is(':checked')) {
                    return;
                }

                inputTimer = setTimeout(function() {
                    console.log('Processing input:', input);
                    
                    if (!input.startsWith('http')) {
                        // ค้นหาด้วยเลขพาสปอร์ต
                        searchByPassport(input);
                    } else {
                        // ค้นหาด้วย URL
                        loadQrCodeData(input);
                    }
                    
                    $this.val(''); // ล้างค่าหลังประมวลผล
                }, 300);
            });

            // Click Handler สำหรับ QR Code Images
            $(document).off('click', '.qr-code-link').on('click', '.qr-code-link', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                console.log('QR Code clicked:', url);
                loadQrCodeData(url);
            });

            // ฟังก์ชันค้นหาด้วยเลขพาสปอร์ต
            function searchByPassport(passportNumber) {
                console.log('Searching by passport:', passportNumber);
                
                // ค้นหาในตารางปัจจุบันก่อน
                let found = false;
                $('table#labour tbody tr').each(function() {
                    const rowPassport = $(this).find('td:nth-child(4)').text().trim();
                    if (rowPassport === passportNumber) {
                        found = true;
                        const qrUrl = $(this).find('.qr-code-link').attr('href');
                        if (qrUrl) {
                            console.log('Found in table, loading:', qrUrl);
                            loadQrCodeData(qrUrl);
                        }
                        return false; // break loop
                    }
                });
                
                if (!found) {
                    // ค้นหาผ่าน API
                    console.log('Not found in table, trying API');
                    $.ajax({
                        url: `/labour/passport/${passportNumber}`,
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            if (response.success && response.url) {
                                loadQrCodeData(response.url);
                            } else {
                                alert('ไม่พบข้อมูลเลขที่หนังสือเดินทาง');
                            }
                        },
                        error: function(xhr) {
                            console.error('API Error:', xhr);
                            alert('ไม่พบข้อมูลเลขที่หนังสือเดินทาง');
                        }
                    });
                }
            }

            // ฟังก์ชันโหลดข้อมูล QR Code
            function loadQrCodeData(url) {
                console.log('Loading QR data from:', url);
                
                const modalContent = $('#qrCodeDetailContent');
                const modalElement = document.getElementById('qrCodeDetailModal');
                
                if (!modalElement) {
                    console.error('Modal element not found');
                    return;
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        console.log('AJAX request starting...');
                        modalContent.html(`
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-3">กำลังโหลดข้อมูล...</div>
                            </div>
                        `);
                        
                        // แสดง Modal ด้วย Bootstrap 5
                        const modal = new bootstrap.Modal(modalElement, {
                            backdrop: true,
                            keyboard: true,
                            focus: true
                        });
                        modal.show();
                        console.log('Modal show() called');
                    },
                    success: function(response) {
                        console.log('AJAX Success - Response received');
                        modalContent.html(response);
                        
                        // Initialize Bootstrap components
                        setTimeout(function() {
                            console.log('Initializing modal components...');
                            
                            // Check accordions
                            const accordions = modalContent.find('.accordion');
                            console.log('Found accordions in modal:', accordions.length);
                            accordions.each(function(i) {
                                console.log(`Accordion ${i}:`, this.id, this.className);
                            });
                            
                            // Check accordion headers
                            const headers = modalContent.find('.accordion-header');
                            console.log('Found accordion headers:', headers.length);
                            headers.each(function(i) {
                                const onclick = $(this).attr('onclick');
                                console.log(`Header ${i} onclick:`, onclick);
                            });
                            
                            initializeAccordions();
                            
                            // Initialize other Bootstrap components
                            modalContent.find('[data-bs-toggle="popover"]').each(function() {
                                new bootstrap.Popover(this);
                            });
                            
                            modalContent.find('[data-bs-toggle="tooltip"]').each(function() {
                                new bootstrap.Tooltip(this);
                            });
                            
                            console.log('Modal content loaded and initialized');
                        }, 100);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        modalContent.html(`
                            <div class="alert alert-danger">
                                <h5>เกิดข้อผิดพลาดในการโหลดข้อมูล</h5>
                                <p>Error: ${error}</p>
                                <p>Status: ${status}</p>
                            </div>
                        `);
                    }
                });
            }
            
            // ฟังก์ชันสำหรับโหลดรายละเอียด payment type เฉพาะ
            function loadPaymentDetail(labourId, paymentTypeId) {
                console.log('Loading payment detail for labour:', labourId, 'payment type:', paymentTypeId);
                
                // ปิด popover ก่อน
                $('.payment-pending-btn').popover('hide');
                
                // สร้าง URL สำหรับดูรายละเอียด payment type เฉพาะ
                const url = `/labour/${labourId}/payment-detail/${paymentTypeId}`;
                
                const modalContent = $('#qrCodeDetailContent');
                
                if (!qrModal) {
                    console.error('Modal not initialized');
                    return;
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        console.log('Loading payment detail...');
                        modalContent.html(`
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-3">กำลังโหลดรายละเอียดการชำระเงิน...</div>
                            </div>
                        `);
                        
                        qrModal.show();
                    },
                    success: function(response) {
                        console.log('Payment detail loaded successfully');
                        modalContent.html(response);
                        
                        setTimeout(function() {
                            initializeAccordions();
                            console.log('Payment detail modal initialized');
                        }, 100);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading payment detail:', error);
                        // Fallback: โหลด QR detail ปกติแทน
                        loadQrCodeData(`/labour/${labourId}/qrcode-detail`);
                    }
                });
            }
            
            // เพิ่มให้เป็น global function
            window.loadPaymentDetail = loadPaymentDetail;

            // Handle Select All checkbox
            $('#selectAll').change(function() {
                $('input[name="labour_ids[]"]').prop('checked', this.checked);
                updateSelectedCount();
            });

            // Handle individual checkboxes
            $(document).on('change', 'input[name="labour_ids[]"]', function() {
                updateSelectedCount();
            });

            // Update selected count and button state
            function updateSelectedCount() {
                let selectedCount = $('input[name="labour_ids[]"]:checked').length;
                $('#selectedCount').text(`(${selectedCount} รายการที่เลือก)`);
                $('#printSelectedQRCodes').prop('disabled', selectedCount === 0);
                $('#previewSelectedQRCodes').prop('disabled', selectedCount === 0);
                $('#testSelectedQRCodes').prop('disabled', selectedCount === 0);
            }

            // Enhanced Print QR Codes with preloading and fallback
            $('#printSelectedQRCodes').click(function() {
                let selectedIds = [];
                $('input[name="labour_ids[]"]:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    const $button = $(this);
                    $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> กำลังเตรียม QR codes...');
                    
                    // Collect QR data
                    let qrData = [];
                    $('input[name="labour_ids[]"]:checked').each(function() {
                        let row = $(this).closest('tr');
                        let qrUrl = row.find('.qr-code-link').attr('href');
                        let passportNumber = row.find('td:nth-child(5)').text().trim();
                        
                        qrData.push({
                            url: qrUrl,
                            passport: passportNumber
                        });
                    });

                    // Preload all QR codes with fallback services
                    preloadQRCodesForPrint(qrData).then(function(loadedQRCodes) {
                        console.log(`Successfully loaded ${loadedQRCodes.length} QR codes for printing`);
                        
                        // Create print frame
                        let printFrame = $('<iframe>', {
                            name: 'printQRCodes',
                            class: 'printFrame'
                        }).css('display', 'none').appendTo('body');

                        let printContent = '<html><head><style>' +
                            'table.qr-table { border-collapse: collapse; }' +
                            'table.qr-table td { border: 1.5px dashed #333; width: 180px; height: 220px; padding: 10px; text-align: center; vertical-align: middle; }' +
                            '.qr-info { font-size: 15px; font-weight: bold; margin-top: 4px; text-align: center; }' +
                            '.qr-fallback { font-size: 12px; color: #666; }' +
                            '@media print { body { margin: 0; } td { page-break-inside: avoid; } }' +
                            '</style></head><body><table class="qr-table"><tr>';
                        
                        let colCount = 0;
                        loadedQRCodes.forEach(function(qrCode) {
                            if (qrCode.success) {
                                printContent += `<td><img src="${qrCode.src}" style="max-width: 100px; max-height: 100px;" /><div class="qr-info">รหัสพนักงาน: ${qrCode.passport}</div></td>`;
                            } else {
                                // Fallback for failed QR codes
                                printContent += `<td><div style="width: 100px; height: 100px; border: 2px dashed #ccc; display: flex; align-items: center; justify-content: center; margin: 0 auto;"><div class="qr-fallback">QR Code<br>ไม่สามารถโหลดได้</div></div><div class="qr-info">รหัสพนักงาน: ${qrCode.passport}</div></td>`;
                            }
                            colCount++;
                            if (colCount % 2 === 0) printContent += '</tr><tr>';
                        });
                        
                        printContent += '</tr></table></body></html>';

                        let frameDoc = printFrame[0].contentWindow.document;
                        frameDoc.open();
                        frameDoc.write(printContent);
                        frameDoc.close();

                        // Wait for images to load in print frame, then print
                        setTimeout(function() {
                            printFrame[0].contentWindow.print();
                            setTimeout(function() {
                                printFrame.remove();
                                $button.prop('disabled', false).html('<i class="fas fa-print"></i> พิมพ์ QR Codes ที่เลือก');
                            }, 1000);
                        }, 1000);
                        
                    }).catch(function(error) {
                        console.error('Error loading QR codes for print:', error);
                        alert('เกิดข้อผิดพลาดในการเตรียม QR codes กรุณาลองใหม่');
                        $button.prop('disabled', false).html('<i class="fas fa-print"></i> พิมพ์ QR Codes ที่เลือก');
                    });
                }
            });

            // Preload QR codes for printing with fallback services
            function preloadQRCodesForPrint(qrDataArray) {
                return new Promise(function(resolve) {
                    const qrServices = [
                        'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=',
                        'https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl=',
                        'https://quickchart.io/qr?text=',
                        'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='
                    ];
                    
                    const results = [];
                    let completed = 0;
                    
                    qrDataArray.forEach(function(qrData, index) {
                        let serviceIndex = 0;
                        
                        function tryLoadQR() {
                            if (serviceIndex >= qrServices.length) {
                                // All services failed
                                results[index] = {
                                    success: false,
                                    passport: qrData.passport,
                                    src: null
                                };
                                completed++;
                                if (completed === qrDataArray.length) {
                                    resolve(results);
                                }
                                return;
                            }
                            
                            const service = qrServices[serviceIndex];
                            const fullUrl = service + encodeURIComponent(qrData.passport) + '&t=' + Date.now();
                            
                            const img = new Image();
                            const timeout = setTimeout(function() {
                                serviceIndex++;
                                tryLoadQR();
                            }, 3000); // 3 second timeout per service
                            
                            img.onload = function() {
                                clearTimeout(timeout);
                                results[index] = {
                                    success: true,
                                    passport: qrData.passport,
                                    src: fullUrl
                                };
                                completed++;
                                if (completed === qrDataArray.length) {
                                    resolve(results);
                                }
                            };
                            
                            img.onerror = function() {
                                clearTimeout(timeout);
                                serviceIndex++;
                                setTimeout(tryLoadQR, 200); // Short delay before trying next service
                            };
                            
                            img.src = fullUrl;
                        }
                        
                        // Start loading with a small delay between each QR code
                        setTimeout(tryLoadQR, index * 100);
                    });
                });
            }

            // Preview Selected QR Codes
            $('#previewSelectedQRCodes').click(function() {
                let selectedIds = [];
                $('input[name="labour_ids[]"]:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    const $button = $(this);
                    $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> กำลังเตรียมตัวอย่าง...');
                    
                    // Collect QR data
                    let qrData = [];
                    $('input[name="labour_ids[]"]:checked').each(function() {
                        let row = $(this).closest('tr');
                        let qrUrl = row.find('.qr-code-link').attr('href');
                        let passportNumber = row.find('td:nth-child(5)').text().trim();
                        
                        qrData.push({
                            url: qrUrl,
                            passport: passportNumber
                        });
                    });

                    // Show preview modal
                    showQRPreviewModal(qrData);
                    $button.prop('disabled', false).html('<i class="fas fa-eye"></i> ดูตัวอย่าง QR Codes');
                }
            });

            // Test Selected QR Codes
            $('#testSelectedQRCodes').click(function() {
                let selectedIds = [];
                $('input[name="labour_ids[]"]:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    const $button = $(this);
                    $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> กำลังทดสอบ...');
                    
                    // Collect QR data
                    let qrData = [];
                    $('input[name="labour_ids[]"]:checked').each(function() {
                        let row = $(this).closest('tr');
                        let qrUrl = row.find('.qr-code-link').attr('href');
                        let passportNumber = row.find('td:nth-child(5)').text().trim();
                        
                        qrData.push({
                            url: qrUrl,
                            passport: passportNumber
                        });
                    });

                    // Test QR codes
                    testQRCodesAvailability(qrData).then(function(results) {
                        showQRTestResults(results);
                        $button.prop('disabled', false).html('<i class="fas fa-vial"></i> ทดสอบ QR Codes');
                    });
                }
            });

            // Show QR Preview Modal
            function showQRPreviewModal(qrData) {
                let modalContent = `
                    <div class="modal fade" id="qrPreviewModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">ตัวอย่าง QR Codes ที่เลือก (${qrData.length} รายการ)</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">`;
                
                qrData.forEach(function(data, index) {
                    modalContent += `
                        <div class="col-md-3 mb-3 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${encodeURIComponent(data.passport)}&t=${Date.now()}" 
                                         class="img-fluid mb-2" 
                                         onerror="this.src='https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl=${encodeURIComponent(data.passport)}'" />
                                    <small class="text-muted d-block">รหัส: ${data.passport}</small>
                                </div>
                            </div>
                        </div>`;
                });
                
                modalContent += `
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                    <button type="button" class="btn btn-primary" onclick="$('#printSelectedQRCodes').click(); $('#qrPreviewModal').modal('hide');">
                                        <i class="fas fa-print"></i> พิมพ์เลย
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                // Remove existing modal and add new one
                $('#qrPreviewModal').remove();
                $('body').append(modalContent);
                $('#qrPreviewModal').modal('show');
            }

            // Test QR Codes Availability
            function testQRCodesAvailability(qrData) {
                return new Promise(function(resolve) {
                    const results = [];
                    let completed = 0;
                    
                    qrData.forEach(function(data, index) {
                        const qrServices = [
                            'https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=',
                            'https://chart.googleapis.com/chart?chs=60x60&cht=qr&chl=',
                            'https://quickchart.io/qr?text='
                        ];
                        
                        const serviceResults = [];
                        let serviceCompleted = 0;
                        
                        qrServices.forEach(function(service, serviceIndex) {
                            const url = service + encodeURIComponent(data.passport);
                            const img = new Image();
                            
                            const timeout = setTimeout(function() {
                                serviceResults[serviceIndex] = { success: false, time: 'timeout' };
                                serviceCompleted++;
                                if (serviceCompleted === qrServices.length) {
                                    results[index] = {
                                        passport: data.passport,
                                        services: serviceResults
                                    };
                                    completed++;
                                    if (completed === qrData.length) {
                                        resolve(results);
                                    }
                                }
                            }, 5000);
                            
                            const startTime = Date.now();
                            
                            img.onload = function() {
                                clearTimeout(timeout);
                                serviceResults[serviceIndex] = { 
                                    success: true, 
                                    time: (Date.now() - startTime) + 'ms' 
                                };
                                serviceCompleted++;
                                if (serviceCompleted === qrServices.length) {
                                    results[index] = {
                                        passport: data.passport,
                                        services: serviceResults
                                    };
                                    completed++;
                                    if (completed === qrData.length) {
                                        resolve(results);
                                    }
                                }
                            };
                            
                            img.onerror = function() {
                                clearTimeout(timeout);
                                serviceResults[serviceIndex] = { success: false, time: 'error' };
                                serviceCompleted++;
                                if (serviceCompleted === qrServices.length) {
                                    results[index] = {
                                        passport: data.passport,
                                        services: serviceResults
                                    };
                                    completed++;
                                    if (completed === qrData.length) {
                                        resolve(results);
                                    }
                                }
                            };
                            
                            img.src = url;
                        });
                    });
                });
            }

            // Show QR Test Results
            function showQRTestResults(results) {
                let modalContent = `
                    <div class="modal fade" id="qrTestModal" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">ผลการทดสอบ QR Services</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>รหัสพนักงาน</th>
                                                <th>QR Server API</th>
                                                <th>Google Charts</th>
                                                <th>QuickChart</th>
                                                <th>สถานะ</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                results.forEach(function(result) {
                    const services = result.services;
                    const workingCount = services.filter(s => s.success).length;
                    const statusClass = workingCount > 0 ? 'success' : 'danger';
                    const statusText = workingCount > 0 ? `${workingCount}/3 ใช้งานได้` : 'ทุกบริการล้มเหลว';
                    
                    modalContent += `
                        <tr class="table-${statusClass}">
                            <td>${result.passport}</td>
                            <td>${services[0].success ? '✅ ' + services[0].time : '❌ ' + services[0].time}</td>
                            <td>${services[1].success ? '✅ ' + services[1].time : '❌ ' + services[1].time}</td>
                            <td>${services[2].success ? '✅ ' + services[2].time : '❌ ' + services[2].time}</td>
                            <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        </tr>`;
                });

                modalContent += `
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                // Remove existing modal and add new one
                $('#qrTestModal').remove();
                $('body').append(modalContent);
                $('#qrTestModal').modal('show');
            }

            // Initialize popovers
            $('[data-bs-toggle="popover"]').popover();

            // Hide And Show Mass Update
            $('#show-massupdate').change(function() {
                $('#div-massupdate').toggle(this.checked);
            });

            // QR Code Handler Functions
            window.handleQRLoad = function(img) {
                const labourId = img.getAttribute('data-labour-id');
                const $img = $(img);
                const $loading = $(`#qr-loading-${labourId}`);
                const $fallback = $img.siblings('.qr-fallback');
                const $status = $(`#qr-status-${labourId}`);
                
                console.log(`QR Code loaded successfully for labour ${labourId}`);
                
                $loading.hide();
                $img.show();
                $fallback.hide();
                $status.removeClass('loading error').addClass('success');
                
                setTimeout(() => $status.fadeOut(), 2000);
            };
            
            window.handleQRError = function(img) {
                const labourId = img.getAttribute('data-labour-id');
                const passport = img.getAttribute('data-passport');
                const $img = $(img);
                const $loading = $(`#qr-loading-${labourId}`);
                const $fallback = $img.siblings('.qr-fallback');
                const $status = $(`#qr-status-${labourId}`);
                
                console.warn(`QR Code failed for labour ${labourId}, trying alternative services`);
                
                // Try multiple alternative services in sequence
                const alternatives = [
                    `https://chart.googleapis.com/chart?chs=60x60&cht=qr&chl=${encodeURIComponent(passport)}`,
                    `https://quickchart.io/qr?text=${encodeURIComponent(passport)}&size=60`,
                    `https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${encodeURIComponent(passport)}&ecc=L&format=png&margin=0`
                ];
                
                let attemptIndex = 0;
                
                function tryNextService() {
                    if (attemptIndex < alternatives.length) {
                        const serviceUrl = alternatives[attemptIndex];
                        console.log(`Trying service ${attemptIndex + 1} for labour ${labourId}: ${serviceUrl.substring(0, 50)}...`);
                        
                        $img.off('error').on('error', function() {
                            attemptIndex++;
                            console.warn(`Service ${attemptIndex} failed for labour ${labourId}`);
                            
                            if (attemptIndex < alternatives.length) {
                                setTimeout(tryNextService, 500); // Wait 500ms before trying next service
                            } else {
                                console.error(`All QR services failed for labour ${labourId}`);
                                $loading.hide();
                                $img.hide();
                                $fallback.show();
                                $status.removeClass('loading success').addClass('error');
                            }
                        }).attr('src', serviceUrl);
                        
                        attemptIndex++;
                    }
                }
                
                tryNextService();
            };
            
            window.retryQRCode = function(fallbackElement) {
                const $fallback = $(fallbackElement);
                const $img = $fallback.siblings('.qr-code-img');
                const labourId = $img.attr('data-labour-id');
                const passport = $img.attr('data-passport');
                const $loading = $fallback.siblings('.qr-loading');
                const $status = $(`#qr-status-${labourId}`);
                
                console.log(`Retrying QR Code for labour ${labourId}`);
                
                $fallback.hide();
                $loading.show();
                $status.removeClass('error success').addClass('loading');
                
                // Reset and retry with original service + timestamp
                const originalUrl = `https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${encodeURIComponent(passport)}&t=${Date.now()}`;
                $img.attr('src', originalUrl);
            };

            // QR Code Error Handling and Retry
            function initializeQRCodes() {
                console.log('Initializing QR Codes...');
                const $allImages = $('.qr-code-img');
                console.log(`Found ${$allImages.length} QR codes to load`);
                
                // Load all QR codes immediately but with staggered delays
                $allImages.each(function(index) {
                    const $img = $(this);
                    const labourId = $img.attr('data-labour-id');
                    const $loading = $(`#qr-loading-${labourId}`);
                    const delay = index * 100; // 100ms between each QR code
                    
                    console.log(`Scheduling QR Code ${index + 1}/${$allImages.length} for labour ${labourId} with ${delay}ms delay`);
                    
                    setTimeout(() => {
                        console.log(`Loading QR Code for labour ${labourId}`);
                        const currentSrc = $img.attr('src');
                        // Force trigger load by setting src again
                        $img.attr('src', currentSrc + (currentSrc.includes('?') ? '&' : '?') + 'cache=' + Date.now());
                        
                        // Timeout สำหรับการโหลด QR Code นี้
                        setTimeout(function() {
                            if ($img[0] && !$img[0].complete && $img.is(':hidden')) {
                                console.warn(`QR Code ${labourId} taking too long to load, triggering error`);
                                $img.trigger('error');
                            }
                        }, 10000); // 10 วินาที timeout
                        
                    }, delay);
                });
                
                // Check for any remaining unloaded QR codes after 30 seconds
                setTimeout(() => {
                    const $unloadedImages = $('.qr-code-img:hidden');
                    if ($unloadedImages.length > 0) {
                        console.warn(`${$unloadedImages.length} QR codes still not loaded, forcing fallback`);
                        $unloadedImages.each(function() {
                            const labourId = $(this).attr('data-labour-id');
                            console.log(`Forcing fallback for labour ${labourId}`);
                            $(this).trigger('error');
                        });
                    } else {
                        console.log('All QR codes loaded successfully!');
                    }
                }, 30000);
            }
            
            // เรียกใช้งานหลังจากโหลดหน้าเสร็จ
            initializeQRCodes();
            
            // Force load all QR codes (for debugging and fixing missing QRs)
            window.forceLoadAllQRCodes = function() {
                console.log('Force loading ALL QR codes...');
                const $allImages = $('.qr-code-img');
                
                $allImages.each(function(index) {
                    const $img = $(this);
                    const labourId = $img.attr('data-labour-id');
                    const passport = $img.attr('data-passport');
                    const $loading = $(`#qr-loading-${labourId}`);
                    const $fallback = $img.siblings('.qr-fallback');
                    const $status = $(`#qr-status-${labourId}`);
                    
                    console.log(`Force loading QR ${index + 1}: Labour ${labourId}`);
                    
                    // Reset all states
                    $fallback.hide();
                    $loading.show();
                    $img.hide();
                    if ($status.length) {
                        $status.removeClass('success error').addClass('loading');
                    }
                    
                    // Force reload with cache busting
                    const timestamp = Date.now() + index; // Unique timestamp for each
                    const newSrc = `https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${encodeURIComponent(passport)}&t=${timestamp}`;
                    
                    setTimeout(() => {
                        $img.attr('src', newSrc);
                    }, index * 50); // 50ms delay between each
                });
            };
            
            // Retry only failed QR codes
            window.retryFailedQRCodes = function() {
                console.log('Retrying failed QR codes...');
                const $failedImages = $('.qr-fallback:visible').siblings('.qr-code-img');
                
                console.log(`Found ${$failedImages.length} failed QR codes`);
                
                $failedImages.each(function(index) {
                    const $img = $(this);
                    const labourId = $img.attr('data-labour-id');
                    const $fallback = $img.siblings('.qr-fallback');
                    
                    console.log(`Retrying QR for labour ${labourId}`);
                    if ($fallback.length) {
                        retryQRCode($fallback[0]);
                    }
                });
            };
            
            // Check QR code loading status
            window.checkQRStatus = function() {
                const $allImages = $('.qr-code-img');
                const $visibleImages = $('.qr-code-img:visible');
                const $failedImages = $('.qr-fallback:visible');
                const $loadingImages = $('.qr-loading:visible');
                
                console.log('QR Code Status:');
                console.log(`Total QR codes: ${$allImages.length}`);
                console.log(`Successfully loaded: ${$visibleImages.length}`);
                console.log(`Failed (showing fallback): ${$failedImages.length}`);
                console.log(`Still loading: ${$loadingImages.length}`);
                
                return {
                    total: $allImages.length,
                    loaded: $visibleImages.length,
                    failed: $failedImages.length,
                    loading: $loadingImages.length
                };
            };
            
            // Test QR Code generation
            window.testQRCode = function(data = 'TEST123456') {
                const testUrls = [
                    `https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${encodeURIComponent(data)}`,
                    `https://chart.googleapis.com/chart?chs=60x60&cht=qr&chl=${encodeURIComponent(data)}`,
                    `https://quickchart.io/qr?text=${encodeURIComponent(data)}&size=60`
                ];
                
                testUrls.forEach((url, index) => {
                    const img = new Image();
                    img.onload = () => console.log(`QR Service ${index + 1} working: ${url}`);
                    img.onerror = () => console.warn(`QR Service ${index + 1} failed: ${url}`);
                    img.src = url;
                });
            };
            
            // Monitor page changes for pagination
            function setupPaginationObserver() {
                // Observer for new content loaded via pagination
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList') {
                            const addedNodes = Array.from(mutation.addedNodes);
                            const hasNewQRCodes = addedNodes.some(node => 
                                node.nodeType === 1 && 
                                (node.classList?.contains('qr-code-img') || 
                                 node.querySelector?.('.qr-code-img'))
                            );
                            
                            if (hasNewQRCodes) {
                                console.log('New QR codes detected from pagination, initializing...');
                                setTimeout(initializeQRCodes, 100);
                            }
                        }
                    });
                });
                
                const tableBody = document.querySelector('tbody');
                if (tableBody) {
                    observer.observe(tableBody, {
                        childList: true,
                        subtree: true
                    });
                    console.log('Pagination observer setup for table body');
                }
            }
            
            // Setup pagination click handlers
            function setupPaginationHandlers() {
                $(document).on('click', '.pagination a', function(e) {
                    console.log('Pagination link clicked, will reinitialize QR codes after page load');
                    
                    // Multiple retry attempts with increasing delays
                    const retryDelays = [300, 800, 1500, 3000];
                    
                    retryDelays.forEach((delay, index) => {
                        setTimeout(function() {
                            console.log(`QR retry attempt ${index + 1} after pagination (delay: ${delay}ms)`);
                            const $newQRCodes = $('.qr-code-img');
                            if ($newQRCodes.length > 0) {
                                initializeQRCodes();
                            }
                        }, delay);
                    });
                });
                
                console.log('Pagination handlers setup');
            }
            
            // Detect page visibility changes and refresh QR codes
            function setupVisibilityHandler() {
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        console.log('Page became visible, checking QR codes...');
                        setTimeout(function() {
                            const status = checkQRStatus();
                            if (status.failed > 0) {
                                console.log(`Found ${status.failed} failed QR codes, retrying...`);
                                retryFailedQRCodes();
                            }
                        }, 1000);
                    }
                });
                
                console.log('Visibility change handler setup');
            }
            
            // Auto-retry mechanism for failed QR codes
            function setupAutoRetry() {
                setInterval(function() {
                    const $failedImages = $('.qr-fallback:visible');
                    if ($failedImages.length > 0) {
                        console.log(`Auto-retrying ${$failedImages.length} failed QR codes...`);
                        retryFailedQRCodes();
                    }
                }, 10000); // Retry every 10 seconds
                
                console.log('Auto-retry mechanism setup (every 10 seconds)');
            }
            
            // Initialize all systems
            setupPaginationObserver();
            setupPaginationHandlers();
            setupVisibilityHandler();
            setupAutoRetry();
            
            console.log('QR Code initialization complete.');
            console.log('Available commands:');
            console.log('  - checkQRStatus() : ตรวจสอบสถานะ QR codes');
            console.log('  - forceLoadAllQRCodes() : บังคับโหลด QR codes ทั้งหมดใหม่');
            console.log('  - retryFailedQRCodes() : ลองโหลด QR codes ที่ล้มเหลวใหม่');
            console.log('  - testQRCode() : ทดสอบ QR services');
            console.log('Initialization complete');
        });
    </script>
@endsection
