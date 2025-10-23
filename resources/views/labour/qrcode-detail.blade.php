
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
            <button onclick="togglePaymentTypeForm()" id="addPaymentTypeBtn" class="pay-button" style="display: inline-flex; align-items: center; gap: 0.5rem; margin-right: 1rem;">
                <i class="fas fa-plus"></i>
                เพิ่มประเภทการหัก
            </button>
            <a href="{{ route('labour.paymentEdit', $labour->labour_id) }}" class="pay-button" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-credit-card"></i>
                หน้าจัดการเต็ม
            </a>
        </div>

        <!-- ฟอร์มเพิ่มประเภทการหัก (ซ่อนอยู่) -->
        <div id="paymentTypeForm" style="display: none; margin-top: 1.5rem; padding: 2rem; background: #f8f9fa; border-radius: 12px; border: 2px solid #e3f2fd;">
            <h4 style="margin: 0 0 1.5rem 0; color: #1976d2;">
                <i class="fas fa-plus-circle"></i> เพิ่มประเภทการหัก
            </h4>
            <form id="addPaymentTypeForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ประเภทการหัก:</label>
                        <select id="paymentTypeSelect" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; background: white;" onchange="handlePaymentTypeChange()">
                            <option value="">-- เลือกประเภทการหัก --</option>
                            <option value="ต่อรายงานตัว 90 วัน">ต่อรายงานตัว 90 วัน</option>
                            <option value="ต่อใบอนุญาตทำงาน">ต่อใบอนุญาตทำงาน</option>
                            <option value="ต่อวีซ่า">ต่อวีซ่า</option>
                            <option value="ต่ออายุหนังสือเดินทาง">ต่ออายุหนังสือเดินทาง</option>
                            <option value="อื่นๆ">อื่นๆ (ระบุเอง)</option>
                        </select>
                        
                        <!-- ช่องสำหรับระบุประเภทการหักเอง -->
                        <div id="customPaymentTypeDiv" style="display: none; margin-top: 0.75rem; padding: 1rem; background: #e8f5e8; border: 2px solid #4CAF50; border-radius: 8px;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2e7d32; font-size: 0.9rem;">📝 ระบุประเภทการหักเอง:</label>
                            <input type="text" id="customPaymentType" placeholder="กรุณาระบุประเภทการหัก เช่น ต่อใบขับขี่, ต่อประกันสังคม ฯลฯ" style="width: 100%; padding: 0.75rem; border: 2px solid #4CAF50; border-radius: 8px; font-size: 1rem; background: white; box-shadow: 0 2px 4px rgba(76, 175, 80, 0.2);" oninput="updateCustomPaymentType()">
                            <small style="color: #2e7d32; font-size: 0.8rem; display: block; margin-top: 0.3rem;">💡 พิมพ์ชื่อประเภทการหักที่ต้องการ</small>
                        </div>
                        
                        <input type="hidden" id="paymentTypeName" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">จำนวนเงิน:</label>
                        <input type="number" id="paymentTypeAmount" step="0.01" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ประเภทการหัก:</label>
                        <div style="border: 1px solid #ddd; border-radius: 8px; padding: 1rem; background: white;">
                            <label style="display: block; margin-bottom: 0.5rem; cursor: pointer;">
                                <input type="radio" name="deductionTypeRadio" value="salary" style="margin-right: 0.5rem;" checked> หักจากเงินเดือน
                            </label>
                            <label style="display: block; margin-bottom: 0; cursor: pointer;">
                                <input type="radio" name="deductionTypeRadio" value="self_paid" style="margin-right: 0.5rem;"> แรงงานจ่ายเองทั้งหมด
                            </label>
                        </div>
                        <input type="hidden" id="deductionType" value="salary" required>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">หมายเหตุ:</label>
                        <input type="text" id="paymentTypeNote" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>
                <div style="text-align: right; margin-top: 1.5rem;">
                    <button type="button" onclick="togglePaymentTypeForm()" style="background: #666; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; margin-right: 0.5rem; cursor: pointer; font-size: 1rem;">ยกเลิก</button>
                    <button type="submit" style="background: #2196F3; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; font-size: 1rem;">บันทึก</button>
                </div>
            </form>
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
                                    <button class="btn-details" onclick="toggleDetails('detail-{{ $type->id ?? 'unknown' }}')">
                                        <i class="expand-icon">▼</i> รายละเอียด
                                    </button>
                                    <br>
                                    <button class="pay-button" data-payment-id="{{ $type->id ?? $type->payment_type_id ?? 'temp_'.uniqid() }}" data-payment-name="{{ $type->payment_name }}" data-remaining-amount="{{ $remainingAmount }}" onclick="handlePaymentClick(this)" style="margin-top: 0.5rem; font-size: 0.7rem; padding: 0.3rem 0.6rem;" data-debug-info="id:{{ $type->id }},payment_type_id:{{ $type->payment_type_id ?? 'null' }}">
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
                                    <button class="btn-details" onclick="toggleDetails('detail-{{ $type->id ?? 'unknown' }}')">
                                        <i class="expand-icon">▼</i> รายละเอียด
                                    </button>
                                    <br>
                                    <button class="pay-button" data-payment-id="{{ $type->id ?? $type->payment_type_id ?? 'temp_'.uniqid() }}" data-payment-name="{{ $type->payment_name }}" data-remaining-amount="{{ $remainingAmount }}" onclick="handlePaymentClick(this)" style="margin-top: 0.5rem; font-size: 0.7rem; padding: 0.3rem 0.6rem;" data-debug-info="id:{{ $type->id }},payment_type_id:{{ $type->payment_type_id ?? 'null' }}">
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
        
        <!-- ฟอร์มชำระเงิน (ซ่อนอยู่) -->
        <div id="paymentForm" style="display: none; margin-top: 1.5rem; padding: 2rem; background: #f0f8ff; border-radius: 12px; border: 2px solid #2196f3;">
            <h4 style="margin: 0 0 1.5rem 0; color: #1976d2;">
                <i class="fas fa-credit-card"></i> เพิ่มการชำระเงิน
            </h4>
            <div id="paymentInfo" style="background: #e3f2fd; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #2196f3;"></div>
            <form id="addPaymentForm">
                <input type="hidden" id="paymentTypeId">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">จำนวนเงิน:</label>
                        <input type="number" id="paymentAmount" step="0.01" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">วันที่ชำระ:</label>
                        <input type="datetime-local" id="paymentDate" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">หลักฐานการชำระ:</label>
                    <input type="file" id="paymentProof" accept=".pdf,.jpg,.jpeg,.png" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>
                <div style="text-align: right; margin-top: 1.5rem;">
                    <button type="button" onclick="hidePaymentForm()" style="background: #666; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; margin-right: 0.5rem; cursor: pointer; font-size: 1rem;">ยกเลิก</button>
                    <button type="submit" style="background: #2196F3; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; font-size: 1rem;">บันทึก</button>
                </div>
            </form>
        </div>
    @else
        <div class="summary-card" style="text-align: center; border-left-color: #4CAF50;">
            <h4 style="color: #4CAF50; margin: 0;">🎉 ไม่มีรายการค้างชำระ</h4>
            <p style="margin: 0.5rem 0 0 0; color: #666;">คนงานรายนี้ชำระเงินครบถ้วนแล้ว</p>
        </div>
    @endif
