<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActionLog;
use Illuminate\Support\Str;

class ActionLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // สร้าง Query Builder จาก Model ActionLog
        $query = ActionLog::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('action_type', 'LIKE', "%{$search}%")
                  ->orWhere('model_type', 'LIKE', "%{$search}%")
                  ->orWhere('model_id', 'LIKE', "%{$search}%")
                  ->orWhere('user_id', 'LIKE', "%{$search}%")
                  ->orWhere('old_values', 'LIKE', "%{$search}%")
                  ->orWhere('new_values', 'LIKE', "%{$search}%");
            });
        }

        // ดึงข้อมูล ActionLog พร้อม Pagination
        $logs = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(['search' => $search]);

        // “Inject” model instance ของแรงงานเข้าไปในแต่ละ log
        // เพื่อให้ View เอาไปใช้งานได้ทันที
        foreach ($logs as $log) {
            // ถ้า model_type เป็น LabourModel ให้โหลดข้อมูลจาก DB
            // (ใช้ Str::afterLast เพื่อดึงเฉพาะ class name)
            if (class_exists($log->model_type)) {
                // พยายามโหลด Model เมื่อมี model_id ถูกต้อง
                $modelClass = $log->model_type;
                $modelId = $log->model_id;
                // ป้องกันหาก !class_exists, หรือ find() ไม่เจอ ให้ null
                $log->record = $modelClass::find($modelId);
            } else {
                $log->record = null;
            }
        }

        return view('action_logs.index', compact('logs', 'search'));
    }
}
