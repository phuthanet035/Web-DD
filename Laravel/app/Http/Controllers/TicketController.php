<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RepairTicket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tickets')->get();
        $stats = [
            'total' => RepairTicket::count(),
            'pending' => RepairTicket::where('status', 'pending')->count(),
            'in_progress' => RepairTicket::whereIn('status', ['assigned', 'in_progress'])->count(),
            'resolved' => RepairTicket::where('status', 'resolved')->count(),
        ];
        $recentTickets = RepairTicket::with('category')->latest()->take(5)->get();

        return view('home', compact('categories', 'stats', 'recentTickets'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->query('category_id');
        return view('tickets.create', compact('categories', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:3000',
            'location' => 'required|string|max:255',
            'device_code' => 'nullable|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
            'requester_name' => 'required|string|max:255',
            'requester_phone' => 'required|string|max:50',
            'requester_email' => 'nullable|email|max:255',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'category_id.required' => 'กรุณาเลือกหมวดหมู่อุปกรณ์ที่ต้องการแจ้งซ่อม',
            'title.required' => 'กรุณาระบุหัวข้อปัญหาหรืออาการเสีย',
            'description.required' => 'กรุณาระบุรายละเอียดปัญหาที่พบ',
            'location.required' => 'กรุณาระบุสถานที่/แผนก/ห้องที่อุปกรณ์ตั้งอยู่',
            'priority.required' => 'กรุณาเลือกระดับความเร่งด่วน',
            'requester_name.required' => 'กรุณาระบุชื่อผู้แจ้งซ่อม',
            'requester_phone.required' => 'กรุณาระบุเบอร์โทรศัพท์ติดต่อ',
            'attachment.image' => 'ไฟล์แนบต้องเป็นรูปภาพเท่านั้น (JPG, PNG, WEBP)',
            'attachment.max' => 'ขนาดไฟล์รูปภาพต้องไม่เกิน 5MB',
        ]);

        // Generate Ticket Number: IT-YYYYMMDD-XXX
        $prefix = 'IT-' . date('Ymd') . '-';
        $todayCount = RepairTicket::whereDate('created_at', today())->count() + 1;
        $ticketNumber = $prefix . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

        while (RepairTicket::where('ticket_number', $ticketNumber)->exists()) {
            $todayCount++;
            $ticketNumber = $prefix . str_pad($todayCount, 3, '0', STR_PAD_LEFT);
        }

        $validated['ticket_number'] = $ticketNumber;
        $validated['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('tickets', 'public');
            $validated['attachment'] = $path;
        }

        $ticket = RepairTicket::create($validated);

        TicketHistory::create([
            'repair_ticket_id' => $ticket->id,
            'action' => 'created',
            'comment' => 'ส่งคำขอแจ้งซ่อมเข้าระบบสำเร็จ หมายเลข ' . $ticket->ticket_number,
        ]);

        return redirect()->route('tickets.success', $ticket->ticket_number)
            ->with('success', 'บันทึกคำขอแจ้งซ่อมเรียบร้อยแล้ว!');
    }

    public function success($ticketNumber)
    {
        $ticket = RepairTicket::with(['category'])->where('ticket_number', $ticketNumber)->firstOrFail();
        return view('tickets.success', compact('ticket'));
    }

    public function track(Request $request)
    {
        $search = trim($request->input('search', ''));
        $ticket = null;
        $tickets = null;

        if ($search !== '') {
            // First check if exact ticket number match
            $ticket = RepairTicket::with(['category', 'technician', 'histories.user'])
                ->where('ticket_number', $search)
                ->first();

            // If not found by ticket number, search by phone number
            if (!$ticket) {
                $tickets = RepairTicket::with(['category', 'technician'])
                    ->where('requester_phone', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->latest()
                    ->get();

                if ($tickets->count() === 1) {
                    $ticket = RepairTicket::with(['category', 'technician', 'histories.user'])->find($tickets->first()->id);
                    $tickets = null;
                }
            }
        }

        return view('tickets.track', compact('ticket', 'tickets', 'search'));
    }
}