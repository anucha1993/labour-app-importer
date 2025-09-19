@extends('layouts.main_layout')

@section('content')
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
        <button id="printSelectedQRCodes" class="btn btn-primary" disabled>
            พิมพ์ QR Code ที่เลือก
        </button>
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
                <th>เลขที่วีซ่า</th>
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
                    <td>{{$item->labour_visa_number}}</td>

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
                            <a href="{{ route('labour.qrcodeDetail', $item->labour_id) }}" class="qr-code-link">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode(route('labour.qrcodeDetail', $item->labour_id)) }}" alt="QR" width="60" height="60" />
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
            }

            // Handle Print QR Codes button
            $('#printSelectedQRCodes').click(function() {
                let selectedIds = [];
                $('input[name="labour_ids[]"]:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    let printFrame = $('<iframe>', {
                        name: 'printQRCodes',
                        class: 'printFrame'
                    }).css('display', 'none').appendTo('body');

                    let printContent = '<html><head><style>' +
                        '.qr-container { display: inline-block; text-align: center; margin: 10px; padding: 10px; border: 1px solid #ddd; }' +
                        '.qr-container img { margin-bottom: 5px; }' +
                        '.qr-info { font-size: 14px; }' +
                        '@media print { body { margin: 0; } .qr-container { page-break-inside: avoid; } }' +
                        '</style></head><body>';

                    $('input[name="labour_ids[]"]:checked').each(function() {
                        let row = $(this).closest('tr');
                        let qrUrl = row.find('.qr-code-link').attr('href');
                        let passportNumber = row.find('td:nth-child(4)').text();
                        
                        printContent += '<div class="qr-container">' +
                            '<img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' + encodeURIComponent(qrUrl) + '" />' +
                            '<div class="qr-info">Passport: ' + passportNumber + '</div>' +
                            '</div>';
                    });

                    printContent += '</body></html>';

                    let frameDoc = printFrame[0].contentWindow.document;
                    frameDoc.open();
                    frameDoc.write(printContent);
                    frameDoc.close();

                    setTimeout(function() {
                        printFrame[0].contentWindow.print();
                        setTimeout(function() {
                            printFrame.remove();
                        }, 1000);
                    }, 500);
                }
            });

            // Initialize popovers
            $('[data-bs-toggle="popover"]').popover();

            // Hide And Show Mass Update
            $('#show-massupdate').change(function() {
                $('#div-massupdate').toggle(this.checked);
            });
            
            console.log('Initialization complete');
        });
    </script>
@endsection
