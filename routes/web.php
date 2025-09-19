<?php

use App\Http\Controllers\labour\labourMassUpdateController;
use App\Http\Controllers\labour\LabourPaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\settings\settingsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//setting
Route::get('/settings',[settingsController::class,'index'])->name('setting.index');
Route::post('/settings/update',[settingsController::class,'update'])->name('setting.update');
//member
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/member', [App\Http\Controllers\member\AuthController::class, 'index'])->name('member.index')->middleware('type');
Route::post('/member/update{id}', [App\Http\Controllers\member\AuthController::class, 'update'])->name('member.update');
Route::get('/member/ajax', [App\Http\Controllers\member\AuthController::class, 'member_ajax'])->name('member.indexAjax');
Route::get('/member/delete/{id}', [App\Http\Controllers\member\AuthController::class, 'destroy'])->name('member.delete');

//company
Route::get('/company', [App\Http\Controllers\company\CompanyController::class, 'index'])->name('company.index');
Route::post('/company/store', [App\Http\Controllers\company\CompanyController::class, 'store'])->name('company.store');
Route::get('/company/form-add', [App\Http\Controllers\company\CompanyController::class, 'create'])->name('company.create');
Route::get('/company/form-edit/{companyModel}', [App\Http\Controllers\company\CompanyController::class, 'edit'])->name('company.edit');
Route::put('/company/update/{companyModel}', [App\Http\Controllers\company\CompanyController::class, 'update'])->name('company.update');
Route::get('/company/delete/{companyModel}', [App\Http\Controllers\company\CompanyController::class, 'destroy'])->name('company.delete')->middleware('type');

// select address
Route::post('/province', [App\Http\Controllers\company\CompanyController::class, 'province'])->name('province');
Route::post('/amphur', [App\Http\Controllers\company\CompanyController::class, 'amphur'])->name('amphur');
Route::post('/district', [App\Http\Controllers\company\CompanyController::class, 'district'])->name('district');

//Labour Search Routes
Route::get('/labour/passport/{passportNumber}', [App\Http\Controllers\labour\LabourController::class, 'findByPassport'])->name('labour.findByPassport');

//Payment Routes
Route::post('/labour/payment-type', [LabourPaymentController::class, 'storePaymentType']);
Route::post('/labour/payment-type/{paymentType}', [LabourPaymentController::class, 'updatePaymentType']);
Route::delete('/labour/payment-type/{paymentType}', [LabourPaymentController::class, 'deletePaymentType']);
Route::post('/labour/payment-history', [LabourPaymentController::class, 'storePaymentHistory']);
Route::delete('/labour/payment-history/{paymentHistory}', [LabourPaymentController::class, 'deletePaymentHistory']);
Route::delete('/labour/payment-history/{paymentHistory}', [LabourPaymentController::class, 'deletePaymentHistory']);

//agency
Route::get('agency',[\App\Http\Controllers\agency\AgencyController::class,'index'])->name('agency.index');
Route::get('agency/form-add',[\App\Http\Controllers\agency\AgencyController::class,'create'])->name('agency.create');
Route::post('agency/store',[\App\Http\Controllers\agency\AgencyController::class,'store'])->name('agency.store');
Route::get('agency/form-edit/{agencyModel}',[\App\Http\Controllers\agency\AgencyController::class,'edit'])->name('agency.edit');
Route::put('agency/update/{agencyModel}',[\App\Http\Controllers\agency\AgencyController::class,'update'])->name('agency.update');
Route::get('agency/delete/{agencyModel}',[\App\Http\Controllers\agency\AgencyController::class,'destroy'])->name('agency.delete')->middleware('type');

