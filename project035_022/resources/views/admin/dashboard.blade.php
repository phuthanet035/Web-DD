@extends('layouts.app')

@section('title', 'แผงควบคุมระบบแจ้งซ่อม - แอดมินและช่าง IT')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">🛠️ แผงควบคุมระบบแจ้งซ่อม IT (Admin Dashboard)</h1>
            <p class="text-slate-500 text-sm mt-1">ศูนย์จัดการคิวงาน คัดกรองความเร่งด่วน และมอบหมายงานซ่อม</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                🟢 เข้าสู่ระบบในฐานะ: {{ Auth::user()->name ?? 'เจ้าหน้าที่ IT' }}
            </span>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">งานทั้งหมด</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($counters['total']) }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-amber-600 font-medium">รอดำเนินการ</span>
            <p class="text-xl font-bold text-amber-600 mt-1">{{ number_format($counters['pending']) }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-blue-600 font-medium">กำลังซ่อม</span>
            <p class="text-xl font-bold text-blue-600 mt-1">{{ number_format($counters['in_progress']) }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-emerald-600 font-medium">เสร็จสิ้นแล้ว</span>
            <p class="text-xl font-bold text-emerald-600 mt-1">{{ number_format($counters['resolved']) }}</p>
        </div>
        <!-- ⭐ Highlight Feature 2: ค้างงานด่วนที่สุด -->
        <div class="bg-red-50 p-4 rounded-xl border border-red-200 shadow-sm col-span-2 sm:col-span-1">
            <span class="text-xs text-red-700 font-bold flex items-center">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-ping mr-1.5"></span> เคสด่วนที่สุดที่ค้าง
            </span>
            <p class="text-xl font-black text-red-700 mt-1">{{ number_format($counters['urgent_pending']) }} รายการ</p>
        </div>
    </div>

    <!-- ⭐ ฟีเจอร์ที่ 2: ฟอร์มค้นหาและคัดกรองงาน (Filter Bar) -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
            <!-- ค้นหาคำค้น -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">ค้นหา (รหัส / ชื่อปัญหา / ผู้แจ้ง)</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="พิมพ์คำค้นหา..."
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">
            </div>

            <!-- กรองสถานะ -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">สถานะงาน</label>
                <select name="status" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white">
                    <option value="">ทั้งหมดทุกสถานะ</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>กำลังซ่อม</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>เสร็จสิ้น</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                </select>
            </div>

            <!-- กรองความเร่งด่วน -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">ความเร่งด่วน (Priority)</label>
                <select name="priority" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white">
                    <option value="">ทุกระดับความด่วน</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>🔴 ด่วนที่สุด</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🟠 เร่งด่วน</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🔵 ปานกลาง</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>⚪ ต่ำ</option>
                </select>
            </div>

            <!-- กรองหมวดหมู่ -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">หมวดหมู่</label>
                <select name="category" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white">
                    <option value="">ทุกหมวดหมู่อุปกรณ์</option>
                    <option value="hardware" {{ request('category') == 'hardware' ? 'selected' : '' }}>ฮาร์ดแวร์</option>
                    <option value="software" {{ request('category') == 'software' ? 'selected' : '' }}>ซอฟต์แวร์</option>
                    <option value="network" {{ request('category') == 'network' ? 'selected' : '' }}>ระบบเครือข่าย</option>
                    <option value="printer" {{ request('category') == 'printer' ? 'selected' : '' }}>เครื่องพิมพ์</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>อื่นๆ</option>
                </select>
            </div>

            <!-- ปุ่มดำเนินการ -->
            <div class="sm:col-span-5 flex justify-end space-x-2 pt-2 border-t border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-medium">
                    รีเซ็ตตัวกรอง
                </a>
                <button type="submit" class="px-5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm">
                    กรองข้อมูล 🔍
                </button>
            </div>
        </form>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">รหัส Ticket</th>
                        <th class="px-4 py-3">ความเร่งด่วน</th>
                        <th class="px-4 py-3">หัวข้อปัญหา & หมวดหมู่</th>
                        <th class="px-4 py-3">ผู้แจ้ง / แผนก</th>
                        <th class="px-4 py-3">สถานะ</th>
                        <th class="px-4 py-3">รูปภาพ</th>
                        <th class="px-4 py-3">วันที่แจ้ง</th>
                        <th class="px-4 py-3 text-right">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tickets as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3.5 font-bold text-blue-600 code-font">
                                {{ $item->ticket_number }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full font-semibold {{ $item->priority_badge_color }} border">
                                    {{ $item->priority_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <div class="font-semibold text-slate-900 truncate">{{ $item->title }}</div>
                                <div class="text-[11px] text-slate-500">{{ $item->category_label }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-medium text-slate-800">{{ $item->requester_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $item->department ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full font-semibold {{ $item->status_badge_color }} border">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($item->image_path)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 font-medium" title="มีรูปภาพหลักฐาน">
                                        📸 มีรูป
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-500">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3.5 text-right space-x-1">
                                <a href="{{ route('admin.tickets.show', $item) }}" class="inline-flex items-center px-2.5 py-1 rounded bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold transition">
                                    ตรวจสอบ / แก้ไข
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-400">
                                ไม่พบรายการแจ้งซ่อมที่ตรงกับเงื่อนไข
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
