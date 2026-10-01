<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTicketController extends Controller
{
    /**
     * แดชบอร์ดสรุปภาพรวมงานแจ้งซ่อม พร้อมระบบคัดกรองงาน (Feature 2)
     */
    public function dashboard(Request $request)
    {
        $query = Ticket::query()->with('technician');

        // ฟิลเตอร์ตามสถานะงานซ่อม
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ⭐ ฟีเจอร์ที่ 2: ฟิลเตอร์ตามระดับความเร่งด่วน (Priority)
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // ⭐ ฟีเจอร์ที่ 2: ฟิลเตอร์ตามหมวดหมู่อุปกรณ์ (Category)
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // ค้นหาตามคำค้น (รหัส Ticket, หัวข้อ, หรือชื่อผู้แจ้ง)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('ticket_number', 'like', "%{$q}%")
                    ->orWhere('title', 'like', "%{$q}%")
                    ->orWhere('requester_name', 'like', "%{$q}%");
            });
        }

        // เรียงลำดับงาน: งานด่วนที่สุดขึ้นก่อน ตามด้วยวันที่แจ้งล่าสุด
        $tickets = $query->orderByRaw("CASE 
            WHEN priority = 'urgent' THEN 1 
            WHEN priority = 'high' THEN 2 
            WHEN priority = 'medium' THEN 3 
            ELSE 4 END")
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // สถิติสรุปงานในแดชบอร์ด
        $counters = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'urgent_pending' => Ticket::where('priority', 'urgent')->whereIn('status', ['pending', 'in_progress'])->count(),
        ];

        return view('admin.dashboard', compact('tickets', 'counters'));
    }

    /**
     * แสดงรายละเอียดใบแจ้งซ่อม พร้อมรูปภาพหลักฐานและบันทึกของช่าง
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('technician');
        $technicians = User::all();

        return view('admin.show', compact('ticket', 'technicians'));
    }

    /**
     * อัปเดตสถานะงานซ่อม และบันทึกข้อความช่าง (Feature 3)
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            // ⭐ ฟีเจอร์ที่ 3: บันทึกหมายเหตุการซ่อม / วิธีแก้ไขปัญหา
            'admin_notes' => 'nullable|string|max:3000',
        ]);

        // ถ้าอัปเดตสถานะเป็น 'resolved' (ซ่อมเสร็จ) ให้บันทึกเวลาปิดงานอัตโนมัติ
        if ($validated['status'] === 'resolved' && $ticket->status !== 'resolved') {
            $ticket->resolved_at = now();
        } elseif ($validated['status'] !== 'resolved') {
            $ticket->resolved_at = null;
        }

        $ticket->status = $validated['status'];
        $ticket->priority = $validated['priority'];
        $ticket->assigned_to = $validated['assigned_to'] ?? null;
        $ticket->admin_notes = $validated['admin_notes'] ?? null;
        $ticket->save();

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', "อัปเดตข้อมูลใบแจ้งซ่อมรหัส {$ticket->ticket_number} เรียบร้อยแล้ว");
    }

    /**
     * ลบใบแจ้งซ่อม และไฟล์แนบรูปภาพ (ถ้ามี)
     */
    public function destroy(Ticket $ticket)
    {
        $ticketNumber = $ticket->ticket_number;

        // ลบไฟล์ภาพจากดิสก์ (ฟีเจอร์ที่ 1)
        if ($ticket->image_path && Storage::disk('public')->exists($ticket->image_path)) {
            Storage::disk('public')->delete($ticket->image_path);
        }

        $ticket->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', "ลบใบแจ้งซ่อมรหัส {$ticketNumber} เรียบร้อยแล้ว");
    }
}