</div>

<!-- JavaScript Functions -->

<script>
const labourId = {{ $labour->labour_id }};

// Test if JavaScript is working
console.log('JavaScript loaded successfully');

// Setup CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
console.log('CSRF Token:', csrfToken ? 'Found' : 'Not Found');

// Prevent modal errors from other files
window.addEventListener('error', function(e) {
    if (e.message && e.message.includes('bootstrap.Modal.getInstance')) {
        console.warn('Bootstrap Modal error suppressed:', e.message);
        e.preventDefault();
        return false;
    }
});

// Debug payment buttons on load
window.addEventListener('load', function() {
    console.log('Window loaded, checking payment buttons...');
    testPaymentButtons();
});

// Test function สำหรับทดสอบการแสดงช่อง custom input
function testCustomInput() {
    const customDiv = document.getElementById('customPaymentTypeDiv');
    if (customDiv) {
        if (customDiv.style.display === 'none') {
            customDiv.style.display = 'block';
            console.log('Custom input shown');
        } else {
            customDiv.style.display = 'none';
            console.log('Custom input hidden');
        }
    } else {
        console.log('Custom input div not found');
    }
}

// Test function สำหรับทดสอบปุ่มชำระเงิน
function testPaymentButtons() {
    const payButtons = document.querySelectorAll('.pay-button[data-payment-id]');
    console.log('Found payment buttons:', payButtons.length);
    
    payButtons.forEach((button, index) => {
        const paymentId = button.getAttribute('data-payment-id');
        const paymentName = button.getAttribute('data-payment-name');
        const remainingAmount = button.getAttribute('data-remaining-amount');
        const debugInfo = button.getAttribute('data-debug-info');
        
        console.log(`Button ${index + 1}:`, {
            paymentId,
            paymentName,
            remainingAmount,
            debugInfo,
            hasPaymentId: !!paymentId,
            hasPaymentName: !!paymentName,
            hasRemainingAmount: !!remainingAmount,
            paymentIdLength: paymentId ? paymentId.length : 0
        });
        
        // ถ้า paymentId ว่างให้ดู HTML element
        if (!paymentId || paymentId.trim() === '') {
            console.warn(`Button ${index + 1} has empty paymentId!`, button.outerHTML.substring(0, 200));
        }
    });
    
    return payButtons;
}

