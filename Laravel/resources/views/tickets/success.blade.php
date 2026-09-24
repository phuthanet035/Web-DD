@extends('layouts.app')

@section('title', 'แจ้งซ่อมสำเร็จ | IT Service Desk')

@section('styles')
<style>
    .success-card {
        max-width: 650px;
        margin: 2rem auto;
        background: #ffffff;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-lg);
        padding: 3rem 2.5rem;
        text-align: center;
    }

    .success-icon-wrap {
        width: 80px;
        height: 80px;
        background: #d1fae5;
        color: #059669;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.2);
    }

    .ticket-box {
        background: #f8fafc;
        border: 2px dashed #93c5fd;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin: 2rem 0;
    }

    .ticket-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        margin-bottom: 0.35rem;
    }

    .ticket-code {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: 0.05em;
        font-family: 'Plus Jakarta Sans', monospace;
    }

    .copy-btn {
        margin-top: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        border: 1px solid var(--border-color);
        background: #ffffff;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
        cursor: pointer;
        transition: all 0.2s;
    }

    .copy-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .summary-list {
        text-align: left;
        background: #f8fafc;
        border-radius: var(--radius-md);
        padding: 1.25rem;
        margin-bottom: 2rem;
        font-size: 0.9rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: var(--text-muted);
    }

    .summary-val {
        font-weight: 600;
        color: var(--secondary);
    }
</style>
@endsection

@section('content')
<div class="success-card">
    <div class="success-icon-wrap">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
    </div>

    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--secondary); margin-bottom: 0.5rem;">
        ส่งคำขอแจ้งซ่อมสำเร็จเรียบร้อย!
    </h1>
    <p style="color: var(--text-muted); font-size: 0.95rem;">
        ระบบได้บันทึกข้อมูลและส่งแจ้งเตือนไปยังทีมช่างไอทีเรียบร้อยแล้ว กรุณาจดจำหรือคัดลอกหมายเลข Ticket เพื่อใช้ติดตามสถานะ
    </p>

    <!-- Ticket Box -->
    <div class="ticket-box">
        <div class="ticket-label">หมายเลขแจ้งซ่อมของคุณ (Ticket Number)</div>
        <div class="ticket-code" id="ticketCode">{{ $ticket->ticket_number }}</div>
        <button type="button" class="copy-btn" onclick="copyTicketCode()">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
            <span id="copyText">คัดลอกรหัส</span>
        </button>
    </div>

    <!-- Summary Details -->
    <div class="summary-list">
        <div class="summary-row">
            <span class="summary-label">หัวข้อปัญหา:</span>
            <span class="summary-val">{{ $ticket->title }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">หมวดหมู่อุปกรณ์:</span>
            <span class="summary-val">{{ $ticket->category->name }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">ผู้แจ้งซ่อม:</span>
            <span class="summary-val">{{ $ticket->requester_name }} ({{ $ticket->requester_phone }})</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">สถานที่:</span>
            <span class="summary-val">{{ $ticket->location }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">ระดับความเร่งด่วน:</span>
            <span class="summary-val">{{ $ticket->priority_label }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">เวลาที่แจ้ง:</span>
            <span class="summary-val">{{ $ticket->created_at->format('d/m/Y H:i น.') }}</span>
        </div>
    </div>

    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('tickets.track', ['search' => $ticket->ticket_number]) }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            ติดตามสถานะงานนี้ทันที
        </a>
        <a href="{{ route('tickets.create') }}" class="btn btn-secondary" style="padding: 0.75rem 1.25rem;">
            แจ้งซ่อมรายการอื่น
        </a>
        <a href="{{ route('home') }}" class="btn btn-secondary" style="padding: 0.75rem 1.25rem;">
            กลับหน้าแรก
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyTicketCode() {
        const code = document.getElementById('ticketCode').innerText.trim();
        navigator.clipboard.writeText(code).then(() => {
            const btnText = document.getElementById('copyText');
            btnText.innerText = 'คัดลอกสำเร็จ! ✓';
            setTimeout(() => {
                btnText.innerText = 'คัดลอกรหัส';
            }, 2500);
        });
    }
</script>
@endsection