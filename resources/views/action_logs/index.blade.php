@extends('layouts.main_layout')

@section('content')
<div class=" mt-4">
  <h3 class="mb-3">Action Logs</h3>

  {{-- ───── ฟอร์มค้นหา ───── --}}
  <form action="{{ route('action-logs.index') }}" method="GET" class="form-inline mb-3">
    <input
      type="text"
      name="search"
      class="form-control mr-2"
      placeholder="ค้นหา (action, model, user, id, ฯลฯ)"
      value="{{ old('search', $search ?? '') }}"
      style="width: 250px;"
    />
    <button type="submit" class="btn btn-primary mr-2">ค้นหา</button>
    <a href="{{ route('action-logs.index') }}" class="btn btn-secondary">รีเซ็ต</a>
  </form>

  {{-- ───── ตารางแสดงข้อมูล log พร้อมคอลัมน์ Passport / Prefix / Fullname ───── --}}
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="">
        <tr>
          <th class="text-center" style="width:50px;">#</th>
          <th style="width:100px;">User ID</th>
          <th>Action</th>
          <th style="min-width:180px;">Model Type</th>
          <th style="width:80px;">Model ID</th>
          {{-- เพิ่มคอลัมน์ใหม่ --}}
          <th style="width:150px;">Passport Number</th>
          <th style="width:100px;">Prefix</th>
          <th style="min-width:200px;">Fullname</th>
          <th style="width:80px; text-align:center;">View</th>
          <th style="width:140px;">วันที่/เวลา</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          {{-- ตัวแปร record จะอ้างถึง LabourModel ของแถวนี้ (null ถ้าไม่พบ) --}}
          @php
            $labour = $log->record; 
            // หาก record มีอยู่ ให้ดึงค่า จากนั้น fallback เป็น '-' เมื่อไม่มี
            $passport = $labour ? ($labour->labour_passport_number ?? '-') : '-';
            $prefix   = $labour ? ($labour->labour_prefix          ?? '-') : '-';
            $fullname = $labour ? ($labour->labour_fullname        ?? '-') : '-';
          @endphp

          <tr>
            <td class="text-center">{{ $log->id }}</td>
            <td class="text-center">{{ $log->user->name ?: '-' }}</td>
            <td>{{ ucfirst($log->action_type) }}</td>
            <td style="font-family: monospace; font-size:0.9em;">{{ $log->model_type }}</td>
            <td class="text-center">{{ $log->model_id }}</td>

            {{-- แสดงข้อมูลจริงจากตาราง labour --}}
            <td>{{ $passport }}</td>
            <td>{{ $prefix }}</td>
            <td>{{ $fullname }}</td>

            <td class="text-center">
              {{-- ปุ่ม View: เปิด Modal ดู old_values / new_values --}}
              <button
                type="button"
                class="btn btn-sm btn-info view-json"
                data-toggle="modal"
                data-target="#jsonModal"
                data-old='@json($log->old_values)'
                data-new='@json($log->new_values)'
              >
                View
              </button>
            </td>
            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="text-center">ไม่มีข้อมูล</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="d-flex justify-content-center">
  <!-- {!! $logs->withQueryString()->appends(['search' => $search])->links('pagination::bootstrap-4') !!} -->
  {!! $logs
      ->appends(['search' => $search])      {{-- ต่อท้าย search=xxx ในทุกลิงก์แบ่งหน้า --}}
      ->links('pagination::bootstrap-4')
!!}

  </div>
</div>

{{-- ───── Modal แสดง JSON (Old / New) ───── --}}
<div class="modal fade" id="jsonModal" tabindex="-1" role="dialog" aria-labelledby="jsonModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jsonModalLabel">รายละเอียด Log (Old / New)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          {{-- Old Values --}}
          <div class="col-md-6 mb-3">
            <h6>Old Values</h6>
            <pre
              id="modalOld"
              style="background-color:#f5f5f5; padding:10px;
                     max-height:400px; overflow:auto;
                     border:1px solid #ddd; border-radius:4px;">
            </pre>
          </div>
          {{-- New Values --}}
          <div class="col-md-6 mb-3">
            <h6>New Values</h6>
            <pre
              id="modalNew"
              style="background-color:#f5f5f5; padding:10px;
                     max-height:400px; overflow:auto;
                     border:1px solid #ddd; border-radius:4px;">
            </pre>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.view-json').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var oldRaw = btn.getAttribute('data-old');
      var newRaw = btn.getAttribute('data-new');

      function safeParse(str) {
        if (!str || str === 'null') return {};
        try { return JSON.parse(str); }
        catch { return {}; }
      }

      var oldObj = safeParse(oldRaw);
      var newObj = safeParse(newRaw);
      if (typeof oldObj === 'string') oldObj = safeParse(oldObj);
      if (typeof newObj === 'string') newObj = safeParse(newObj);

      document.getElementById('modalOld').textContent = JSON.stringify(oldObj, null, 2);
      document.getElementById('modalNew').textContent = JSON.stringify(newObj, null, 2);
    });
  });
});
</script>

@endsection
