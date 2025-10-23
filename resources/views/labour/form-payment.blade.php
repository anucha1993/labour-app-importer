@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('labour.update', $labour->labour_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
        <div class="col-md-12">
            <h3>ข้อมูลส่วนตัว</h3>
             <a href="{{ route('labour.qrcodeDetail', $labour->labour_id) }}" target="_blank" class="mb-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode(route('labour.qrcodeDetail', $labour->labour_id)) }}" alt="QR" width="100" height="100" />
                            </a>
            <div class="border-top border-primary mt-2">
                <div class="card-body">
                   
                </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <label for="">คำนำหน้า :</label>
                    <select name="labour_prefix" class="form-select" disabled>
                        <option selected value="{{ $labour->labour_prefix }}">{{ $labour->labour_prefix }}</option>
                        <option disabled></option>
                        <option value="Mr">Mr.</option>
                        <option value="Ms">Ms.</option>
                        <option value="Mrs">Mrs.</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="">ชื่อ-สกุล :</label>
                    <input type="text" name="labour_fullname" placeholder="Fullname" class="form-control" disabled
                        value="{{ $labour->labour_fullname }}" disabled>
                </div>
                <div class="col-md-3">
                    <label for="">เพศ</label>
                    <select name="labour_sex" class="form-control form-select" id="sex" disabled
                        placeholder="Fullname">
                        @php
                            switch ($labour->labour_sex) {
                                case 'male':
                                    echo '<option value="male">ชาย</option>';
                                    break;

                                case 'female':
                                    echo '<option value="female">หญิง</option>';
                                    break;

                                default:
                                    echo '  <option disabled ></option>';
                                    break;
                            }
                        @endphp
                        <option disabled></option>
                        <option value="male">ชาย</option>
                        <option value="female">หญิง</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="nationality">สัญชาติ :</label>
                    <select name="labour_nationality" class="form-control form-select" id="nationality" disabled>
                        <option selected value="{{ $labour->labour_nationality }}">{{ $labour->name_th }}</option>
                        <option disabled></option>
                        @foreach ($nationality as $item)date
                            <option value="{{ $item->code }}">{{ $item->name_th }}</option>
                        @endforeach
                        <option value=""></option>
                    </select>
                </div>
            </div>
            


            <!-- Section ข้อมูลการชำระเงิน -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h4 class="card-title">ข้อมูลการชำระเงิน</h4>
                    <div class="border-top border-primary">
                        <div class="card">
                            <div class="card-body">
                                <div id="paymentTypesList">
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" id="addPaymentType">
                                        <i class="fas fa-plus"></i> เพิ่มประเภทการหัก
                                    </button>
                                    <button type="button" class="btn btn-success" id="savePaymentTypes">
                                        <i class="fas fa-save"></i> บันทึกข้อมูลการชำระเงิน
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
    </form>

    <!-- แสดงรายการที่ชำระเงินครบแล้ว -->
    <div class="row mt-4 " style="padding: 5px">
        <div class="col-md-12">
            <h4 class="card-title">รายการที่ชำระเงินครบแล้ว</h4>
            <div id="completedPaymentsList">
            </div>
        </div>
    </div>

    <!-- Modal สำหรับเพิ่มประวัติการชำระ -->
    <div class="modal fade" id="addPaymentHistoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มประวัติการชำระ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="paymentHistoryForm" enctype="multipart/form-data">
                        <input type="hidden" id="modalPaymentTypeId">
                        <div class="form-group">
                            <label>จำนวนเงิน</label>
                            <input type="number" step="0.01" class="form-control" id="modalAmount" required>
                        </div>
                        <div class="form-group">
                            <label>วันที่ชำระ</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="modalPaymentDate" required readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="setCurrentDateTime">
                                        <i class="fas fa-clock"></i> ตั้งเป็นเวลาปัจจุบัน
                                    </button>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                // Initialize datetimepicker
                                $('#modalPaymentDate').datetimepicker({
                                    format: 'DD/MM/YYYY HH:mm:ss',
                                    sideBySide: true,
                                    icons: {
                                        time: 'fas fa-clock',
                                        date: 'fas fa-calendar',
                                        up: 'fas fa-chevron-up',
                                        down: 'fas fa-chevron-down',
                                        previous: 'fas fa-chevron-left',
                                        next: 'fas fa-chevron-right',
                                        today: 'fas fa-calendar-check',
                                        clear: 'fas fa-trash',
                                        close: 'fas fa-times'
                                    }
                                });

                                // Set initial value to current date and time
                                $('#modalPaymentDate').val(moment().format('DD/MM/YYYY HH:mm:ss'));

                                // Set current date time button
                                $('#setCurrentDateTime').click(function() {
                                    $('#modalPaymentDate').val(moment().format('DD/MM/YYYY HH:mm:ss'));
                                });
                            });
                        </script>
                        <div class="form-group">
                            <label>หลักฐานการชำระ</label>
                            <input  type="file" class="form-control" id="modalProofFile" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">รองรับไฟล์ PDF, JPG, JPEG, PNG</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCloseModal">ปิด</button>
    <script>
    // สำรอง: ถ้า data-dismiss ไม่ทำงาน ให้ force ปิด modal ด้วย JS
    $(document).on('click', '#btnCloseModal, .close[data-dismiss="modal"]', function() {
        $('#addPaymentHistoryModal').modal('hide');
    });
    </script>
                    <button type="button" class="btn btn-primary" id="savePaymentHistory">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // กำหนดค่า labour_id สำหรับใช้ใน JavaScript
        const labourId = {{ $labour->labour_id }};

        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle toggle history button
        $(document).on('click', '.toggle-history', function() {
            const icon = $(this).find('.fa-chevron-down, .fa-chevron-up');
            icon.toggleClass('fa-chevron-down fa-chevron-up');
            
            if (icon.hasClass('fa-chevron-up')) {
                $(this).html(`
                    <i class="fas fa-history"></i> ซ่อนประวัติการชำระเงิน
                    <i class="fas fa-chevron-up"></i>
                `);
            } else {
                $(this).html(`
                    <i class="fas fa-history"></i> แสดงประวัติการชำระเงิน
                    <i class="fas fa-chevron-down"></i>
                `);
            }
        });

            // Function to generate completed payment HTML
        function generateCompletedPaymentHtml(completedPayments) {
            if (completedPayments.length === 0) return '';
            
            let html = `
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle"></i> ประวัติรายการที่ชำระครบแล้ว</h5>
                    </div>
                    <div class="card-body">
            `;

            completedPayments.forEach(type => {
                const totalPaid = type.histories.reduce((sum, history) => sum + parseFloat(history.amount), 0);
                
                html += `
                    <div class="completed-payment-item mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>ชื่อประเภท:</strong> ${type.payment_name}
                            </div>
                            <div class="col-md-4">
                                <strong>จำนวนเงิน:</strong> ${parseFloat(type.total_amount).toLocaleString()} บาท
                            </div>
                            <div class="col-md-4">
                                <strong>สถานะ:</strong> <span class="badge badge-success">ชำระครบแล้ว</span>
                            </div>
                        </div>
                        
                        <button class="btn btn-link toggle-history mt-2" type="button" data-toggle="collapse" 
                                data-target="#completed-history-${type.payment_type_id}">
                            <i class="fas fa-history"></i> แสดงประวัติการชำระเงิน
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div class="collapse" id="completed-history-${type.payment_type_id}">
                            <div class="table-responsive mt-2">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>วันที่ชำระ</th>
                                            <th class="text-right">จำนวนเงิน</th>
                                            <th class="text-center">หลักฐาน</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${generateHistoryRows(type.histories, false)}
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-success">
                                            <th>รวม</th>
                                            <th class="text-right">${totalPaid.toLocaleString()} บาท</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div></div>';
            return html;
        }

        // Function to generate payment type HTML for incomplete payments
        function generatePaymentTypeHtml(type) {
            // คำนวณยอดรวมที่ชำระแล้ว
            const totalPaid = type.histories.reduce((sum, history) => sum + parseFloat(history.amount), 0);
            const remainingAmount = parseFloat(type.total_amount) - totalPaid;            const historyRows = type.histories.map(history => {
                console.log('Raw payment date:', history.payment_date); // Debug line
                // กำหนด timezone เป็น Asia/Bangkok และจัดรูปแบบวันที่
                const formattedDate = moment(history.payment_date)
                    .tz('Asia/Bangkok')
                    .format('DD/MM/YYYY HH:mm:ss');
                
                return `
                    <tr>
                        <td>${formattedDate}</td>
                        <td class="text-right">${parseFloat(history.amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td class="text-center">
                            ${history.proof_file ? 
                                `<a href="/storage/payment_proofs/${history.proof_file}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-file"></i> ดูหลักฐาน
                                </a>` : 'N/A'
                            }
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm delete-payment-history" data-id="${history.payment_history_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');

            // เก็บยอดที่ชำระแล้วไว้ใน data attribute
            const historyTable = type.histories.length > 0 ? `
                <div class="mt-3" data-paid-amount="${totalPaid}">
                    <div class="mb-2">
                        <small class="text-muted">
                            ยอดชำระแล้ว ${totalPaid.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} บาท 
                            จากทั้งหมด ${parseFloat(type.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} บาท
                            (คงเหลือ ${remainingAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})} บาท)
                        </small>
                    </div>
                    <button class="btn btn-link toggle-history" type="button" data-toggle="collapse" data-target="#history-${type.payment_type_id}">
                        <i class="fas fa-history"></i> แสดงประวัติการชำระเงิน
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="collapse" id="history-${type.payment_type_id}">
                        <div class="payment-histories card card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>วันที่ชำระ</th>
                                            <th class="text-right">จำนวนเงิน</th>
                                            <th class="text-center">หลักฐาน</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${historyRows}
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-info">
                                            <th>รวม</th>
                                            <th class="text-right">${totalPaid.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            ` : '';

            return `
                <div class="payment-type-item mb-4" data-id="${type.payment_type_id}">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>ประเภทการหัก</label>
                                        <div class="input-group">
                                            <select class="form-control payment-name" required disabled>
                                                <option value="">เลือกประเภทการหัก</option>
                                                <option value="ต่อรายงานตัว 90 วัน" ${type.payment_name === 'ต่อรายงานตัว 90 วัน' ? 'selected' : ''}>ต่อรายงานตัว 90 วัน</option>
                                                <option value="ต่อใบอนุญาตทำงาน" ${type.payment_name === 'ต่อใบอนุญาตทำงาน' ? 'selected' : ''}>ต่อใบอนุญาตทำงาน</option>
                                                <option value="ต่อวีซ่า" ${type.payment_name === 'ต่อวีซ่า' ? 'selected' : ''}>ต่อวีซ่า</option>
                                                <option value="ต่ออายุหนังสือเดินทาง" ${type.payment_name === 'ต่ออายุหนังสือเดินทาง' ? 'selected' : ''}>ต่ออายุหนังสือเดินทาง</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-warning btn-sm toggle-edit" data-field="payment-name" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>จำนวนเงินที่หัก</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control total-amount" value="${type.total_amount}" required readonly>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-warning btn-sm toggle-edit" data-field="total-amount" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>ประเภทการหัก</label>
                                        <div class="input-group">
                                            <select class="form-control deduction-type" disabled>
                                                <option value="salary" ${type.deduction_type === 'salary' ? 'selected' : ''}>หักจากเงินเดือน</option>
                                                <option value="self_paid" ${type.deduction_type === 'self_paid' ? 'selected' : ''}>แรงงานจ่ายเองทั้งหมด</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-warning btn-sm toggle-edit" data-field="deduction-type" title="แก้ไข">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>สถานะการหัก</label>
                                        <input type="text" class="form-control" value="${type.status}" readonly>
                                        <input type="hidden" class="status-field" value="${type.status}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>หมายเหตุ</label>
                                        <input type="text" class="form-control note" value="${type.note || ''}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm delete-payment-type mt-4">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    ${type.status !== 'completed' ? `
                                        <button type="button" class="btn btn-success btn-sm add-payment-history mt-4" data-payment-type="${type.payment_type_id}">
                                            <i class="fas fa-plus"></i> ชำระเงิน
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                            ${historyTable}
                        </div>
                    </div>
                </div>
            `;

        }

        // Function to generate history rows
        function generateHistoryRows(histories, showDeleteButton = true) {
            return histories.map(history => {
                const formattedDate = moment(history.payment_date)
                    .tz('Asia/Bangkok')
                    .format('DD/MM/YYYY HH:mm:ss');
                
                return `
                    <tr>
                        <td>${formattedDate}</td>
                        <td class="text-right">${parseFloat(history.amount).toLocaleString()}</td>
                        <td class="text-center">
                            ${history.proof_file ? 
                                `<a href="/storage/payment_proofs/${history.proof_file}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-file"></i> ดูหลักฐาน
                                </a>` : 'N/A'
                            }
                            ${showDeleteButton ? `
                                <button type="button" class="btn btn-danger btn-sm delete-payment-history" data-id="${history.payment_history_id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // แสดงข้อมูลการชำระเงินที่มีอยู่
        @if(isset($paymentTypes))
            const existingPaymentTypes = [
                @foreach($paymentTypes as $type)
                    {
                        payment_type_id: '{{ $type->payment_type_id }}',
                        payment_name: '{{ $type->payment_name }}',
                        total_amount: '{{ $type->total_amount }}',
                        deduction_type: '{{ $type->deduction_type }}',
                        status: '{{ $type->status }}',
                        note: '{{ $type->note }}',
                        histories: @json($type->histories)
                    },
                @endforeach
            ];

            // แยกรายการที่ชำระครบและยังไม่ครบ
            const completedPayments = existingPaymentTypes.filter(type => type.status === 'completed');
            const incompletePayments = existingPaymentTypes.filter(type => type.status !== 'completed');

            // แสดงรายการที่ยังไม่ครบในส่วนของฟอร์มแก้ไข
            const incompleteHtml = incompletePayments.map(type => generatePaymentTypeHtml(type)).join('');
            $('#paymentTypesList').html(incompleteHtml);

            // แสดงรายการที่ชำระครบแล้วในส่วนแยกต่างหาก
            const completedHtml = generateCompletedPaymentHtml(completedPayments);
            $('#completedPaymentsList').html(completedPayments.length ? completedHtml : '<div class="alert alert-info">ไม่มีรายการที่ชำระครบ</div>');
        @endif

        // บันทึกข้อมูลการชำระเงิน
$('#savePaymentTypes').click(function() {
    const promises = [];
    
    $('.payment-type-item').each(function() {
        const item = $(this);
        const paymentTypeId = item.data('id');
        const paymentName = item.find('.payment-name').val();
        const totalAmount = item.find('.total-amount').val();
        
        if (!paymentName || !totalAmount) {
            alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            return false;
        }

        // สร้าง request data
        const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            payment_name: paymentName,
            total_amount: totalAmount,
            deduction_type: item.find('.deduction-type').val(),
            note: item.find('.note').val()
        };

        // ถ้าเป็นรายการใหม่
        if (!paymentTypeId) {
            data.labour_id = labourId;
            promises.push(
                $.ajax({
                    url: '/labour/payment-type',
                    method: 'POST',
                    data: data
                })
            );
        } 
        // ถ้าเป็นการแก้ไขรายการเดิม
        else {
            promises.push(
                $.ajax({
                    url: `/labour/payment-type/${paymentTypeId}`,
                    method: 'POST',
                    data: data
                })
            );
        }
    });

    // รอให้ทุก request เสร็จสิ้น
    Promise.all(promises)
        .then(() => {
            alert('บันทึกข้อมูลเรียบร้อยแล้ว');
            location.reload();
        })
        .catch(() => {
            alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
        });
});

        // Toggle Edit Fields
        $(document).on('click', '.toggle-edit', function() {
            const btn = $(this);
            const fieldType = btn.data('field');
            const inputGroup = btn.closest('.input-group');
            let input;
            
            // หา input element ตาม field type
            switch(fieldType) {
                case 'payment-name':
                    input = inputGroup.find('.payment-name');
                    break;
                case 'total-amount':
                    input = inputGroup.find('.total-amount');
                    break;
                case 'deduction-type':
                    input = inputGroup.find('.deduction-type');
                    break;
            }

            const isDisabled = input.is('select') ? input.prop('disabled') : input.prop('readonly');
            
            if (isDisabled) {
                // เปิดการแก้ไข
                if (input.is('select')) {
                    input.prop('disabled', false);
                } else {
                    input.prop('readonly', false);
                }
                input.focus();
                btn.removeClass('btn-warning').addClass('btn-success')
                   .attr('title', 'บันทึก')
                   .html('<i class="fas fa-save"></i>');
            } else {
                // ตรวจสอบความถูกต้องของข้อมูล
                let isValid = true;
                let errorMessage = '';

                if (fieldType === 'payment-name' && !input.val().trim()) {
                    isValid = false;
                    errorMessage = 'กรุณาเลือกประเภทการหัก';
                } else if (fieldType === 'total-amount') {
                    const amount = parseFloat(input.val());
                    if (isNaN(amount) || amount <= 0) {
                        isValid = false;
                        errorMessage = 'กรุณากรอกจำนวนเงินที่ถูกต้อง';
                    }
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด',
                        text: errorMessage
                    });
                    return;
                }

                // บันทึกการแก้ไข
                if (input.is('select')) {
                    input.prop('disabled', true);
                } else {
                    input.prop('readonly', true);
                }
                btn.removeClass('btn-success').addClass('btn-warning')
                   .attr('title', 'แก้ไข')
                   .html('<i class="fas fa-edit"></i>');
            }
        });

        // Payment Type Management
        $('#addPaymentType').click(function() {
            const newPaymentType = {
                payment_type_id: '',
                payment_name: '',
                total_amount: '',
                deduction_type: 'salary',
                status: 'pending',
                note: '',
                histories: []
            };
            const paymentTypeHtml = generatePaymentTypeHtml(newPaymentType);
            $('#paymentTypesList').append(paymentTypeHtml);
            
            // เปิดให้แก้ไขทุกฟิลด์สำหรับรายการใหม่
            const lastItem = $('#paymentTypesList .payment-type-item:last');
            
            // เปิดการแก้ไขชื่อ (select)
            const nameSelect = lastItem.find('.payment-name');
            nameSelect.prop('disabled', false);
            
            // เปิดการแก้ไขจำนวนเงิน
            const amountInput = lastItem.find('.total-amount');
            amountInput.prop('readonly', false);
            
            // เปิดการแก้ไขประเภทการหัก
            const typeSelect = lastItem.find('.deduction-type');
            typeSelect.prop('disabled', false);
            
            // เปลี่ยนปุ่มทั้งหมดเป็นปุ่มบันทึก
            lastItem.find('.toggle-edit').each(function() {
                $(this)
                    .removeClass('btn-warning').addClass('btn-success')
                    .attr('title', 'บันทึก')
                    .html('<i class="fas fa-save"></i>');
            });
            
            // Focus ที่ select ประเภทการหัก
            nameSelect.focus();
        });

        // Delete Payment Type
        $(document).on('click', '.delete-payment-type', function() {
            const item = $(this).closest('.payment-type-item');
            const id = item.data('id');
            
            if (id) {
                if (confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')) {
                    $.ajax({
                        url: '/labour/payment-type/' + id,
                        method: 'DELETE',
                        success: function() {
                            item.remove();
                        }
                    });
                }
            } else {
                item.remove();
            }
        });

        // Add Payment History Modal
        $(document).on('click', '.add-payment-history', function() {
            console.log('Add payment history button clicked');
            
            const paymentTypeItem = $(this).closest('.payment-type-item');
            const status = paymentTypeItem.find('.status-field').val();
            
            console.log('Payment type status:', status);
            
            if (status === 'completed') {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่สามารถเพิ่มการชำระเงินได้',
                    text: 'ประเภทการชำระนี้ชำระครบแล้ว'
                });
                return;
            }
            
            const paymentTypeId = $(this).data('payment-type');
            console.log('Payment type ID:', paymentTypeId);
            
            if (!paymentTypeId) {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'ไม่พบ ID ของประเภทการชำระเงิน'
                });
                return;
            }
            
            // Clear previous form data
            $('#modalAmount').val('');
            $('#modalProofFile').val('');
            
            // Set payment type ID
            $('#modalPaymentTypeId').val(paymentTypeId);
            
            // Set current date/time
            const now = moment().format('DD/MM/YYYY HH:mm:ss');
            $('#modalPaymentDate').val(now);
            
            console.log('Opening modal with:', {
                paymentTypeId: paymentTypeId,
                currentDateTime: now
            });
            
            $('#addPaymentHistoryModal').modal('show');
        });

        // Save Payment History
        $('#savePaymentHistory').click(function() {
            console.log('Save payment history button clicked');
            
            // Check if SweetAlert2 is loaded
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded');
                alert('ระบบแจ้งเตือนมีปัญหา กรุณารีเฟรชหน้าเว็บ');
                return;
            }
            
            // Check if modal elements exist
            if (!$('#modalPaymentTypeId').length || !$('#modalAmount').length || !$('#modalPaymentDate').length) {
                console.error('Modal elements not found');
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'ไม่พบองค์ประกอบของฟอร์ม กรุณารีเฟรชหน้าเว็บ'
                });
                return;
            }

            const paymentTypeId = $('#modalPaymentTypeId').val();
            const newAmount = parseFloat($('#modalAmount').val() || 0);
            const paymentDate = $('#modalPaymentDate').val();
            
            // Client-side validation
            console.log('Validating inputs:', {
                paymentTypeId: paymentTypeId,
                newAmount: newAmount,
                paymentDate: paymentDate
            });
            
            if (!paymentTypeId) {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'กรุณาเลือกประเภทการชำระเงิน'
                });
                return;
            }
            
            if (!newAmount || newAmount <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'กรุณาระบุจำนวนเงินที่ถูกต้อง'
                });
                return;
            }
            
            if (!paymentDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'กรุณาระบุวันที่ชำระเงิน'
                });
                return;
            }
            
            // หา payment type item จาก ID และตรวจสอบยอดเงิน
            const paymentTypeItem = $(`.payment-type-item[data-id="${paymentTypeId}"]`);
            const totalAmount = parseFloat(paymentTypeItem.find('.total-amount').val());
            const paidAmount = parseFloat(paymentTypeItem.data('paid-amount') || 0);
            const remainingAmount = totalAmount - paidAmount;
            
            // ตรวจสอบว่าจำนวนเงินที่จะชำระรวมกับที่ชำระไปแล้วไม่เกินยอดที่ต้องชำระ
            if (paidAmount + newAmount > totalAmount) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่สามารถชำระเงินเกินยอดที่กำหนด',
                    html: `ยอดคงเหลือที่ต้องชำระ: <b>${remainingAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</b> บาท<br>
                          ยอดที่ต้องการชำระ: <b>${newAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</b> บาท<br>
                          <small class="text-danger">* จำนวนเงินที่ชำระต้องไม่เกินยอดคงเหลือ</small>`
                });
                return;
            }

            // ตรวจสอบรูปแบบวันที่
            if (!moment(paymentDate, 'DD/MM/YYYY HH:mm:ss', true).isValid()) {
                console.error('Invalid date format:', paymentDate);
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'รูปแบบวันที่ไม่ถูกต้อง กรุณาเลือกวันที่ใหม่'
                });
                return;
            }

            // Prepare form data
            // Convert date to ISO 8601 format (Y-m-d\TH:i) as required by server
            const paymentDateTime = moment(paymentDate, 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DDTHH:mm');
            
            // ตรวจสอบ CSRF token
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!csrfToken) {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'ไม่พบ CSRF Token กรุณารีเฟรชหน้าเว็บ'
                });
                return;
            }
            
            console.log('Preparing form data:', {
                paymentTypeId: paymentTypeId,
                amount: newAmount,
                paymentDate: paymentDateTime,
                csrfToken: csrfToken ? 'found' : 'not found'
            });
            
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('payment_type_id', paymentTypeId);
            formData.append('amount', newAmount);
            formData.append('payment_date', paymentDateTime);
            
            const proofFile = $('#modalProofFile')[0].files[0];
            if (proofFile) {
                console.log('Proof file:', {
                    name: proofFile.name,
                    size: proofFile.size,
                    type: proofFile.type
                });
                formData.append('proof_file', proofFile);
            }
            
            // Debug form data
            console.log('FormData entries:');
            for (let pair of formData.entries()) {
                console.log(pair[0], pair[1]);
            }

            // Show loading state
            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                text: 'กรุณารอสักครู่',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit payment
            const ajaxConfig = {
                url: '/labour/payment-history',
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };
            
            // ถ้ามีไฟล์ ใช้ FormData, ถ้าไม่มีใช้ JSON
            if (proofFile) {
                ajaxConfig.data = formData;
                ajaxConfig.processData = false;
                ajaxConfig.contentType = false;
            } else {
                // ส่งเป็น JSON ถ้าไม่มีไฟล์
                ajaxConfig.data = {
                    _token: csrfToken,
                    payment_type_id: paymentTypeId,
                    amount: newAmount,
                    payment_date: paymentDateTime
                };
                ajaxConfig.dataType = 'json';
            }
            
            console.log('AJAX Config:', ajaxConfig);
            
            $.ajax(ajaxConfig).done(function(response) {
                console.log('Payment saved successfully:', response);
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลสำเร็จ',
                    text: 'ระบบจะรีเฟรชหน้าเพื่อแสดงข้อมูลล่าสุด',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            }).fail(function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                let errorMessage = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                let detailMessage = '';
                
                if (xhr.status === 422) {
                    // Validation errors
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        const errorList = Object.values(errors).flat();
                        errorMessage = 'ข้อมูลไม่ถูกต้อง';
                        detailMessage = errorList.join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 0) {
                    errorMessage = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้';
                } else if (xhr.status === 500) {
                    errorMessage = 'เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    html: detailMessage ? `${errorMessage}<br><small>${detailMessage}</small>` : errorMessage,
                    footer: `HTTP Status: ${xhr.status}`
                });
            });
        });

        // Delete Payment History
        $(document).on('click', '.delete-payment-history', function() {
            const id = $(this).data('id');
            if (confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')) {
                $.ajax({
                    url: '/labour/payment-history/' + id,
                    method: 'DELETE',
                    success: function() {
                        location.reload();
                    }
                });
            }
        });

        $('#agency').select2({
            placeholder: 'Select a Agency',
        });
        $('#company').select2({
            placeholder: 'Select a Company',
        });


        function selectstatus() {
            var select = document.getElementById('labour_status').value;
            console.log(select);
            if (select == 'job') {
                document.getElementById("status_labour").innerHTML = '';
            }
            if (select == 'resign') {
                document.getElementById("status_labour").innerHTML =
                    '<label for="">วันที่ลาออก</label> <input type="date" class="form-control" name="labour_resing_date"  value="{{ $labour->labour_resing_date }}">';
            }
            if (select == 'escape') {
                document.getElementById("status_labour").innerHTML =
                    '<label for="">วันที่หลบหนี</label> <input type="date" class="form-control" name="labour_escape_date" value="{{ $labour->labour_escape_date }}">';
            }
        }
        selectstatus();


        moment.updateLocale('th', {
            durationLabelsStandard: {
                S: 'millisecond',
                SS: 'milliseconds',
                s: 'ว',
                ss: 'วินาที',
                m: 'นาที',
                mm: 'นาที',
                h: 'ชม.',
                hh: 'ชั่วโมง',
                d: 'ว',
                dd: 'วัน',
                w: 'สัปดาห์',
                ww: 'สัปดาห์',
                M: 'เดือน',
                MM: 'เดือน',
                y: 'ป',
                yy: 'ปี'
            }
        });

        function calAge() {
            var date = document.getElementById("birthday").value;
            console.log(date)
            var diff = moment(date).diff(moment(), 'milliseconds');
            var duration = moment.duration(diff);
            document.getElementById("age_year").value = duration.format().replace("-", "");
        }

        calAge();


        Date.getFormattedDateDiff = function(date1, date2) {
            var b = moment(date1),
                a = moment(date2),
                intervals = ['years', 'months', 'weeks', 'days'],
                intervals_th = ['ปี', 'เดือน', 'สัปดาห์', 'วัน'],
                out = [];
            out = [];

            for (var i = 0; i < intervals.length; i++) {
                var diff = a.diff(b, intervals[i]);
                b.add(diff, intervals[i]);
                out.push(diff + ' ' + intervals_th[i]);
            }
            return out.join(', ');
        };
        // คำนวนวันหมดอายุ Passport
        function passend() {
            var end = new Date(document.getElementById('pass-date-end').value),
                start = new Date();

            document.getElementById('PassEndDate').value = 'จำนวนวันที่เหลือ' +
                Date.getFormattedDateDiff(start, end);


        }
        passend();
        // คำนวนวันหมดอายุ Visa
        function visaend() {
            var end = new Date(document.getElementById('visa-date-end').value),
                start = new Date();

            document.getElementById('VisaEndDate').value = 'จำนวนวันที่เหลือ' +
                Date.getFormattedDateDiff(start, end);
        }
        visaend();

        // คำนวนวันหมดอายุ WorkPreMit
        function Workpremitend() {
            var end = new Date(document.getElementById('workpremit-date-end').value),
                start = new Date();

            document.getElementById('workpremitEndDate').value = 'จำนวนวันที่เหลือ' +
                Date.getFormattedDateDiff(start, end);
        }
        Workpremitend();
        // คำนวนวันหมดอายุ 90day
        function day90end() {
            var end = new Date(document.getElementById('day90-date-end').value),
                start = new Date();

            document.getElementById('day90End').value = 'จำนวนวันที่เหลือ' +
                Date.getFormattedDateDiff(start, end);
        }
        day90end();
    </script>
@endsection