// Handle payment type change (inline function)
function handlePaymentTypeChange() {
    const paymentTypeSelect = document.getElementById('paymentTypeSelect');
    const customPaymentTypeDiv = document.getElementById('customPaymentTypeDiv');
    const customPaymentTypeInput = document.getElementById('customPaymentType');
    const hiddenPaymentSelect = document.getElementById('paymentTypeName');
    
    console.log('handlePaymentTypeChange called');
    
    if (!paymentTypeSelect) {
        console.error('paymentTypeSelect not found');
        return;
    }
    
    const selectedValue = paymentTypeSelect.value;
    console.log('Selected value:', selectedValue);
    
    if (selectedValue === 'อื่นๆ') {
        console.log('Showing custom input');
        if (customPaymentTypeDiv) {
            customPaymentTypeDiv.style.display = 'block';
            console.log('Custom div display set to block');
        }
        if (customPaymentTypeInput) {
            customPaymentTypeInput.required = true;
            customPaymentTypeInput.focus();
        }
        if (hiddenPaymentSelect) {
            hiddenPaymentSelect.value = '';
        }
    } else {
        console.log('Hiding custom input');
        if (customPaymentTypeDiv) {
            customPaymentTypeDiv.style.display = 'none';
        }
        if (customPaymentTypeInput) {
            customPaymentTypeInput.required = false;
            customPaymentTypeInput.value = '';
        }
        if (hiddenPaymentSelect) {
            hiddenPaymentSelect.value = selectedValue;
        }
    }
}

// Update custom payment type value
function updateCustomPaymentType() {
    const customPaymentTypeInput = document.getElementById('customPaymentType');
    const hiddenPaymentSelect = document.getElementById('paymentTypeName');
    
    if (customPaymentTypeInput && hiddenPaymentSelect) {
        const customValue = customPaymentTypeInput.value.trim();
        hiddenPaymentSelect.value = customValue;
        console.log('Custom payment type updated:', customValue);
    }
}

// Toggle Payment Type Form
function togglePaymentTypeForm() {
    const form = document.getElementById('paymentTypeForm');
    const btn = document.getElementById('addPaymentTypeBtn');
    
    console.log('togglePaymentTypeForm called');
    
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-minus"></i> ยกเลิก';
        btn.style.background = '#666';
        // Scroll to form
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Test if elements exist after form is shown
        setTimeout(() => {
            const selectElement = document.getElementById('paymentTypeSelect');
            const customDiv = document.getElementById('customPaymentTypeDiv');
            console.log('After form shown:', {
                selectExists: !!selectElement,
                customDivExists: !!customDiv
            });
        }, 100);
        
    } else {
        form.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-plus"></i> เพิ่มประเภทการหัก';
        btn.style.background = '';
        // Reset form
        document.getElementById('addPaymentTypeForm').reset();
        document.getElementById('paymentTypeName').value = '';
        document.getElementById('deductionType').value = 'salary';
        
        // Reset custom payment type section
        const paymentTypeSelect = document.getElementById('paymentTypeSelect');
        const customPaymentTypeDiv = document.getElementById('customPaymentTypeDiv');
        const customPaymentTypeInput = document.getElementById('customPaymentType');
        
        if (paymentTypeSelect) paymentTypeSelect.value = '';
        if (customPaymentTypeDiv) customPaymentTypeDiv.style.display = 'none';
        if (customPaymentTypeInput) {
            customPaymentTypeInput.value = '';
            customPaymentTypeInput.required = false;
        }
    }
}

