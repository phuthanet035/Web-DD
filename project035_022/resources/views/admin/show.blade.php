@extends('layouts.app')

@section('title', 'จัดการใบแจ้งซ่อม #' . $ticket->ticket_number)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Back Link & Header -->
    <div>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center mb-2">
            ← ย้อนกลับไปแผงควบคุม (Dashboard)
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">รายละเอียดใบแจ้งซ่อม</span>
                <h1 class="text-2xl font-black text-slate-900 code-font flex items-center gap-2">
                    {{ $ticket->ticket_number }}
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $ticket->priority_badge_color }} border">
                        {{ $ticket->priority_label }}
                    </span>
                </h1>
            </div>
            <div>
                <span class="text-sm px-3.5 py-1.5 rounded-full font-bold {{ $ticket->status_badge_color }} border">
                    สถานะปัจจุบัน: {{ $ticket->status_label }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Grid: Info (Left) vs Update Controls (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Ticket Information (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Requester Card -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2 mb-3">👤 ข้อมูลผู้แจ้งซ่อม</h2>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block">ชื่อ-นามสกุล</span>
                        <strong class="text-slate-800 text-sm">{{ $ticket->requester_name }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">แผนก / สถานที่</span>
                        <strong class="text-slate-800 text-sm">{{ $ticket->department ?? 'ไม่ได้ระบุ' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">อีเมล</span>
                        <span class="text-slate-800">{{ $ticket->requester_email }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">เบอร์ติดต่อ</span>
                        <span class="text-slate-800">{{ $ticket->requester_phone ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Problem Details Card -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2">📋 รายละเอียดปัญหาที่แจ้ง</h2>
                <div>
                    <span class="text-xs text-slate-400 block">หัวข้อปัญหา</span>
                    <p class="text-base font-bold text-slate-900 mt-0.5">{{ $ticket->title }}</p>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">หมวดหมู่อุปกรณ์</span>
                    <span class="inline-block mt-0.5 px-2.5 py-1 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                        {{ $ticket->category_label }}
                    </span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block mb-1">คำอธิบายอาการเสีย</span>
                    <div class="bg-slate-50 p-3.5 rounded-xl text-slate-800 text-xs whitespace-pre-line leading-relaxed border border-slate-100">
                        {{ $ticket->description }}
                    </div>
                </div>

                <!-- ⭐ ฟีเจอร์ที่ 1: แสดงรูปภาพหลักฐาน -->
                @if($ticket->image_path)
                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-700 block mb-2">📸 รูปภาพหลักฐานที่ผู้ใช้แนบมา:</span>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <a href="{{ asset('storage/' . $ticket->image_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $ticket->image_path) }}" alt="ภาพหลักฐาน" class="max-h-72 rounded-lg border border-slate-300 shadow-sm hover:opacity-95 transition">
                            </a>
                            <span class="text-[11px] text-blue-600 block mt-1">คลิกที่รูปเพื่อเปิดดูขนาดเต็ม</span>
                        </div>
                    </div>
                @else
                    <div class="pt-2 text-xs text-slate-400 italic">
                        (ไม่มีรูปภาพแนบในคำขอนี้)
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Technician Action & Status Update Form -->
        <div class="space-y-6">
            <!-- Update Form Card -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 border-b pb-2 mb-4">⚙️ การดำเนินการของช่าง IT</h2>

                <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- สถานะการซ่อม -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">สถานะงานซ่อม</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white">
                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>⏳ รอดำเนินการ (Pending)</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🔧 กำลังดำเนินการซ่อม (In Progress)</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>✅ ซ่อมเสร็จสิ้น / ปิดงาน (Resolved)</option>
                            <option value="cancelled" {{ $ticket->status == 'cancelled' ? 'selected' : '' }}>❌ ยกเลิกคำขอ (Cancelled)</option>
                        </select>
                    </div>

                    <!-- ⭐ ฟีเจอร์ที่ 2: ปรับระดับความเร่งด่วน -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ปรับระดับความเร่งด่วน</label>
                        <select name="priority" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white">
                            <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>🔴 ด่วนที่สุด (Urgent)</option>
                            <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>🟠 เร่งด่วน (High)</option>
                            <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>🔵 ปานกลาง (Medium)</option>
                            <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>⚪ ต่ำ (Low)</option>
                        </select>
                    </div>

                    <!-- ช่างผู้รับผิดชอบ -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">มอบหมายช่างผู้รับผิดชอบ</label>
                        <select name="assigned_to" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white">
                            <option value="">-- ยังไม่มอบหมายช่าง --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $ticket->assigned_to == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }} ({{ $tech->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ⭐ ฟีเจอร์ที่ 3: บันทึกข้อความการซ่อม / วิธีแก้ไขปัญหา -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1 flex items-center">
                            <span>📝 บันทึกการแก้ไข / หมายเหตุช่าง</span>
                        </label>
                        <p class="text-[11px] text-slate-400 mb-1.5">ข้อความนี้จะแสดงในหน้าติดตามสถานะของผู้ใช้ เพื่อแจ้งความคืบหน้า</p>
                        <textarea name="admin_notes" rows="4"
                            placeholder="เช่น ตรวจสอบพบเพาเวอร์ซัพพลายเสีย ทำการเปลี่ยนตัวใหม่และทดสอบเปิดเครื่อง 30 นาที ใช้งานได้ปกติ"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs">{{ old('admin_notes', $ticket->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-md transition">
                        💾 บันทึกการอัปเดตงาน
                    </button>
                </form>
            </div>

            <!-- Danger Zone (Delete) -->
            <div class="bg-red-50/50 p-4 rounded-2xl border border-red-100">
                <span class="text-xs font-bold text-red-700 block mb-1">โซนอันตราย (Danger Zone)</span>
                <p class="text-[11px] text-slate-500 mb-3">การลบใบแจ้งซ่อมจะลบข้อมูลและไฟล์ภาพแนบถาวร ไม่สามารถกู้คืนได้</p>
                <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบใบแจ้งซ่อมนี้ใช่หรือไม่?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2 rounded-xl bg-white border border-red-200 text-red-600 hover:bg-red-600 hover:text-white font-semibold text-xs transition">
                        🗑️ ลบใบแจ้งซ่อมนี้
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
