<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * หน้าแรกของระบบแจ้งซ่อม IT (Public Home)
     */
    public function index()
    {
        // สถิติเบื้องต้นเพื่อความโปร่งใสและน่าเชื่อถือ
        $stats = [
            'total' => Ticket::count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'pending' => Ticket::where('status', 'pending')->count(),
        ];

        return view('tickets.index', compact('stats'));
    }

    /**
     * หน้าฟอร์มสำหรับแจ้งซ่อมปัญหาใหม่
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * บันทึกข้อมูลใบแจ้งซ่อม พร้อมประมวลผลฟีเจอร์ใหม่
     * - แนบรูปถ่ายหลักฐาน (Feature 1)
     * - กำหนดหมวดหมู่และระดับความเร่งด่วน (Feature 2)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:3000',
            'requester_name' => 'required|string|max:150',
            'requester_email' => 'required|email|max:150',
            'requester_phone' => 'nullable|string|max:30',
            'department' => 'nullable|string|max:100',
            // ⭐ ฟีเจอร์ที่ 2: ตรวจสอบหมวดหมู่และระดับความเร่งด่วน
            'category' => 'required|in:hardware,software,network,printer,other',
            'priority' => 'required|in:low,medium,high,urgent',
            // ⭐ ฟีเจอร์ที่ 1: ตรวจสอบรูปภาพหลักฐาน (อนุญาตเฉพาะไฟล์รูปภาพ ไม่เกิน 2MB)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'title.required' => 'กรุณาระบุหัวข้อปัญหาที่ต้องการแจ้งซ่อม',
            'description.required' => 'กรุณากรอกรายละเอียดอาการเสียหรือปัญหาที่พบ',
            'requester_name.required' => 'กรุณาระบุชื่อ-นามสกุลของผู้แจ้ง',
            'requester_email.required' => 'กรุณากรอกอีเมลสำหรับรับการแจ้งเตือน',
            'requester_email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'category.required' => 'กรุณาเลือกหมวดหมู่อุปกรณ์หรือปัญหา',
            'priority.required' => 'กรุณาระบุระดับความเร่งด่วน',
            'image.image' => 'ไฟล์แนบต้องเป็นไฟล์รูปภาพเท่านั้น',
            'image.max' => 'ขนาดไฟล์รูปภาพต้องไม่เกิน 2MB',
        ]);

        // จัดการอัปโหลดไฟล์รูปภาพหลักฐาน (ถ้ามี)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tickets', 'public');
        }

        // สร้างรหัส Ticket อัตโนมัติในรูปแบบ TK-YYYYMMDD-XXXX
        $ticketNumber = 'TK-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'requester_name' => $validated['requester_name'],
            'requester_email' => $validated['requester_email'],
            'requester_phone' => $validated['requester_phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'status' => 'pending',
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('tickets.success', ['ticket_number' => $ticket->ticket_number])
            ->with('success', 'บันทึกคำขอแจ้งซ่อมสำเร็จ รหัสติดตามของคุณคือ: ' . $ticket->ticket_number);
    }

    /**
     * หน้าแสดงความสำเร็จหลังส่งใบแจ้งซ่อม
     */
    public function success($ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->firstOrFail();

        return view('tickets.success', compact('ticket'));
    }

    /**
     * หน้าค้นหาและติดตามสถานะงานซ่อมสำหรับผู้ใช้ทั่วไป
     * แสดง Timeline และข้อความจากช่าง (Feature 3)
     */
    public function track(Request $request)
    {
        $ticket = null;
        $search = $request->input('search');

        if ($search) {
            $ticket = Ticket::where('ticket_number', trim($search))
                ->orWhere('requester_email', trim($search))
                ->latest()
                ->first();
        }

        return view('tickets.track', compact('ticket', 'search'));
    }
}