// Handle Payment Button Click
function handlePaymentClick(button) {
    console.log('Button clicked:', button);
    console.log('Button attributes:', {
        'data-payment-id': button.getAttribute('data-payment-id'),
        'data-payment-name': button.getAttribute('data-payment-name'),
        'data-remaining-amount': button.getAttribute('data-remaining-amount')
    });
    
    const paymentId = button.getAttribute('data-payment-id');
    const paymentName = button.getAttribute('data-payment-name');
    const remainingAmount = parseFloat(button.getAttribute('data-remaining-amount'));
    
    console.log('Parsed values:', { paymentId, paymentName, remainingAmount });
    
    // ตรวจสอบค่าที่จำเป็น
    if (!paymentId) {
        console.error('paymentId is missing!');
        alert('❌ ข้อผิดพลาด: ไม่พบรหัสประเภทการหัก\nกรุณารีเฟรชหน้าเว็บและลองใหม่');
        return;
    }
    
    if (!paymentName) {
        console.error('paymentName is missing!');
        alert('❌ ข้อผิดพลาด: ไม่พบชื่อประเภทการหัก');
        return;
    }
    
    if (isNaN(remainingAmount) || remainingAmount <= 0) {
        console.error('Invalid remainingAmount:', remainingAmount);
        alert('❌ ข้อผิดพลาด: จำนวนเงินที่ค้างไม่ถูกต้อง');
        return;
    }
    
    showPaymentForm(paymentId, paymentName, remainingAmount);
}

// Show Payment Form
function showPaymentForm(paymentTypeId, paymentName, remainingAmount) {
    console.log('showPaymentForm called:', { paymentTypeId, paymentName, remainingAmount });
    
    const form = document.getElementById('paymentForm');
    const info = document.getElementById('paymentInfo');
    const paymentTypeIdInput = document.getElementById('paymentTypeId');
    const paymentAmountInput = document.getElementById('paymentAmount');
    const paymentDateInput = document.getElementById('paymentDate');
    
    console.log('Payment form elements:', {
        form: !!form,
        info: !!info,
        paymentTypeIdInput: !!paymentTypeIdInput,
        paymentAmountInput: !!paymentAmountInput,
        paymentDateInput: !!paymentDateInput
    });
    
    if (!form) {
        console.error('Payment form not found!');
        alert('❌ ไม่พบฟอร์มชำระเงิน กรุณารีเฟรชหน้าเว็บ');
        return;
    }
    
    // Hide payment type form if visible
    const paymentTypeForm = document.getElementById('paymentTypeForm');
    if (paymentTypeForm && paymentTypeForm.style.display === 'block') {
        togglePaymentTypeForm();
    }
    
    // Set payment info
    if (paymentTypeIdInput) {
        paymentTypeIdInput.value = paymentTypeId;
        console.log('Set paymentTypeId:', paymentTypeId);
    } else {
        console.error('paymentTypeId input not found!');
    }
    
    if (paymentAmountInput) {
        paymentAmountInput.value = remainingAmount;
        console.log('Set amount:', remainingAmount);
    } else {
        console.error('paymentAmount input not found!');
    }
    
    // Set current datetime
    if (paymentDateInput) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const dateTimeString = now.toISOString().slice(0, 16);
        paymentDateInput.value = dateTimeString;
        console.log('Set payment date:', dateTimeString);
    } else {
        console.error('paymentDate input not found!');
    }
    
    // Update info display
    if (info) {
        info.innerHTML = `
            <strong>ประเภทการหัก:</strong> ${paymentName}<br>
            <strong>จำนวนเงินที่ค้าง:</strong> ${remainingAmount.toLocaleString()} บาท
        `;
    }
    
    // Show form
    form.style.display = 'block';
    console.log('Payment form displayed');
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    console.log('Scrolled to payment form');
    
    // Focus on amount field for user convenience
    if (paymentAmountInput) {
        setTimeout(() => {
            paymentAmountInput.focus();
            paymentAmountInput.select();
        }, 300);
    }
}

