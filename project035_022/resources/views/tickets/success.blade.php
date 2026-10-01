@extends('layouts.app')

@section('title', 'ส่งคำขอแจ้งซ่อมสำเร็จ - ระบบแจ้งซ่อม IT')

@section('content')
<div class="max-w-2xl mx-auto text-center py-6">
    <!-- Success Badge -->
    <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-6 shadow-sm">
        🎉
    </div>

    <h1 class="text-3xl font-extrabold text-slate-900">ส่งคำขอแจ้งซ่อมเรียบร้อยแล้ว!</h1>
    <p class="text-slate-600 text-base mt-2">
        ระบบได้รับข้อมูลการแจ้งซ่อมของท่านแล้ว เจ้าหน้าที่ IT จะเร่งดำเนินการตามลำดับความเร่งด่วน
    </p>

    <!-- Ticket Code Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 mt-8 shadow-sm text-left">
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">รหัสติดตามการแจ้งซ่อม (Ticket ID)</span>
                <span class="text-2xl font-black text-blue-600 code-font mt-0.5 block tracking-wide">{{ $ticket->ticket_number }}</span>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $ticket->status_badge_color }} border">
                {{ $ticket->status_label }}
            </span>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">หัวข้อปัญหา:</span>
                <span class="font-medium text-slate-900 text-right">{{ $ticket->title }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">หมวดหมู่:</span>
                <span class="font-medium text-slate-800">{{ $ticket->category_label }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">ระดับความเร่งด่วน:</span>
                <span class="font-semibold text-slate-800">{{ $ticket->priority_label }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">ผู้แจ้ง:</span>
                <span class="font-medium text-slate-800">{{ $ticket->requester_name }} ({{ $ticket->department ?? 'ไม่ระบุแผนก' }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">วันที่ส่งคำขอ:</span>
                <span class="text-slate-700">{{ $ticket->created_at->format('d/m/Y H:i น.') }}</span>
            </div>
            @if($ticket->image_path)
                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="text-slate-500">รูปภาพหลักฐาน:</span>
                    <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200">แนบไฟล์แล้ว ✔️</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('tickets.track', ['search' => $ticket->ticket_number]) }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition shadow-md shadow-blue-500/20">
            🔍 ติดตามสถานะงานซ่อมนี้ทันที
        </a>
        <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 font-medium text-sm transition">
            กลับหน้าแรก
        </a>
    </div>
</div>
@endsection
