<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'requester_name',
        'requester_email',
        'requester_phone',
        'department',
        'status',
        'image_path',    // ฟีเจอร์ที่ 1: รูปภาพหลักฐาน
        'category',      // ฟีเจอร์ที่ 2: หมวดหมู่อุปกรณ์
        'priority',      // ฟีเจอร์ที่ 2: ลำดับความสำคัญ
        'admin_notes',   // ฟีเจอร์ที่ 3: บันทึกของช่าง
        'assigned_to',
        'resolved_at',   // ฟีเจอร์ที่ 3: เวลาปิดงาน
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Helper: ชื่อหมวดหมู่ภาษาไทย
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'hardware' => 'ฮาร์ดแวร์ / เครื่องคอมพิวเตอร์',
            'software' => 'ซอฟต์แวร์ / โปรแกรม',
            'network' => 'ระบบเครือข่าย / อินเทอร์เน็ต',
            'printer' => 'เครื่องพิมพ์ / สแกนเนอร์',
            default => 'อื่นๆ',
        };
    }

    // Helper: ระดับความสำคัญภาษาไทย
    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'ด่วนที่สุด (Urgent)',
            'high' => 'เร่งด่วน (High)',
            'medium' => 'ปานกลาง (Medium)',
            'low' => 'ต่ำ (Low)',
            default => 'ปกติ',
        };
    }

    // Helper: สีป้ายกำกับ Priority
    public function getPriorityBadgeColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'bg-red-100 text-red-700 border-red-300',
            'high' => 'bg-orange-100 text-orange-700 border-orange-300',
            'medium' => 'bg-blue-100 text-blue-700 border-blue-300',
            'low' => 'bg-gray-100 text-gray-700 border-gray-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }

    // Helper: สถานะภาษาไทย
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'รอดำเนินการ',
            'in_progress' => 'กำลังดำเนินการซ่อม',
            'resolved' => 'ซ่อมเสร็จสิ้น',
            'cancelled' => 'ยกเลิกคำขอ',
            default => 'ไม่ระบุ',
        };
    }

    // Helper: สีป้ายกำกับสถานะ
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-700 border-amber-300',
            'in_progress' => 'bg-blue-100 text-blue-700 border-blue-300',
            'resolved' => 'bg-emerald-100 text-emerald-700 border-emerald-300',
            'cancelled' => 'bg-rose-100 text-rose-700 border-rose-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
}