// Hide Payment Form
function hidePaymentForm() {
    const form = document.getElementById('paymentForm');
    form.style.display = 'none';
    
    // Reset form
    document.getElementById('addPaymentForm').reset();
    document.getElementById('paymentTypeId').value = '';
}

// Add event listeners for select debugging
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Setting up event listeners');
    
    // Handle payment type select change
    const paymentTypeSelect = document.getElementById('paymentTypeSelect');
    const customPaymentTypeDiv = document.getElementById('customPaymentTypeDiv');
    const customPaymentTypeInput = document.getElementById('customPaymentType');
    const hiddenPaymentSelect = document.getElementById('paymentTypeName');
    
    console.log('Elements found:', {
        paymentTypeSelect: !!paymentTypeSelect,
        customPaymentTypeDiv: !!customPaymentTypeDiv,
        customPaymentTypeInput: !!customPaymentTypeInput,
        hiddenPaymentSelect: !!hiddenPaymentSelect
    });
    
    if (paymentTypeSelect) {
        paymentTypeSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            console.log('Payment type selected:', selectedValue);
            
            if (selectedValue === 'อื่นๆ') {
                console.log('Showing custom input field');
                // แสดงช่องระบุเอง
                if (customPaymentTypeDiv) {
                    customPaymentTypeDiv.style.display = 'block';
                }
                if (customPaymentTypeInput) {
                    customPaymentTypeInput.required = true;
                }
                if (hiddenPaymentSelect) {
                    hiddenPaymentSelect.value = ''; // ล้างค่าชั่วคราว
                }
            } else {
                console.log('Hiding custom input field');
                // ซ่อนช่องระบุเอง
                if (customPaymentTypeDiv) {
                    customPaymentTypeDiv.style.display = 'none';
                }
                if (customPaymentTypeInput) {
                    customPaymentTypeInput.required = false;
                    customPaymentTypeInput.value = '';
                }
                if (hiddenPaymentSelect) {
                    hiddenPaymentSelect.value = selectedValue;
                }
            }
        });
    }
    
    // Handle custom payment type input
    if (customPaymentTypeInput) {
        customPaymentTypeInput.addEventListener('input', function() {
            const customValue = this.value.trim();
            if (customValue) {
                if (hiddenPaymentSelect) {
                    hiddenPaymentSelect.value = customValue;
                }
                console.log('Custom payment type entered:', customValue);
            } else {
                if (hiddenPaymentSelect) {
                    hiddenPaymentSelect.value = '';
                }
            }
        });
    }
    
    // Handle deduction type radio button changes
    const deductionTypeRadios = document.querySelectorAll('input[name="deductionTypeRadio"]');
    const hiddenDeductionSelect = document.getElementById('deductionType');
    
    // Set default value
    if (hiddenDeductionSelect) {
        hiddenDeductionSelect.value = 'salary';
    }
    
    deductionTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                if (hiddenDeductionSelect) {
                    hiddenDeductionSelect.value = this.value;
                }
                console.log('Deduction type selected:', this.value);
            }
        });
    });
});