// Labour Model
Route::get('labour',[\App\Http\Controllers\labour\LabourController::class,'index'])->name('labour.index');
Route::get('labour/form-add',[\App\Http\Controllers\labour\LabourController::class,'create'])->name('labour.create');
// QR Code worker detail
Route::get('labour/{labour}/qrcode-detail', [\App\Http\Controllers\labour\LabourController::class, 'qrcodeDetail'])->name('labour.qrcodeDetail');
Route::get('labour/{labour}/payment-detail/{paymentType}', [\App\Http\Controllers\labour\LabourController::class, 'paymentDetail'])->name('labour.paymentDetail');
Route::post('labour/store',[\App\Http\Controllers\labour\LabourController::class,'store'])->name('labour.store');
Route::get('labour/form-edit/{labourModel}',[\App\Http\Controllers\labour\LabourController::class,'edit'])->name('labour.edit');
Route::get('labour/form-show/{labourModel}',[\App\Http\Controllers\labour\LabourController::class,'show'])->name('labour.show');
Route::put('labour/update/{labourModel}',[\App\Http\Controllers\labour\LabourController::class,'update'])->name('labour.update');
Route::get('labour/delete/{labourModel}',[\App\Http\Controllers\labour\LabourController::class,'destroy'])->name('labour.delete')->middleware('type');
Route::get('labour/delete/file/passport/{labourModel}',[\App\Http\Controllers\labour\LabourController::class,'deleteFilePassport'])->name('labour.deleteFilePassport');
Route::get('labour/delete/file/visa/{labourModel}',[\App\Http\Controllers\labour\LabourController::class,'deleteFileVisa'])->name('labour.deleteFileVisa');
Route::get('labour/delete/file/work/{labourModel}',[\App\Http\Controllers\labour\LabourController::class,'deleteFileWork'])->name('labour.deleteFileWork');

//Import Labour
Route::get('import/labour/form',[\App\Http\Controllers\import\labour\ImportLabour::class,'importform'])->name('labour.importform');
Route::post('import/labour-excel',[\App\Http\Controllers\import\labour\ImportLabour::class,'import'])->name('import-excel.labour');

//Notification 
Route::get('/',[\App\Http\Controllers\notification\NotificationController::class,'index'])->name('dashboard');
Route::get('totalnotify',[\App\Http\Controllers\notification\NotificationController::class,'notify'])->name('dashboard.notify');

//ExportExpire

Route::get('export/expire/visa',[\App\Http\Controllers\ExportExcelExpire\LabourExpireVisaController::class,'export'])->name('ExportExpire.Visa');
Route::get('export/expire/passport',[\App\Http\Controllers\ExportExcelExpire\LabourExpirePassportController::class,'export'])->name('ExportExpire.passport');
Route::get('export/expire/workpremit',[\App\Http\Controllers\ExportExcelExpire\LabourExpireWorkpremitController::class,'export'])->name('ExportExpire.workpremit');
Route::get('export/expire/day90',[\App\Http\Controllers\ExportExcelExpire\LabourExpireDay90Controller::class,'export'])->name('ExportExpire.day90');

//Report

Route::get('Report',[\App\Http\Controllers\report\ReportLabourController::class,'index'])->name('report.index');

Route::post('Report/custom/pdf',[\App\Http\Controllers\PDF\labourCustomController::class,'exportPDF'])->name('report.customPDF');

//ExportCustom

Route::post('Report/custom/date',[\App\Http\Controllers\PDF\labourCustomDateController::class,'exportto'])->name('report.customdate');

//MassUpdate

Route::post('labour/massupdate',[labourMassUpdateController::class,'massupdate'])->name('labour.massupdate');


//ActionsLog

Route::middleware(['auth'])->group(function () {
    Route::get('/action-logs', [App\Http\Controllers\ActionLogController::class, 'index'])
        ->name('action-logs.index');
});

Route::get('labour/{labour}/qrcode-detail', [\App\Http\Controllers\labour\LabourController::class, 'qrcodeDetail'])->name('labour.qrcodeDetail');

// Labour Payment 
Route::get('labour/{labour}/payment-edit', [\App\Http\Controllers\labour\LabourPaymentController::class, 'edit'])->name('labour.paymentEdit');