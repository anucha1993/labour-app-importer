
<style>
    body, html { background: #f2f4f8; }
    .qr-mobile-container {
        width: 100vw;
        min-height: 100vh;
        margin: 0;
        padding: 0.5rem 0.5rem 2rem 0.5rem;
        background: #fff;
        border-radius: 0;
        box-shadow: 0 2px 16px rgba(80,120,200,0.07);
        font-size: 1.13rem;
    }
    .qr-mobile-header {
        text-align: center;
        margin-bottom: 0.8rem;
    }
    .qr-mobile-header h3 {
        font-size: 1.22rem;
        margin-bottom: 0.5rem;
        color: #2a3b6e;
        letter-spacing: 0.5px;
    }
    .qr-mobile-info {
        background: linear-gradient(90deg, #e3eafc 0%, #f8f9fa 100%);
        border-radius: 10px;
        padding: 0.7rem 1rem;
        margin-bottom: 1.1rem;
        font-size: 1.09rem;
        box-shadow: 0 1px 4px rgba(80,120,200,0.04);
    }
    .qr-mobile-info strong {
        min-width: 90px;
        display: inline-block;
        color: #2a3b6e;
    }
    .qr-mobile-section-title {
        font-size: 1.09rem;
        font-weight: 600;
        margin: 1.1rem 0 0.5rem 0;
        color: #1a237e;
        letter-spacing: 0.2px;
    }
    .qr-mobile-list-group {
        padding-left: 0;
        margin-bottom: 1rem;
    }
    .qr-mobile-list-group li {
        list-style: none;
        background: #f6faff;
        margin-bottom: 0.4rem;
        border-radius: 6px;
        padding: 0.6rem 0.7rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1rem;
        border: 1px solid #e3eafc;
    }
    .qr-mobile-badge {
        font-size: 1rem;
        padding: 0.32em 0.7em;
        border-radius: 1em;
    }
    .qr-mobile-table {
        width: 100vw;
        min-width: 340px;
        font-size: 1.01rem;
        border-collapse: collapse;
        margin-bottom: 1.2rem;
        background: #fff;
        box-shadow: 0 1px 4px rgba(80,120,200,0.04);
    }
    .qr-mobile-table th, .qr-mobile-table td {
        border: 1px solid #e0e0e0;
        padding: 0.45em 0.2em;
        text-align: center;
        word-break: break-word;
    }
    .qr-mobile-table th {
        background: #e3eafc;
        color: #1a237e;
        font-weight: 600;
    }
    .accordion {
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1.2rem;
        background: #f6faff;
        border: 1px solid #e3eafc;
        box-shadow: 0 1px 4px rgba(80,120,200,0.04);
    }
    .accordion-header {
        color: #1a237e;
        font-weight: 600;
        padding: 0.8rem 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 1.05rem;
        border-bottom: 1px solid #dbe6fd;
        transition: background 0.2s;
    }
    .accordion-header.completed {
        background: #e0f7e9;
    }
    .accordion-header.partial, .accordion-header.pending, .accordion-header.danger {
        background: #fff8e1;
    }
    .accordion-header:hover {
        filter: brightness(0.97);
    }
    .accordion-content {
        display: none;
        padding: 0.7rem 1rem 0.7rem 1rem;
        background: #fff;
        animation: fadeIn 0.2s;
    }
    .accordion.active .accordion-content {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @media (max-width: 600px) {
        .qr-mobile-header h3 { font-size: 1.07rem; }
        .qr-mobile-info { font-size: 0.99rem; }
        .qr-mobile-section-title { font-size: 0.99rem; }
        .qr-mobile-table th, .qr-mobile-table td { font-size: 0.93rem; padding: 0.38em 0.1em; }
        .accordion-header { font-size: 0.98rem; }
    }
</style>
<div class="qr-mobile-container">
    <div class="qr-mobile-header">
        <h3>ข้อมูลคนงานเบื้องต้น</h3>
    </div>
    <div class="qr-mobile-info">
        <div><strong>ชื่อ-นามสกุล:</strong> {{ $labour->labour_prefix }}{{ $labour->labour_fullname }}</div>
        <div><strong>เลขที่ Passport:</strong> {{ $labour->labour_passport_number }}</div>
        <div><strong>เลขที่วีซ่า:</strong> {{ $labour->labour_visa_number }}</div>
        <div><strong>บริษัท:</strong> {{ $labour->company->company_name ?? '-' }}</div>
        <div><strong>เอเจนซี่:</strong> {{ $labour->agency->agency_name ?? '-' }}</div>
        <div><strong>สถานะแรงงาน:</strong> {{ $labour->labour_status == 'enable' ? 'Enable' : 'Disable' }}</div>
    </div>
    <div class="qr-mobile-section-title">รายการค้างชำระ</div>
    @if($pendingTypes->count() > 0)
        <ul class="qr-mobile-list-group">
            @foreach($pendingTypes as $type)
                <li>
                    <span>{{ $type->payment_name }}</span>
                    <span class="qr-mobile-badge bg-warning text-dark">
                        {{ number_format($type->total_amount - $type->calculatePaidAmount(), 2) }} บาท
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <span class="qr-mobile-badge bg-success">ไม่มีค้างชำระ</span>
    @endif
    <div class="qr-mobile-section-title">ประวัติการชำระเงิน (แยกตามประเภท)</div>
    @php
        $paymentTypes = $labour->paymentTypes;
    @endphp
    @if($paymentTypes->count() > 0)
        <div style="overflow-x:auto;">
            @foreach($paymentTypes as $idx => $type)
                @php
                    $headerStatusClass = $type->status === 'completed' ? 'completed' : ($type->status === 'partial' ? 'partial' : 'danger');
                @endphp
                <div class="accordion" id="acc-{{ $idx }}">
                    <div class="accordion-header {{ $headerStatusClass }}" onclick="toggleAccordion('acc-{{ $idx }}')">
                        <span>
                            {{ $type->payment_name }}
                            <span class="qr-mobile-badge {{ $type->status === 'completed' ? 'bg-success' : ($type->status === 'partial' ? 'bg-warning text-dark' : 'bg-danger') }}" style="margin-left:0.5em;">
                                {{ $type->status }}
                            </span>
                        </span>
                        <span style="font-weight:400; font-size:0.98em;">ยอดรวม: {{ number_format($type->total_amount,2) }} บาท</span>
                    </div>
                    <div class="accordion-content">
                        @php
                            $histories = $type->histories->sortByDesc('payment_date');
                        @endphp
                        @if($histories->count() > 0)
                            <table class="qr-mobile-table" style="margin-bottom:0.5rem;">
                                <thead>
                                    <tr>
                                        <th>วันที่ชำระ</th>
                                        <th>จำนวนเงิน</th>
                                        <th>ไฟล์หลักฐาน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($histories as $history)
                                        <tr>
                                            <td>{{ $history->payment_date ? $history->payment_date->format('d/m/Y') : '-' }}</td>
                                            <td>{{ number_format($history->amount, 2) }}</td>
                                            <td>
                                                @if($history->proof_file)
                                                    <a href="{{ asset('storage/' . $history->proof_file) }}" target="_blank">ดูไฟล์</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div style="padding:0.5rem 1rem; color:#888; font-size:0.97em;">ไม่มีประวัติการชำระเงิน</div>
                        @endif
                        <div style="padding:0.3rem 1rem 0.7rem 1rem; font-size:0.97em; color:#555; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5em;">
                            <span>ยอดคงเหลือ: <b>{{ number_format($type->total_amount - $type->calculatePaidAmount(),2) }}</b> บาท</span>
                            <a href="{{ route('labour.edit', $labour->labour_id) }}" class="btn btn-primary btn-sm" style="background:#1976d2; border:none; border-radius:20px; padding:0.4em 1.2em; font-size:0.98em; color:#fff;">ชำระเงิน</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <script>
            function toggleAccordion(id) {
                var acc = document.getElementById(id);
                if(acc.classList.contains('active')) {
                    acc.classList.remove('active');
                } else {
                    document.querySelectorAll('.accordion').forEach(function(a){ a.classList.remove('active'); });
                    acc.classList.add('active');
                }
            }
        </script>
    @else
        <span class="text-muted">ไม่มีประวัติการชำระเงิน</span>
    @endif
</div>

