@extends('layouts.app')

@section('title', 'หน้าแรก - ระบบแจ้งซ่อม IT')

@section('content')
<div class="space-y-12">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/30 text-blue-200 border border-blue-400/30 backdrop-blur-sm mb-4">
                🚀 เวอร์ชั่นใหม่: เพิ่มระบบแนบรูปถ่าย & จัดลำดับความเร่งด่วน
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                แจ้งซ่อมอุปกรณ์ IT ออนไลน์ สะดวก รวดเร็ว ตรวจสอบได้
            </h1>
            <p class="mt-4 text-blue-100 text-base sm:text-lg leading-relaxed font-light">
                ระบบจัดการงานแจ้งซ่อมคอมพิวเตอร์ โปรแกรม และระบบเครือข่ายสำหรับองค์กร ติดตามสถานะงานได้ตลอด 24 ชั่วโมง พร้อมช่างผู้เชี่ยวชาญดูแลอย่างใกล้ชิด
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-6 py-3.5 rounded-xl bg-white text-blue-700 font-semibold hover:bg-blue-50 transition shadow-lg shadow-black/10">
                    🛠️ แจ้งซ่อมปัญหาใหม่
                </a>
                <a href="{{ route('tickets.track') }}" class="inline-flex items-center px-6 py-3.5 rounded-xl bg-blue-600/40 hover:bg-blue-600/60 text-white font-medium border border-white/20 backdrop-blur-sm transition">
                    🔍 ติดตามสถานะงานซ่อม
                </a>
            </div>
        </div>
        <!-- Decorative Glow -->
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-2xl font-bold">
                📋
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">รายการแจ้งซ่อมทั้งหมด</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }} รายการ</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-2xl font-bold">
                ⏳
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">กำลังดำเนินการซ่อม</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['pending']) }} รายการ</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl font-bold">
                ✅
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">ดำเนินการเสร็จสิ้นแล้ว</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['resolved']) }} รายการ</p>
            </div>
        </div>
    </div>

    <!-- 3 Key Features Highlight (สำหรับอธิบายตอนพรีเซนต์) -->
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="text-2xl font-bold text-slate-900">✨ 3 ฟีเจอร์หลักที่พัฒนาเพิ่มในระบบ</h2>
            <p class="text-slate-500 text-sm mt-2">ยกระดับประสิทธิภาพการซ่อมบำรุงด้วยฟีเจอร์ที่ตรงจุด ตอบโจทย์การใช้งานจริง</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4 font-bold">
                    📸
                </div>
                <h3 class="font-bold text-slate-900 text-lg">1. แนบรูปถ่ายหลักฐานปัญหา</h3>
                <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                    ผู้แจ้งสามารถอัปโหลดภาพหน้าจอ error หรืออุปกรณ์ที่ชำรุด ทำให้ช่างวิเคราะห์ปัญหาได้แม่นยำตั้งแต่ก่อนเข้าหน้างาน
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl mb-4 font-bold">
                    ⚡
                </div>
                <h3 class="font-bold text-slate-900 text-lg">2. จัดหมวดหมู่ & ลำดับความเร่งด่วน</h3>
                <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                    ระบุความเร่งด่วน (Urgent / High / Medium / Low) และแยกประเภทฮาร์ดแวร์ ซอฟต์แวร์ เน็ตเวิร์ก พร้อมระบบ Filter ช่วยช่างจัดคิวงานเร่งด่วนก่อน
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mb-4 font-bold">
                    📝
                </div>
                <h3 class="font-bold text-slate-900 text-lg">3. บันทึกงานช่าง & Timeline ติดตาม</h3>
                <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                    ช่างสามารถพิมพ์สรุปผลการซ่อม/คำแนะนำ ผู้แจ้งสามารถเปิดดูความคืบหน้าแบบ Real-time และเห็นข้อความจากช่างได้ทันที
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
