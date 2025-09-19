@php
    $histories = $type->histories->sortByDesc('payment_date');
    $paidAmount = $type->calculatePaidAmount();
    $remainingAmount = $type->total_amount - $paidAmount;
@endphp

<div class="payment-detail-section">
    <!-- Summary Info -->
    <div class="detail-summary">
        <div class="summary-row">
            <div class="summary-item">
                <span class="summary-label">จำนวนเงินรวม:</span>
                <span class="summary-amount total">{{ number_format($type->total_amount, 2) }} บาท</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">ชำระแล้ว:</span>
                <span class="summary-amount paid">{{ number_format($paidAmount, 2) }} บาท</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">คงเหลือ:</span>
                <span class="summary-amount remaining">{{ number_format($remainingAmount, 2) }} บาท</span>
            </div>
        </div>
    </div>

    @if($histories->count() > 0)
        <!-- Payment History Table -->
        <div class="history-section">
            <h6 style="margin: 1rem 0 0.5rem 0; color: #333; font-size: 0.9rem;">ประวัติการชำระเงิน</h6>
            <div class="history-table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>วันที่ชำระ</th>
                            <th class="text-center">จำนวนเงิน</th>
                            <th class="text-center">หลักฐาน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td>
                                    <div class="date-info">
                                        <div class="date-main">{{ $history->payment_date ? $history->payment_date->format('d/m/Y') : '-' }}</div>
                                        @if($history->payment_date)
                                            <div class="date-time">{{ $history->payment_date->format('H:i น.') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="amount-history">{{ number_format($history->amount, 2) }} บาท</span>
                                </td>
                                <td class="text-center">
                                    @if($history->proof_file)
                                        <a href="{{ asset('storage/' . $history->proof_file) }}" 
                                           target="_blank" 
                                           class="proof-link">
                                            📄 ดูไฟล์
                                        </a>
                                    @else
                                        <span class="no-proof">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="no-history">
            <div class="no-history-icon">📝</div>
            <div class="no-history-text">ยังไม่มีประวัติการชำระเงิน</div>
        </div>
    @endif

    @if($remainingAmount > 0)
        <div class="payment-action">
            <a href="{{ route('labour.paymentEdit', $labour->labour_id) }}" class="btn-pay-now">
                ชำระเงิน {{ number_format($remainingAmount, 2) }} บาท
            </a>
        </div>
    @endif
</div>

<style>
.payment-detail-section {
    background: white;
    border-radius: 8px;
    padding: 0;
}

.detail-summary {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.summary-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: space-around;
}

.summary-item {
    text-align: center;
    min-width: 120px;
}

.summary-label {
    display: block;
    font-size: 0.75rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.summary-amount {
    display: block;
    font-weight: 600;
    font-size: 0.9rem;
}

.summary-amount.total {
    color: #333;
}

.summary-amount.paid {
    color: #4CAF50;
}

.summary-amount.remaining {
    color: #f44336;
}

.history-section {
    margin-bottom: 1rem;
}

.history-table-wrapper {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8rem;
}

.history-table thead th {
    background: #f5f5f5;
    padding: 0.6rem 0.5rem;
    font-weight: 600;
    font-size: 0.75rem;
    color: #333;
    border-bottom: 1px solid #e0e0e0;
}

.history-table tbody td {
    padding: 0.6rem 0.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.history-table tbody tr:last-child td {
    border-bottom: none;
}

.date-info {
    text-align: left;
}

.date-main {
    font-weight: 500;
    color: #333;
}

.date-time {
    font-size: 0.7rem;
    color: #666;
    margin-top: 0.1rem;
}

.amount-history {
    color: #4CAF50;
    font-weight: 600;
}

.proof-link {
    color: #2196F3;
    text-decoration: none;
    font-size: 0.75rem;
    padding: 0.2rem 0.4rem;
    border: 1px solid #2196F3;
    border-radius: 12px;
    transition: all 0.2s;
}

.proof-link:hover {
    background: #2196F3;
    color: white;
    text-decoration: none;
}

.no-proof {
    color: #999;
    font-size: 0.75rem;
}

.no-history {
    text-align: center;
    padding: 2rem 1rem;
    color: #666;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.no-history-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.no-history-text {
    font-size: 0.85rem;
}

.payment-action {
    text-align: center;
    padding-top: 0.5rem;
}

.btn-pay-now {
    background: linear-gradient(135deg, #4CAF50, #45a049);
    color: white;
    text-decoration: none;
    padding: 0.6rem 1.5rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
}

.btn-pay-now:hover {
    background: linear-gradient(135deg, #45a049, #4CAF50);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .summary-row {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .summary-item {
        min-width: auto;
    }
    
    .history-table {
        font-size: 0.75rem;
    }
    
    .history-table th,
    .history-table td {
        padding: 0.5rem 0.3rem;
    }
    
    .btn-pay-now {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
    }
}
</style>