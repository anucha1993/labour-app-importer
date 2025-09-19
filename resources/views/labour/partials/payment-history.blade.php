@php
    $histories = $type->histories->sortByDesc('payment_date');
    $paidAmount = $type->calculatePaidAmount();
    $remainingAmount = $type->total_amount - $paidAmount;
@endphp

<div class="payment-summary">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
        <div>
            <div class="summary-label">จำนวนเงินรวม</div>
            <div class="summary-value">{{ number_format($type->total_amount, 2) }} บาท</div>
        </div>
        <div>
            <div class="summary-label">จำนวนที่ชำระแล้ว</div>
            <div class="summary-value" style="color: #4CAF50;">{{ number_format($paidAmount, 2) }} บาท</div>
        </div>
        <div>
            <div class="summary-label">คงเหลือ</div>
            <div class="summary-value" style="color: #f44336;">{{ number_format($remainingAmount, 2) }} บาท</div>
        </div>
    </div>
</div>

@if($histories->count() > 0)
    <div class="payment-history">
        <h5 style="margin: 0 0 1rem 0; font-size: 1rem; color: #333;">ประวัติการชำระ</h5>
        <div class="history-list">
            @foreach($histories as $history)
                <div class="history-item">
                    <div class="history-date">
                        <div style="font-weight: 600; color: #333;">{{ $history->payment_date ? $history->payment_date->format('d/m/Y') : '-' }}</div>
                        <div style="font-size: 0.8rem; color: #666;">{{ $history->payment_date ? $history->payment_date->format('H:i น.') : '' }}</div>
                    </div>
                    <div class="history-amount">
                        <div style="font-weight: 600; color: #4CAF50;">+{{ number_format($history->amount, 2) }} บาท</div>
                        @if($history->proof_file)
                            <a href="{{ asset('storage/' . $history->proof_file) }}" target="_blank" style="font-size: 0.8rem; color: #2196F3; text-decoration: none;">
                                📄 ดูหลักฐาน
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div style="text-align: center; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; color: #666;">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📝</div>
        <div>ยังไม่มีประวัติการชำระเงิน</div>
    </div>
@endif

@if($remainingAmount > 0)
    <div style="text-align: center; margin-top: 1rem;">
        <a href="{{ route('labour.paymentEdit', $labour->labour_id) }}" 
           class="payment-btn">
            ชำระเงิน
        </a>
    </div>
@endif

<style>
.payment-summary {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #e0e0e0;
}

.summary-label {
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 0.25rem;
}

.summary-value {
    font-weight: 700;
    font-size: 1.1rem;
    color: #333;
}

.payment-history {
    margin-top: 1rem;
}

.history-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
    background: #fff;
}

.history-item:last-child {
    border-bottom: none;
}

.history-item:hover {
    background: #f8f9fa;
}

.history-date {
    flex: 1;
}

.history-amount {
    text-align: right;
}

.payment-btn {
    display: inline-block;
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    transition: all 0.3s ease;
}

.payment-btn:hover {
    background: linear-gradient(135deg, #45a049 0%, #4CAF50 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    color: white;
    text-decoration: none;
}
</style>