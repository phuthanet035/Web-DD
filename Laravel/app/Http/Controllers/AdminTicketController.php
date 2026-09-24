<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RepairTicket;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTicketController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = RepairTicket::with(['category', 'technician']);

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Assigned Technician
        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        // Search Keyword
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('ticket_number', 'like', "%{$s}%")
                  ->orWhere('title', 'like', "%{$s}%")
                  ->orWhere('requester_name', 'like', "%{$s}%")
                  ->orWhere('requester_phone', 'like', "%{$s}%")
                  ->orWhere('device_code', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%");
            });
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'         => RepairTicket::count(),
            'pending'       => RepairTicket::where('status', 'pending')->count(),
            'in_progress'   => RepairTicket::whereIn('status', ['assigned', 'in_progress'])->count(),
            'waiting_parts' => RepairTicket::where('status', 'waiting_parts')->count(),
            'resolved'      => RepairTicket::where('status', 'resolved')->count(),
        ];

        $categories = Category::all();
        $technicians = User::whereIn('role', ['technician', 'admin'])->get();

        return view('admin.dashboard', compact('tickets', 'stats', 'categories', 'technicians'));
    }

    public function show(RepairTicket $ticket)
    {
        $ticket->load(['category', 'technician', 'histories.user']);
        $technicians = User::whereIn('role', ['technician', 'admin'])->get();

        return view('admin.show', compact('ticket', 'technicians'));
    }

    public function update(Request $request, RepairTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,waiting_parts,resolved,cancelled',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'resolution_note' => 'nullable|string|max:3000',
            'comment' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $ticket->status;
        $oldAssigned = $ticket->assigned_to;

        $ticket->status = $validated['status'];
        $ticket->priority = $validated['priority'];
        $ticket->assigned_to = $validated['assigned_to'] ?: null;
        $ticket->resolution_note = $validated['resolution_note'] ?? $ticket->resolution_note;

        if ($validated['status'] === 'resolved' && !$ticket->resolved_at) {
            $ticket->resolved_at = now();
        } elseif ($validated['status'] !== 'resolved') {
            $ticket->resolved_at = null;
        }

        $ticket->save();

        // History logs
        $currentUser = Auth::user();
        $logActions = [];

        if ($oldStatus !== $ticket->status) {
            $statusLabels = RepairTicket::statusLabels();
            $logActions[] = "เปลี่ยนสถานะจาก '{$statusLabels[$oldStatus]}' เป็น '{$statusLabels[$ticket->status]}'";
        }

        if ($oldAssigned != $ticket->assigned_to) {
            if ($ticket->assigned_to) {
                $assignedUser = User::find($ticket->assigned_to);
                $logActions[] = "มอบหมายงานให้ช่าง: " . ($assignedUser ? $assignedUser->name : 'N/A');
            } else {
                $logActions[] = "ยกเลิกการมอบหมายช่าง";
            }
        }

        $commentText = !empty($validated['comment']) ? $validated['comment'] : implode(' | ', $logActions);

        if (empty($commentText)) {
            $commentText = 'อัปเดตข้อมูลใบแจ้งซ่อม';
        }

        TicketHistory::create([
            'repair_ticket_id' => $ticket->id,
            'user_id' => $currentUser->id,
            'action' => 'status_changed',
            'comment' => $commentText,
        ]);

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'บันทึกการอัปเดตงานเรียบร้อยแล้ว');
    }

    public function destroy(RepairTicket $ticket)
    {
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'เฉพาะผู้ดูแลระบบ (Admin) เท่านั้นที่สามารถลบรายการได้');
        }

        $ticket->delete();

        return redirect()->route('admin.dashboard')->with('success', 'ลบรายการแจ้งซ่อมสำเร็จ');
    }
}