<?php

namespace App\Http\Controllers\labour;

use Illuminate\Http\Request;
use App\Models\labour\LabourModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\labour\LabourPaymentType;
use App\Models\labour\LabourPaymentHistory;

class LabourPaymentController extends Controller
{

    public function edit(LabourModel $labour)
    {
        //
        $nationality = DB::table('nationality')->get();
        $agency = DB::table('agency')->get();
        $company = DB::table('company')->get();

        $labour = LabourModel::with(['paymentTypes.histories'])
            ->where('labour_id', $labour->labour_id)
            ->leftJoin('company', 'company.company_id', 'labour.company_id')
            ->leftJoin('agency', 'agency.agency_id', 'labour.labour_agency')
            ->leftJoin('nationality', 'nationality.code', 'labour.labour_nationality')
            ->groupBy('labour.labour_id')
            ->first();

        // Format payment data for view
        $paymentTypes = $labour->paymentTypes ?? collect([]);

        return view('labour.form-payment', compact('nationality', 'agency', 'company', 'labour', 'paymentTypes'));
    }

    public function storePaymentType(Request $request)
    {
        $request->validate([
            'labour_id' => 'required|exists:labour,labour_id',
            'payment_name' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'deduction_type' => 'required|in:salary,self_paid',
            'note' => 'nullable|string'
        ]);

        $paymentType = LabourPaymentType::create($request->all());
        return response()->json($paymentType);
    }

    public function updatePaymentType(Request $request, LabourPaymentType $paymentType)
    {
        $request->validate([
            'payment_name' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'deduction_type' => 'required|in:salary,self_paid',
            'note' => 'nullable|string'
        ]);

        $paymentType->update($request->all());
        return response()->json($paymentType);
    }

    public function deletePaymentType(LabourPaymentType $paymentType)
    {
        $paymentType->delete();
        return response()->json(['message' => 'Payment type deleted successfully']);
    }

    public function storePaymentHistory(Request $request)
    {
        $request->validate([
            'payment_type_id' => 'required|exists:labour_payment_types,payment_type_id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date_format:Y-m-d\TH:i',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // ตรวจสอบว่าจำนวนเงินที่จะชำระรวมกับที่ชำระไปแล้วไม่เกินยอดที่ต้องชำระ
        $paymentType = LabourPaymentType::findOrFail($request->payment_type_id);
        $totalPaid = $paymentType->histories()->sum('amount');
        $newAmount = $request->amount;

        if ($totalPaid + $newAmount > $paymentType->total_amount) {
            return response()->json([
                'error' => 'ไม่สามารถชำระเงินเกินยอดที่กำหนดได้',
                'remaining' => $paymentType->total_amount - $totalPaid
            ], 422);
        }

        $paymentHistory = new LabourPaymentHistory($request->all());
        
        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('payment_proofs', $filename, 'public');
            $paymentHistory->proof_file = $filename;
        }

        $paymentHistory->save();
        
        // Update payment type status
        $paymentHistory->paymentType->updateStatus();

        return response()->json($paymentHistory);
    }

    public function deletePaymentHistory(LabourPaymentHistory $paymentHistory)
    {
        // Delete proof file if exists
        if ($paymentHistory->proof_file) {
            Storage::disk('public')->delete('payment_proofs/' . $paymentHistory->proof_file);
        }

        $paymentType = $paymentHistory->paymentType;
        $paymentHistory->delete();
        
        // Update payment type status
        $paymentType->updateStatus();

        return response()->json(['message' => 'Payment history deleted successfully']);
    }
}
