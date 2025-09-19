<style>
    body, html { background: #f2f4f8; }
    .payment-detail-container {
        width: 100%;
        min-height: auto;
        margin: 0;
        padding: 0.5rem;
        background: #fff;
        border-radius: 0;
        box-shadow: none;
        font-size: 0.9rem;
    }
    .payment-detail-header {
        text-align: center;
        margin-bottom: 0.8rem;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        color: white;
    }
    .payment-detail-header h3 {
        font-size: 1.2rem;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }
    .labour-info {
        background: linear-gradient(90deg, #e3eafc 0%, #f8f9fa 100%);
        border-radius: 8px;
        padding: 0.8rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    .labour-info strong {
        min-width: 100px;
        display: inline-block;
        color: #2a3b6e;
    }
    .payment-type-info {
        background: #f8f9ff;
        border: 2px solid #667eea;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .payment-type-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a237e;
        margin-bottom: 0.5rem;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.3em 0.8em;
        border-radius: 20px;
        margin-left: 0.5rem;
    }
    .payment-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .summary-card {
        background: white;
        padding: 0.8rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .summary-label {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 0.3rem;
    }
    .summary-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a237e;
    }
    .payment-history-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .payment-history-table th,
    .payment-history-table td {
        padding: 0.8rem 0.5rem;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }
    .payment-history-table th {
        background: #667eea;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .payment-history-table td {
        font-size: 0.85rem;
    }
    .payment-history-table tr:nth-child(even) {
        background: #f8f9ff;
    }
    .no-history {
        text-align: center;
        padding: 2rem;
        color: #666;
        font-style: italic;
    }
    .action-buttons {
        text-align: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e0e0e0;
    }
    .btn-payment {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        margin: 0 0.5rem;
        transition: transform 0.2s;
    }
    .btn-payment:hover {
        transform: translateY(-2px);
        color: white;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
</style>

<div class="payment-detail-container">
    <div class="payment-detail-header">
        <h3>รายละเอียดการชำระเงิน</h3>
        <small>{{ $paymentType->payment_name }}</small>
    </div>

    <!-- ข้อมูลแรงงาน -->
    <div class="labour-info">
        <div><strong>ชื่อ-สกุล:</strong> {{ $labour->labour_prefix }}.{{ $labour->labour_fullname }}</div>
        <div><strong>เลขที่ Passport:</strong> {{ $labour->labour_passport_number }}</div>
        <div><strong>บริษัท:</strong> {{ $labour->company->company_name ?? 'ไม่ระบุ' }}</div>
    </div>

    <!-- ข้อมูลประเภทการชำระ -->
    <div class="payment-type-info">
        <div class="payment-type-title">
            {{ $paymentType->payment_name }}
            <span class="status-badge {{ $paymentType->status === 'completed' ? 'bg-success' : ($paymentType->status === 'partial' ? 'bg-warning text-dark' : 'bg-danger') }}">
                {{ $paymentType->status }}
            </span>
        </div>

        <!-- สรุปยอดเงิน -->
        <div class="payment-summary">
            <div class="summary-card">
                <div class="summary-label">ยอดรวมทั้งหมด</div>
                <div class="summary-value">{{ number_format($paymentType->total_amount, 2) }} บาท</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">ยอดค้างชำระ</div>
                <div class="summary-value" style="color: {{ $paymentType->total_amount - $paymentType->calculatePaidAmount() > 0 ? '#dc3545' : '#28a745' }}">
                    {{ number_format($paymentType->total_amount - $paymentType->calculatePaidAmount(), 2) }} บาท
                </div>
            </div>
        </div>
    </div>

    <!-- ประวัติการชำระเงิน -->
    <h4 style="color: #1a237e; margin-bottom: 0.8rem;">ประวัติการชำระเงิน</h4>
    
    @php
        $histories = $paymentType->histories->sortByDesc('payment_date');
    @endphp
    
    @if($histories->count() > 0)
        <table class="payment-history-table">
            <thead>
                <tr>
                    <th>วันที่ชำระ</th>
                    <th>จำนวนเงิน</th>
                    <th>หมายเหตุ</th>
                    <th>ไฟล์หลักฐาน</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->payment_date ? $history->payment_date->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-weight: 600; color: #28a745;">{{ number_format($history->amount, 2) }} บาท</td>
                        <td>{{ $history->note ?? '-' }}</td>
                        <td>
                            @if($history->proof_file)
                                <a href="{{ asset('storage/' . $history->proof_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-file"></i> ดูไฟล์
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="background: #e8f4fd; padding: 0.8rem; border-radius: 6px; font-size: 0.9rem;">
            <strong>สรุป:</strong> ชำระแล้ว {{ $histories->count() }} ครั้ง 
            รวม {{ number_format($paymentType->calculatePaidAmount(), 2) }} บาท
        </div>
    @else
        <div class="no-history">
            <i class="fas fa-info-circle" style="font-size: 2rem; color: #ccc; margin-bottom: 1rem;"></i>
            <p>ยังไม่มีประวัติการชำระเงินสำหรับรายการนี้</p>
        </div>
    @endif

    <!-- ปุ่มดำเนินการ -->
    <div class="action-buttons">
        @if($paymentType->total_amount - $paymentType->calculatePaidAmount() > 0)
            <a href="{{ route('labour.paymentEdit', $labour->labour_id) }}" class="btn-payment">
                <i class="fas fa-credit-card"></i> ชำระเงิน
            </a>
        @endif
        <button onclick="loadQrCodeData('{{ route('labour.qrcodeDetail', $labour->labour_id) }}')" class="btn-payment btn-secondary">
            <i class="fas fa-list"></i> ดูประวัติทั้งหมด
        </button>
    </div>
</div>