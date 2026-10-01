@extends('layouts.app')

@section('title', 'ติดตามสถานะงานแจ้งซ่อม - ระบบแจ้งซ่อม IT')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <!-- Header & Search Box -->
    <div class="text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">🔍 ติดตามสถานะงานแจ้งซ่อม IT</h1>
        <p class="text-slate-500 text-sm mt-1">กรอกรหัส Ticket (เช่น TK-202610-XXXX) หรืออีเมลที่ใช้แจ้ง เพื่อดูความคืบหน้า</p>

        <form action="{{ route('tickets.track') }}" method="GET" class="mt-6 flex max-w-lg mx-auto gap-2">
            <input type="text" name="search" value="{{ request('search') }}" required
                placeholder="กรอกรหัส Ticket ID หรือ อีเมล..."
                class="flex-1 px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm shadow-sm font-medium">
            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition shadow-md shadow-blue-500/20">
                ค้นหา
            </button>
        </form>
    </div>

    @if(request()->filled('search'))
        @if($ticket)
            <!-- Ticket Found Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
                <!-- Header of Ticket -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-6">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xl font-black text-blue-600 code-font">{{ $ticket->ticket_number }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $ticket->priority_badge_color }} border">
                                {{ $ticket->priority_label }}
                            </span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900 mt-1">{{ $ticket->title }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">แจ้งเมื่อ: {{ $ticket->created_at->format('d/m/Y H:i น.') }}</p>
                    </div>

                    <div class="text-left sm:text-right">
                        <span class="inline-block px-3.5 py-1.5 rounded-full text-sm font-bold {{ $ticket->status_badge_color }} border">
                            {{ $ticket->status_label }}
                        </span>
                        @if($ticket->resolved_at)
                            <p class="text-xs text-emerald-600 font-medium mt-1">เสร็จสิ้นเมื่อ: {{ $ticket->resolved_at->format('d/m/Y H:i น.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Progress Steps (Timeline) -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">ลำดับขั้นตอนการดำเนินงาน</h3>
                    <div class="grid grid-cols-3 gap-2 text-center text-xs font-medium">
                        <!-- Step 1: รับเรื่อง -->
                        <div class="space-y-2">
                            <div class="h-2 rounded-full {{ in_array($ticket->status, ['pending', 'in_progress', 'resolved']) ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                            <span class="{{ in_array($ticket->status, ['pending', 'in_progress', 'resolved']) ? 'text-blue-700 font-bold' : 'text-slate-400' }}">
                                1. รับเรื่องแจ้งซ่อม
                            </span>
                        </div>

                        <!-- Step 2: กำลังซ่อม -->
                        <div class="space-y-2">
                            <div class="h-2 rounded-full {{ in_array($ticket->status, ['in_progress', 'resolved']) ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                            <span class="{{ in_array($ticket->status, ['in_progress', 'resolved']) ? 'text-blue-700 font-bold' : 'text-slate-400' }}">
                                2. กำลังดำเนินการซ่อม
                            </span>
                        </div>

                        <!-- Step 3: เสร็จสิ้น -->
                        <div class="space-y-2">
                            <div class="h-2 rounded-full {{ $ticket->status === 'resolved' ? 'bg-emerald-600' : 'bg-slate-200' }}"></div>
                            <span class="{{ $ticket->status === 'resolved' ? 'text-emerald-700 font-bold' : 'text-slate-400' }}">
                                3. ซ่อมเสร็จสิ้น / ปิดงาน
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Description & Attached Image (Feature 1) -->
                <div class="bg-slate-50 p-4 rounded-xl space-y-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">รายละเอียดปัญหาที่แจ้งไว้:</p>
                    <p class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ $ticket->description }}</p>

                    @if($ticket->image_path)
                        <div class="pt-3 border-t border-slate-200">
                            <p class="text-xs font-bold text-slate-500 mb-2">📸 รูปภาพหลักฐานที่แนบไว้:</p>
                            <a href="{{ asset('storage/' . $ticket->image_path) }}" target="_blank" class="inline-block group">
                                <img src="{{ asset('storage/' . $ticket->image_path) }}" alt="หลักฐานการแจ้งซ่อม" class="h-36 rounded-lg object-cover border border-slate-300 shadow-sm group-hover:opacity-90 transition">
                                <span class="text-xs text-blue-600 hover:underline block mt-1">คลิกเพื่อดูรูปภาพขนาดเต็ม ↗</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- ⭐ ฟีเจอร์ที่ 3: ข้อความตอบกลับ/บันทึกความคืบหน้าจากช่าง IT -->
                @if($ticket->admin_notes)
                    <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl">
                        <div class="flex items-center space-x-2 text-emerald-800 font-bold text-sm mb-1">
                            <span>💬</span>
                            <span>บันทึกความคืบหน้าจากเจ้าหน้าที่ IT / ช่างผู้ดูแล:</span>
                        </div>
                        <p class="text-sm text-emerald-900 whitespace-pre-line pl-6 leading-relaxed">
                            {{ $ticket->admin_notes }}
                        </p>
                    </div>
                @else
                    <div class="bg-amber-50/70 border border-amber-200/80 p-3.5 rounded-xl text-xs text-amber-800 flex items-center">
                        <span class="mr-2 text-base">⏳</span>
                        <span>เจ้าหน้าที่ IT กำลังตรวจสอบคำขอ ยังไม่มีข้อความบันทึกเพิ่มเติมในขณะนี้</span>
                    </div>
                @endif

                <!-- Contact & Requester Info -->
                <div class="border-t pt-4 text-xs text-slate-500 flex flex-wrap justify-between gap-2">
                    <div>ผู้แจ้ง: <strong class="text-slate-700">{{ $ticket->requester_name }}</strong> (แผนก: {{ $ticket->department ?? '-' }})</div>
                    <div>หมวดหมู่: <strong class="text-slate-700">{{ $ticket->category_label }}</strong></div>
                </div>
            </div>
        @else
            <!-- Not Found Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-sm">
                <div class="text-4xl mb-3">🔍❌</div>
                <h3 class="text-lg font-bold text-slate-800">ไม่พบข้อมูลใบแจ้งซ่อม</h3>
                <p class="text-slate-500 text-sm mt-1">โปรดตรวจสอบรหัส Ticket หรือ อีเมลที่ใช้แจ้ง แล้วลองใหม่อีกครั้ง</p>
            </div>
        @endif
    @endif
</div>
@endsection