document.getElementById('addPaymentTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Payment type form submitted');
    
    try {
        if (!csrfToken) {
            alert('ไม่พบ CSRF Token กรุณารีเฟรชหน้าเว็บ');
            return;
        }
        
        const paymentTypeSelect = document.getElementById('paymentTypeSelect');
        const customPaymentTypeInput = document.getElementById('customPaymentType');
        const paymentName = document.getElementById('paymentTypeName').value;
        const totalAmount = document.getElementById('paymentTypeAmount').value;
        const deductionType = document.getElementById('deductionType').value;
        const note = document.getElementById('paymentTypeNote').value;
        
        console.log('Form data:', { paymentName, totalAmount, deductionType, note });
        
        // ตรวจสอบการเลือกประเภทการหัก
        if (!paymentTypeSelect.value) {
            alert('กรุณาเลือกประเภทการหัก');
            paymentTypeSelect.focus();
            return;
        }
        
        // ตรวจสอบช่องระบุเองเมื่อเลือก "อื่นๆ"
        if (paymentTypeSelect.value === 'อื่นๆ' && !customPaymentTypeInput.value.trim()) {
            alert('กรุณาระบุประเภทการหัก');
            customPaymentTypeInput.focus();
            return;
        }
        
        if (!totalAmount || !deductionType) {
            alert('กรุณากรอกข้อมูลให้ครบถ้วน\n- จำนวนเงิน\n- ประเภทการหัก');
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('labour_id', labourId);
        formData.append('payment_name', paymentName);
        formData.append('total_amount', totalAmount);
        formData.append('deduction_type', deductionType);
        formData.append('note', note);
        
        console.log('Sending request to /labour/payment-type');
        
        fetch('/labour/payment-type', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // ตรวจสอบ content type
            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);
            
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // หาก response ไม่ใช่ JSON อาจจะเป็น HTML
                return response.text().then(text => {
                    console.log('Non-JSON response:', text);
                    // สำหรับ Laravel ที่ redirect หลัง successful creation
                    if (response.status === 200 || response.status === 201) {
                        return { success: true, message: 'Data saved successfully' };
                    }
                    throw new Error('Invalid response format');
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            
            // ตรวจสอบหลายรูปแบบของ success response
            if (data.success || data.labour_id || (data && !data.error)) {
                alert('✅ เพิ่มประเภทการหักสำเร็จ!');
                togglePaymentTypeForm();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                alert('❌ เกิดข้อผิดพลาด: ' + (data.message || data.error || 'ไม่สามารถเพิ่มข้อมูลได้'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
        });
    } catch (error) {
        console.error('Form submission error:', error);
        alert('เกิดข้อผิดพลาด: ' + error.message);
    }
});

document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Payment form submitted');
    
    try {
        if (!csrfToken) {
            alert('ไม่พบ CSRF Token กรุณารีเฟรชหน้าเว็บ');
            return;
        }
        
        const paymentTypeIdElement = document.getElementById('paymentTypeId');
        const amountElement = document.getElementById('paymentAmount');
        const paymentDateElement = document.getElementById('paymentDate');
        
        const paymentTypeId = paymentTypeIdElement ? paymentTypeIdElement.value : '';
        const amount = amountElement ? amountElement.value : '';
        const paymentDate = paymentDateElement ? paymentDateElement.value : '';
        
        console.log('Form elements:', {
            paymentTypeIdElement: !!paymentTypeIdElement,
            amountElement: !!amountElement,
            paymentDateElement: !!paymentDateElement
        });
        
        console.log('Form data:', { paymentTypeId, amount, paymentDate });
        
        // ตรวจสอบข้อมูลทีละฟิลด์
        if (!paymentTypeId) {
            alert('❌ ไม่พบข้อมูลประเภทการหัก กรุณาลองใหม่');
            console.error('paymentTypeId is missing');
            return;
        }
        
        if (!amount || amount <= 0) {
            alert('❌ กรุณากรอกจำนวนเงินที่ถูกต้อง');
            if (amountElement) amountElement.focus();
            return;
        }
        
        if (!paymentDate) {
            alert('❌ กรุณาเลือกวันที่ชำระ');
            if (paymentDateElement) paymentDateElement.focus();
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('payment_type_id', paymentTypeId);
        formData.append('amount', amount);
        formData.append('payment_date', paymentDate);
        
        const proofFile = document.getElementById('paymentProof').files[0];
        if (proofFile) {
            console.log('Proof file:', proofFile.name);
            formData.append('proof_file', proofFile);
        }
        
        console.log('Sending request to /labour/payment-history');
        
        fetch('/labour/payment-history', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // ตรวจสอบ content type
            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);
            
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // หาก response ไม่ใช่ JSON อาจจะเป็น HTML
                return response.text().then(text => {
                    console.log('Non-JSON response:', text);
                    // สำหรับ Laravel ที่ redirect หลัง successful creation
                    if (response.status === 200 || response.status === 201) {
                        return { success: true, message: 'Payment saved successfully' };
                    }
                    throw new Error('Invalid response format');
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            
            // ตรวจสอบหลายรูปแบบของ success response
            if (data.success || data.payment_id || data.id || (data && !data.error)) {
                alert('✅ บันทึกการชำระเงินสำเร็จ!');
                hidePaymentForm();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                alert('❌ เกิดข้อผิดพลาด: ' + (data.message || data.error || 'ไม่สามารถบันทึกข้อมูลได้'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
        });
    } catch (error) {
        console.error('Form submission error:', error);
        alert('เกิดข้อผิดพลาด: ' + error.message);
    }
});

// Close modal when clicking outside
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

