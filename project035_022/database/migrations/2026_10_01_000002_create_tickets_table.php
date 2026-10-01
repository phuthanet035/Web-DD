<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ระบบแจ้งซ่อม IT พร้อมรองรับ 3 ฟีเจอร์ใหม่
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // เช่น IT-202610-ABCD
            $table->string('title');                  // หัวข้อการแจ้งซ่อม
            $table->text('description');              // รายละเอียดของปัญหา
            
            // ข้อมูลผู้แจ้งซ่อม
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('requester_phone')->nullable();
            $table->string('department')->nullable();   // แผนก หรือ ห้องทำงาน
            
            // สถานะของงานซ่อม (pending = รอดำเนินการ, in_progress = กำลังซ่อม, resolved = เสร็จสิ้น, cancelled = ยกเลิก)
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'cancelled'])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | ⭐ ฟีเจอร์ที่ 1: ระบบแนบรูปถ่ายหลักฐานปัญหา (Image Attachment)
            |--------------------------------------------------------------------------
            */
            $table->string('image_path')->nullable(); // จัดเก็บพาธรูปภาพหลักฐาน เช่น tickets/sample.jpg

            /*
            |--------------------------------------------------------------------------
            | ⭐ ฟีเจอร์ที่ 2: ระดับความสำคัญ & หมวดหมู่อุปกรณ์ (Priority & Category)
            |--------------------------------------------------------------------------
            */
            $table->enum('category', ['hardware', 'software', 'network', 'printer', 'other'])->default('hardware');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            /*
            |--------------------------------------------------------------------------
            | ⭐ ฟีเจอร์ที่ 3: ระบบบันทึกหมายเหตุการซ่อมของช่าง & เวลาปิดงาน (Technician Notes)
            |--------------------------------------------------------------------------
            */
            $table->text('admin_notes')->nullable();   // บันทึกรายละเอียดการตรวจสอบ/วิธีแก้ไขของช่าง
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable(); // วัน-เวลาที่ดำเนินการซ่อมเสร็จสิ้น

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
