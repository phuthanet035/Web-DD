<?php

use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ระบบแจ้งซ่อม IT (IT Repair & Helpdesk System)
|--------------------------------------------------------------------------
| ปรับปรุงใหม่: เสริม 3 ฟีเจอร์สำคัญสำหรับงานบริการไอทีจริง
| 1. ระบบแนบรูปถ่ายหลักฐานปัญหา (Image Attachment)
| 2. ระดับความสำคัญ & หมวดหมู่อุปกรณ์ พร้อมระบบคัดกรองงาน (Priority, Category & Filters)
| 3. ระบบบันทึกหมายเหตุการซ่อมของช่าง & เส้นทางไทม์ไลน์สถานะ (Technician Notes & Status Timeline)
|--------------------------------------------------------------------------
*/

// Public Routes (ผู้ใช้ทั่วไป)
Route::get('/', [TicketController::class, 'index'])->name('home');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/success/{ticket_number}', [TicketController::class, 'success'])->name('tickets.success');
Route::get('/tickets/track', [TicketController::class, 'track'])->name('tickets.track');

// Authentication Routes (เจ้าหน้าที่ IT / ผู้ดูแลระบบ)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin & Staff Routes (ต้องล็อกอิน)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminTicketController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::put('/tickets/{ticket}', [AdminTicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [AdminTicketController::class, 'destroy'])->name('tickets.destroy');
});
