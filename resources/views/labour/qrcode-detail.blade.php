
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    body, html { background: #f8f9fa; }
    .qr-mobile-container {
        width: 100%;
        margin: 0;
        padding: 1rem;
        background: #fff;
        font-size: 0.9rem;
    }
    
    /* Header Section */
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.8rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-bottom: 0.2rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 600;
    }
    
    /* Summary Section */
    .summary-card {
        background: #fff;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #4CAF50;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* Payment Table */
    .payment-table-container {
        background: #fff;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .payment-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        font-size: 0.85rem;
    }
    
    .payment-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 0.5rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.8rem;
        border: none;
    }
    
    .payment-table tbody tr.table-row {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }
    
    .payment-table tbody tr.table-row:hover {
        background: #f8f9fa;
    }
    
    .payment-table tbody tr.pending {
        border-left: 4px solid #f44336;
    }
    
    .payment-table tbody tr.partial {
        border-left: 4px solid #FF9800;
    }
    
    .payment-table tbody tr.completed {
        border-left: 4px solid #4CAF50;
        background: rgba(76, 175, 80, 0.05);
    }
    
    .payment-table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .payment-name-col {
        max-width: 200px;
    }
    
    .payment-name {
        font-weight: 600;
        color: #333;
        line-height: 1.3;
    }
    
    .text-center {
        text-align: center;
    }
    
    .amount-pending {
        color: #f44336;
        font-weight: 600;
    }
    
    .amount-partial {
        color: #FF9800;
        font-weight: 600;
    }
    
    .amount-paid {
        color: #4CAF50;
        font-weight: 600;
    }
    
    .amount-completed {
        color: #4CAF50;
        font-weight: 600;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 80px;
    }
    
    .status-badge.pending {
        background: rgba(244, 67, 54, 0.1);
        color: #f44336;
        border: 1px solid rgba(244, 67, 54, 0.2);
    }
    
    .status-badge.partial {
        background: rgba(255, 152, 0, 0.1);
        color: #FF9800;
        border: 1px solid rgba(255, 152, 0, 0.2);
    }
    
    .status-badge.completed {
        background: rgba(76, 175, 80, 0.1);
        color: #4CAF50;
        border: 1px solid rgba(76, 175, 80, 0.2);
    }
    
    /* Detail Button */
    .btn-details {
        background: #2196F3;
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin: 0 auto;
    }
    
    .btn-details:hover {
        background: #1976D2;
        transform: translateY(-1px);
    }
    
    .btn-details.active {
        background: #1976D2;
    }
    
    .expand-icon {
        font-size: 0.7rem;
        transition: transform 0.2s;
    }
    
    /* Detail Row */
    .detail-row td {
        padding: 0;
        background: #f8f9fa;
        border-top: none;
    }
    
    .detail-content {
        padding: 1rem;
        border-top: 2px solid #e9ecef;
    }
    
    .history-table {
        width: 100%;
        margin-bottom: 1rem;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .history-table th {
        background: #f5f5f5;
        padding: 0.8rem 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .history-table td {
        padding: 0.8rem 0.5rem;
        text-align: center;
        font-size: 0.85rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .balance-info {
        background: white;
        padding: 0.8rem;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .balance-text {
        font-size: 0.9rem;
        color: #666;
    }
    
    .balance-amount {
        font-size: 1rem;
        font-weight: 700;
        color: #f44336;
    }
    
    .pay-button {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .pay-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
        color: white;
        text-decoration: none;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }
        to {
            opacity: 1;
            max-height: 500px;
        }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .qr-mobile-container {
            padding: 0.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .payment-table-container {
            padding: 0.5rem;
        }
        
        .payment-table {
            font-size: 0.75rem;
        }
        
        .payment-table thead th {
            padding: 0.5rem 0.3rem;
            font-size: 0.7rem;
        }
        
        .payment-table td {
            padding: 0.5rem 0.3rem;
        }
        
        .payment-name {
            font-size: 0.8rem;
        }
        
        .status-badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
            min-width: 70px;
        }
        
        .btn-details {
            padding: 0.3rem 0.6rem;
            font-size: 0.7rem;
        }
        
        .detail-content {
            padding: 0.75rem;
        }
        
        /* Stack table on mobile */
        .payment-table thead {
            display: none;
        }
        
        .payment-table tbody tr.table-row {
            display: block;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            padding: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .payment-table tbody td {
            display: block;
            padding: 0.25rem 0;
            border-bottom: none;
            position: relative;
            text-align: left !important;
        }
        
        .payment-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #666;
            font-size: 0.7rem;
            display: block;
            margin-bottom: 0.2rem;
        }
        
        .payment-name-col:before {
            content: "รายการ: ";
        }
        
        .payment-table tbody td:nth-child(2):before {
            content: "จำนวนเงิน: ";
        }
        
        .payment-table tbody td:nth-child(3):before {
            content: "ชำระแล้ว: ";
        }
        
        .payment-table tbody td:nth-child(4):before {
            content: "คงเหลือ: ";
        }
        
        .payment-table tbody td:nth-child(5):before {
            content: "สถานะ: ";
        }
        
        .payment-table tbody td:nth-child(6):before {
            content: "การดำเนินการ: ";
        }
        
        .btn-details {
            width: 100%;
            justify-content: center;
            margin-top: 0.5rem;
        }
    }
</style>
<div class="qr-mobile-container">
    <!-- Header Section -->
    <div class="info-card">
        <h3 style="margin: 0 0 1rem 0; text-align: center; font-size: 1.2rem;">ข้อมูลคนงานเบื้องต้น</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">ชื่อ-นามสกุล</div>
                <div class="info-value">{{ $labour->labour_prefix }}{{ $labour->labour_fullname }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">เลขที่ Passport</div>
                <div class="info-value">{{ $labour->labour_passport_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">เลขที่วีซ่า</div>
                <div class="info-value">{{ $labour->labour_visa_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">บริษัท</div>
                <div class="info-value">{{ $labour->company->company_name ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">เอเจนซี่</div>
                <div class="info-value">{{ $labour->agency->agency_name ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">สถานะแรงงาน</div>
                <div class="info-value">{{ $labour->labour_status == 'enable' ? 'Enable' : 'Disable' }}</div>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    @php
        $paymentTypes = $labour->paymentTypes;
        $completedTypes = $paymentTypes->where('status', 'completed');
        $partialTypes = $paymentTypes->where('status', 'partial');
        $pendingTypes = $paymentTypes->where('status', 'pending');
        $totalOwed = $paymentTypes->sum(function($type) {
            return $type->total_amount - $type->calculatePaidAmount();
        });
    @endphp
    
    <div class="summary-card">
        <h4 style="margin: 0 0 0.8rem 0; color: #333;">สรุปสถานะการชำระ</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem;">
            <div style="text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #4CAF50;">{{ $completedTypes->count() }}</div>
                <div style="font-size: 0.8rem; color: #666;">ชำระครบ</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #FF9800;">{{ $partialTypes->count() }}</div>
                <div style="font-size: 0.8rem; color: #666;">ชำระบางส่วน</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #f44336;">{{ $pendingTypes->count() }}</div>
                <div style="font-size: 0.8rem; color: #666;">ยังไม่ชำระ</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 1.2rem; font-weight: 700; color: #f44336;">{{ number_format($totalOwed, 2) }}</div>
                <div style="font-size: 0.8rem; color: #666;">ยอดค้างชำระ (บาท)</div>
            </div>
        </div>
        
        <!-- ปุ่มนำทางไปหน้าชำระเงิน -->
        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e0e0e0;">
            <button id="addPaymentTypeBtn" class="pay-button" style="display: inline-flex; align-items: center; gap: 0.5rem; margin-right: 1rem;">
                <i class="fas fa-plus"></i>
                เพิ่มประเภทการหัก
            </button>
            <a href="{{ route('labour.paymentEdit', $labour->labour_id) }}" class="pay-button" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-credit-card"></i>
                หน้าจัดการเต็ม
            </a>
        </div>
    </div>

    <!-- Payment Table -->
    @if($paymentTypes->count() > 0)
        <div class="payment-table-container">
            <h4 style="margin: 0 0 1rem 0; color: #333;">รายการค่าใช้จ่าย</h4>
            <div class="table-responsive">
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>รายการ</th>
                            <th class="text-center">จำนวนเงิน</th>
                            <th class="text-center">ชำระแล้ว</th>
                            <th class="text-center">คงเหลือ</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- รายการที่ยังไม่ชำระ -->
                        @foreach($pendingTypes as $type)
                            @php
                                $paidAmount = $type->calculatePaidAmount();
                                $remainingAmount = $type->total_amount - $paidAmount;
                            @endphp
                            <tr class="table-row pending">
                                <td class="payment-name-col">
                                    <div class="payment-name">{{ $type->payment_name }}</div>
                                </td>
                                <td class="text-center">{{ number_format($type->total_amount, 2) }}</td>
                                <td class="text-center">{{ number_format($paidAmount, 2) }}</td>
                                <td class="text-center amount-pending">{{ number_format($remainingAmount, 2) }}</td>
                                <td class="text-center">
                                    <span class="status-badge pending">⏰ ยังไม่ชำระ</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-details" onclick="toggleDetails('detail-{{ $type->id }}')">
                                        <i class="expand-icon">▼</i> รายละเอียด
                                    </button>
                                    <br>
                                    <button class="pay-button" onclick="openPaymentModal({{ $type->id }}, '{{ $type->payment_name }}', {{ $remainingAmount }})" style="margin-top: 0.5rem; font-size: 0.7rem; padding: 0.3rem 0.6rem;">
                                        <i class="fas fa-plus"></i> ชำระเงิน
                                    </button>
                                </td>
                            </tr>
                            <tr class="detail-row" id="detail-{{ $type->id }}" style="display: none;">
                                <td colspan="6">
                                    <div class="detail-content">
                                        @include('labour.partials.payment-table-detail', ['type' => $type])
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- รายการที่ชำระบางส่วน -->
                        @foreach($partialTypes as $type)
                            @php
                                $paidAmount = $type->calculatePaidAmount();
                                $remainingAmount = $type->total_amount - $paidAmount;
                            @endphp
                            <tr class="table-row partial">
                                <td class="payment-name-col">
                                    <div class="payment-name">{{ $type->payment_name }}</div>
                                </td>
                                <td class="text-center">{{ number_format($type->total_amount, 2) }}</td>
                                <td class="text-center amount-paid">{{ number_format($paidAmount, 2) }}</td>
                                <td class="text-center amount-partial">{{ number_format($remainingAmount, 2) }}</td>
                                <td class="text-center">
                                    <span class="status-badge partial">⚠ ชำระบางส่วน</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-details" onclick="toggleDetails('detail-{{ $type->id }}')">
                                        <i class="expand-icon">▼</i> รายละเอียด
                                    </button>
                                    <br>
                                    <button class="pay-button" onclick="openPaymentModal({{ $type->id }}, '{{ $type->payment_name }}', {{ $remainingAmount }})" style="margin-top: 0.5rem; font-size: 0.7rem; padding: 0.3rem 0.6rem;">
                                        <i class="fas fa-plus"></i> ชำระเงิน
                                    </button>
                                </td>
                            </tr>
                            <tr class="detail-row" id="detail-{{ $type->id }}" style="display: none;">
                                <td colspan="6">
                                    <div class="detail-content">
                                        @include('labour.partials.payment-table-detail', ['type' => $type])
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <!-- รายการที่ชำระครบ (อยู่ล่างสุด) -->
                        @foreach($completedTypes as $type)
                            @php
                                $paidAmount = $type->calculatePaidAmount();
                                $remainingAmount = $type->total_amount - $paidAmount;
                            @endphp
                            <tr class="table-row completed">
                                <td class="payment-name-col">
                                    <div class="payment-name">{{ $type->payment_name }}</div>
                                </td>
                                <td class="text-center">{{ number_format($type->total_amount, 2) }}</td>
                                <td class="text-center amount-completed">{{ number_format($paidAmount, 2) }}</td>
                                <td class="text-center">{{ number_format($remainingAmount, 2) }}</td>
                                <td class="text-center">
                                    <span class="status-badge completed">✓ ชำระครบ</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-details" onclick="toggleDetails('detail-{{ $type->id }}')">
                                        <i class="expand-icon">▼</i> รายละเอียด
                                    </button>
                                </td>
                            </tr>
                            <tr class="detail-row" id="detail-{{ $type->id }}" style="display: none;">
                                <td colspan="6">
                                    <div class="detail-content">
                                        @include('labour.partials.payment-table-detail', ['type' => $type])
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="summary-card" style="text-align: center; border-left-color: #4CAF50;">
            <h4 style="color: #4CAF50; margin: 0;">🎉 ไม่มีรายการค้างชำระ</h4>
            <p style="margin: 0.5rem 0 0 0; color: #666;">คนงานรายนี้ชำระเงินครบถ้วนแล้ว</p>
        </div>
    @endif
</div>

<!-- Modal สำหรับเพิ่มประเภทการหัก -->
<div id="addPaymentTypeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 2rem; border-radius: 12px; width: 90%; max-width: 500px;">
        <h4 style="margin: 0 0 1rem 0;">เพิ่มประเภทการหัก</h4>
        <form id="addPaymentTypeForm">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ประเภทการหัก:</label>
                <select id="paymentTypeName" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="">เลือกประเภทการหัก</option>
                    <option value="ต่อรายงานตัว 90 วัน">ต่อรายงานตัว 90 วัน</option>
                    <option value="ต่อใบอนุญาตทำงาน">ต่อใบอนุญาตทำงาน</option>
                    <option value="ต่อวีซ่า">ต่อวีซ่า</option>
                    <option value="ต่ออายุหนังสือเดินทาง">ต่ออายุหนังสือเดินทาง</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">จำนวนเงิน:</label>
                <input type="number" id="paymentTypeAmount" step="0.01" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ประเภทการหัก:</label>
                <select id="deductionType" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="salary">หักจากเงินเดือน</option>
                    <option value="self_paid">แรงงานจ่ายเองทั้งหมด</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">หมายเหตุ:</label>
                <input type="text" id="paymentTypeNote" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="text-align: right; margin-top: 1.5rem;">
                <button type="button" onclick="closePaymentTypeModal()" style="background: #666; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 5px; margin-right: 0.5rem; cursor: pointer;">ยกเลิก</button>
                <button type="submit" style="background: #2196F3; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 5px; cursor: pointer;">บันทึก</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal สำหรับเพิ่มการชำระเงิน -->
<div id="addPaymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 2rem; border-radius: 12px; width: 90%; max-width: 500px;">
        <h4 style="margin: 0 0 1rem 0;">เพิ่มการชำระเงิน</h4>
        <div id="paymentInfo" style="background: #f5f5f5; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;"></div>
        <form id="addPaymentForm">
            <input type="hidden" id="paymentTypeId">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">จำนวนเงิน:</label>
                <input type="number" id="paymentAmount" step="0.01" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">วันที่ชำระ:</label>
                <input type="datetime-local" id="paymentDate" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">หลักฐานการชำระ:</label>
                <input type="file" id="paymentProof" accept=".pdf,.jpg,.jpeg,.png" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="text-align: right; margin-top: 1.5rem;">
                <button type="button" onclick="closePaymentModal()" style="background: #666; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 5px; margin-right: 0.5rem; cursor: pointer;">ยกเลิก</button>
                <button type="submit" style="background: #2196F3; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 5px; cursor: pointer;">บันทึก</button>
            </div>
        </form>
    </div>
</div>

<script>
const labourId = {{ $labour->labour_id }};

// Setup CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// เพิ่มประเภทการหัก
document.getElementById('addPaymentTypeBtn').addEventListener('click', function() {
    document.getElementById('addPaymentTypeModal').style.display = 'block';
});

function closePaymentTypeModal() {
    document.getElementById('addPaymentTypeModal').style.display = 'none';
    document.getElementById('addPaymentTypeForm').reset();
}

document.getElementById('addPaymentTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('labour_id', labourId);
    formData.append('payment_name', document.getElementById('paymentTypeName').value);
    formData.append('total_amount', document.getElementById('paymentTypeAmount').value);
    formData.append('deduction_type', document.getElementById('deductionType').value);
    formData.append('note', document.getElementById('paymentTypeNote').value);
    
    fetch('/labour/payment-type', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('เพิ่มประเภทการหักสำเร็จ');
            closePaymentTypeModal();
            location.reload();
        } else {
            alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถเพิ่มข้อมูลได้'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
    });
});

// เพิ่มการชำระเงิน
function openPaymentModal(paymentTypeId, paymentName, remainingAmount) {
    document.getElementById('paymentTypeId').value = paymentTypeId;
    document.getElementById('paymentInfo').innerHTML = `
        <strong>ประเภท:</strong> ${paymentName}<br>
        <strong>ยอดคงเหลือ:</strong> ${remainingAmount.toLocaleString()} บาท
    `;
    document.getElementById('paymentAmount').max = remainingAmount;
    
    // Set current date/time
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('paymentDate').value = now.toISOString().slice(0, 16);
    
    document.getElementById('addPaymentModal').style.display = 'block';
}

function closePaymentModal() {
    document.getElementById('addPaymentModal').style.display = 'none';
    document.getElementById('addPaymentForm').reset();
}

document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('payment_type_id', document.getElementById('paymentTypeId').value);
    formData.append('amount', document.getElementById('paymentAmount').value);
    formData.append('payment_date', document.getElementById('paymentDate').value);
    
    const proofFile = document.getElementById('paymentProof').files[0];
    if (proofFile) {
        formData.append('proof_file', proofFile);
    }
    
    fetch('/labour/payment-history', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('บันทึกการชำระเงินสำเร็จ');
            closePaymentModal();
            location.reload();
        } else {
            alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
    });
});

// Close modal when clicking outside
document.getElementById('addPaymentTypeModal').addEventListener('click', function(e) {
    if (e.target === this) closePaymentTypeModal();
});

document.getElementById('addPaymentModal').addEventListener('click', function(e) {
    if (e.target === this) closePaymentModal();
});

function toggleDetails(detailId) {
    const detailRow = document.getElementById(detailId);
    const button = event.target.closest('.btn-details');
    const icon = button.querySelector('.expand-icon');
    
    if (detailRow.style.display === 'none') {
        detailRow.style.display = 'table-row';
        icon.style.transform = 'rotate(180deg)';
        button.classList.add('active');
    } else {
        detailRow.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
        button.classList.remove('active');
    }
}
</script>

<style>
/* Mobile-First Responsive Design */
@media (max-width: 768px) {
    .qr-mobile-container {
        padding: 0.5rem;
        max-width: 100vw;
        overflow-x: hidden;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .info-item {
        padding: 0.75rem;
    }
    
    .info-card, .summary-card, .payment-group {
        margin-bottom: 0.75rem;
        border-radius: 12px;
    }
    
    .payment-header {
        padding: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .payment-name {
        font-size: 0.95rem;
        flex: 1;
        min-width: 200px;
    }
    
    .payment-amount {
        font-size: 0.9rem;
    }
    
    .group-header {
        padding: 1rem;
        font-size: 0.95rem;
    }
    
    .status-icon {
        width: 24px;
        height: 24px;
        font-size: 0.8rem;
    }
    
    .payment-summary {
        padding: 0.75rem;
    }
    
    .summary-value {
        font-size: 1rem;
    }
    
    .history-item {
        padding: 0.75rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .history-amount {
        text-align: left;
        width: 100%;
    }
    
    .payment-btn {
        padding: 0.65rem 1.5rem;
        font-size: 0.9rem;
    }
}

/* Tablet Styles */
@media (min-width: 769px) and (max-width: 1024px) {
    .qr-mobile-container {
        padding: 1rem;
        max-width: 100%;
    }
    
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .payment-header {
        padding: 1.25rem;
    }
    
    .group-header {
        padding: 1.25rem;
    }
}

/* Desktop Styles */
@media (min-width: 1025px) {
    .qr-mobile-container {
        padding: 1.5rem;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .payment-header {
        padding: 1.5rem;
    }
    
    .group-header {
        padding: 1.5rem;
    }
    
    .payment-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
}

/* Print Styles */
@media print {
    .qr-mobile-container {
        padding: 0;
        box-shadow: none;
        background: white;
    }
    
    .payment-group {
        page-break-inside: avoid;
        margin-bottom: 1rem;
    }
    
    .payment-item {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    
    .payment-details {
        display: block !important;
    }
    
    .expand-icon {
        display: none;
    }
    
    .payment-btn {
        display: none;
    }
    
    a[href*="asset"] {
        text-decoration: underline;
        color: #000 !important;
    }
}

/* Animation Improvements */
.payment-item.active .payment-details {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        max-height: 1000px;
        transform: translateY(0);
    }
}

/* Accessibility Improvements */
@media (prefers-reduced-motion: reduce) {
    .payment-item,
    .payment-details,
    .payment-btn {
        transition: none;
        animation: none;
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .info-card,
    .summary-card,
    .payment-group,
    .payment-item {
        border: 2px solid #000;
    }
    
    .group-header.completed {
        background: #000;
        color: #fff;
    }
    
    .group-header.partial {
        background: #666;
        color: #fff;
    }
    
    .group-header.pending {
        background: #333;
        color: #fff;
    }
}

/* Focus States for Accessibility */
.payment-header:focus,
.payment-btn:focus {
    outline: 3px solid #2196F3;
    outline-offset: 2px;
}

/* Loading State */
.qr-mobile-container.loading {
    opacity: 0.7;
    pointer-events: none;
}

.qr-mobile-container.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 32px;
    height: 32px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #2196F3;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>

