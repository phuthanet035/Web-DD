@extends('layouts.app')

@section('title', 'ติดตามสถานะงานแจ้งซ่อม | IT Service Desk')

@section('styles')
<style>
    .track-wrapper {
        max-width: 900px;
        margin: 0 auto;
    }

    .track-search-box {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        text-align: center;
    }

    .track-input-group {
        display: flex;
        gap: 0.5rem;
        max-width: 600px;
        margin: 1.5rem auto 0 auto;
    }

    .track-input {
        flex: 1;
        padding: 0.85rem 1.25rem;
        border-radius: 9999px;
        border: 2px solid var(--border-color);
        font-size: 1rem;
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
    }

    .track-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }

    /* Ticket Detail Card */
    .detail-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .detail-header {
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        padding: 1.75rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .detail-body {
        padding: 2rem;
    }

    /* Progress tracker */
    .tracker-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 1.5rem 0 2.5rem 0;
        padding: 0 1rem;
    }

    .tracker-steps::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 3rem;
        right: 3rem;
        height: 4px;
        background: #e2e8f0;
        z-index: 1;
    }

    .step-node {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
    }

    .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #cbd5e1;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .step-node.active .step-circle {
        border-color: var(--primary);
        background: var(--primary);
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(37, 99, 235, 0.2);
    }

    .step-node.completed .step-circle {
        border-color: #10b981;
        background: #10b981;
        color: #ffffff;
    }

    .step-node-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .step-node.active .step-node-label {
        color: var(--primary);
    }

    .step-node.completed .step-node-label {
        color: #10b981;
    }

    /* Info grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
        background: #f8fafc;
        border-radius: var(--radius-md);
        padding: 1.25rem;
        margin-bottom: 2rem;
    }

    .info-item-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .info-item-val {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--secondary);
    }

    /* Timeline */
    .timeline {
        border-left: 2px solid #e2e8f0;
        padding-left: 1.5rem;
        margin-left: 1rem;
        position: relative;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -1.95rem;
        top: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--primary);
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 2px var(--primary);
    }

    .timeline-time {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .timeline-content {
        font-size: 0.9rem;
        color: var(--text-main);
        margin-top: 0.2rem;
    }
</style>
@endsection

@section('content')
<div class="track-wrapper">
    <!-- Search Box -->
    <div class="track-search-box">
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--secondary); margin-bottom: 0.35rem;">
            ค้นหาและติดตามสถานะงานแจ้งซ่อม
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">
            กรอกหมายเลข Ticket หรือเบอร์โทรศัพท์ที่ใช้ในการแจ้งซ่อมเพื่อดูความคืบหน้า
        </p>

        <form action="{{ route('tickets.track') }}" method="GET" class="track-input-group">
            <input type="text" name="search" class="track-input" placeholder="ตัวอย่าง: IT-20260924-001 หรือ 0812345678" value="{{ $search }}" required>
            <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.75rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                ค้นหา
            </button>
        </form>
    </div>

    @if($search !== '')
        @if($ticket)
            <!-- Ticket Detail Found -->
            <div class="detail-card">
                <div class="detail-header">
                    <div>
                        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem; flex-wrap:wrap;">
                            <span style="font-family:'Plus Jakarta Sans', monospace; font-size:1.4rem; font-weight:800; color:var(--primary);">
                                {{ $ticket->ticket_number }}
                            </span>
                            <span class="badge badge-{{ $ticket->status }}">
                                {{ $ticket->status_label }}
                            </span>
                            <span class="badge priority-{{ $ticket->priority }}">
                                ความเร่งด่วน: {{ $ticket->priority_label }}
                            </span>
                        </div>
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--secondary);">
                            {{ $ticket->title }}
                        </h2>
                    </div>

                    <div style="text-align: right;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);">วันที่แจ้งซ่อม:</span>
                        <div style="font-weight: 600; font-size: 0.9rem;">{{ $ticket->created_at->format('d/m/Y H:i น.') }}</div>
                    </div>
                </div>

                <div class="detail-body">
                    <!-- Visual Progress Tracker -->
                    @php
                        $step = 1;
                        if ($ticket->status === 'assigned') $step = 2;
                        elseif (in_array($ticket->status, ['in_progress', 'waiting_parts'])) $step = 3;
                        elseif ($ticket->status === 'resolved') $step = 4;
                    @endphp

                    <div class="tracker-steps">
                        <div class="step-node {{ $step >= 1 ? ($step > 1 ? 'completed' : 'active') : '' }}">
                            <div class="step-circle">{{ $step > 1 ? '✓' : '1' }}</div>
                            <div class="step-node-label">1. แจ้งซ่อมแล้ว</div>
                        </div>

                        <div class="step-node {{ $step >= 2 ? ($step > 2 ? 'completed' : 'active') : '' }}">
                            <div class="step-circle">{{ $step > 2 ? '✓' : '2' }}</div>
                            <div class="step-node-label">2. เจ้าหน้าที่รับเรื่อง</div>
                        </div>

                        <div class="step-node {{ $step >= 3 ? ($step > 3 ? 'completed' : 'active') : '' }}">
                            <div class="step-circle">{{ $step > 3 ? '✓' : '3' }}</div>
                            <div class="step-node-label">3. กำลังดำเนินการ</div>
                        </div>

                        <div class="step-node {{ $step >= 4 ? 'completed' : '' }}">
                            <div class="step-circle">{{ $step >= 4 ? '✓' : '4' }}</div>
                            <div class="step-node-label">4. ซ่อมเสร็จสิ้น</div>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="info-grid">
                        <div>
                            <div class="info-item-label">หมวดหมู่อุปกรณ์</div>
                            <div class="info-item-val">{{ $ticket->category->name }}</div>
                        </div>
                        <div>
                            <div class="info-item-label">รหัสเครื่อง / Asset Tag</div>
                            <div class="info-item-val">{{ $ticket->device_code ?: 'ไม่ได้ระบุ' }}</div>
                        </div>
                        <div>
                            <div class="info-item-label">สถานที่ตั้งอุปกรณ์</div>
                            <div class="info-item-val">{{ $ticket->location }}</div>
                        </div>
                        <div>
                            <div class="info-item-label">ผู้แจ้งซ่อม</div>
                            <div class="info-item-val">{{ $ticket->requester_name }} ({{ $ticket->requester_phone }})</div>
                        </div>
                        <div>
                            <div class="info-item-label">ช่างผู้รับผิดชอบ</div>
                            <div class="info-item-val" style="color: var(--primary);">
                                {{ $ticket->technician ? $ticket->technician->name : 'กำลังรอจัดสรรช่าง' }}
                            </div>
                        </div>
                        <div>
                            <div class="info-item-label">สถานะปัจจุบัน</div>
                            <div class="info-item-val">
                                @if($ticket->status === 'resolved')
                                    <span style="color: #059669;">เสร็จสิ้น (เมื่อ {{ $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y H:i น.') : '-' }})</span>
                                @elseif($ticket->status === 'waiting_parts')
                                    <span style="color: #9333ea;">รออะไหล่ / จัดซื้ออุปกรณ์</span>
                                @elseif($ticket->status === 'in_progress')
                                    <span style="color: #2563eb;">ช่างกำลังดำเนินการแก้ไข</span>
                                @else
                                    <span style="color: #d97706;">รอดำเนินการ</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">รายละเอียดปัญหา:</h3>
                        <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; font-size: 0.95rem; white-space: pre-wrap;">{{ $ticket->description }}</div>
                    </div>

                    @if($ticket->attachment)
                        <div style="margin-bottom: 2rem;">
                            <h3 style="font-size: 1rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">รูปภาพแนบ:</h3>
                            <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank">
                                <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="ภาพอาการเสีย" style="max-width: 320px; max-height: 240px; border-radius: 8px; border: 1px solid var(--border-color); object-fit: cover;">
                            </a>
                        </div>
                    @endif

                    <!-- Resolution Note if any -->
                    @if($ticket->resolution_note)
                        <div style="margin-bottom: 2rem; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: var(--radius-md); padding: 1.25rem;">
                            <h3 style="font-size: 1rem; font-weight: 700; color: #065f46; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                ผลการแก้ไขและคำแนะนำจากเจ้าหน้าที่ IT:
                            </h3>
                            <div style="color: #064e3b; font-size: 0.95rem; white-space: pre-wrap;">{{ $ticket->resolution_note }}</div>
                        </div>
                    @endif

                    <!-- Activity History Timeline -->
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--secondary); margin-bottom: 1rem;">
                            บันทึกประวัติการดำเนินงาน (Timeline)
                        </h3>

                        <div class="timeline">
                            @foreach($ticket->histories as $history)
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-time">{{ $history->created_at->format('d/m/Y H:i น.') }}</div>
                                    <div class="timeline-content">
                                        {{ $history->comment }}
                                        @if($history->user)
                                            <span style="font-size: 0.8rem; color: var(--text-muted);">— โดย {{ $history->user->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        @elseif($tickets && $tickets->count() > 0)
            <!-- Multiple Tickets by Phone -->
            <div class="card" style="padding: 1.5rem;">
                <h2 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem;">
                    พบข้อมูลแจ้งซ่อมทั้งหมด {{ $tickets->count() }} รายการ สำหรับ "{{ $search }}"
                </h2>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($tickets as $t)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid var(--border-color); border-radius: 8px; flex-wrap: wrap; gap: 0.75rem;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <strong style="color: var(--primary);">{{ $t->ticket_number }}</strong>
                                    <span class="badge badge-{{ $t->status }}">{{ $t->status_label }}</span>
                                </div>
                                <div style="font-weight: 600; margin-top: 0.25rem;">{{ $t->title }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">
                                    {{ $t->category->name }} | {{ $t->created_at->format('d/m/Y H:i น.') }}
                                </div>
                            </div>
                            <a href="{{ route('tickets.track', ['search' => $t->ticket_number]) }}" class="btn btn-outline-primary btn-sm">
                                ดูรายละเอียดงานนี้ &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Not Found -->
            <div style="text-align: center; padding: 3rem 1.5rem; background: #ffffff; border-radius: var(--radius-lg); border: 1px dashed var(--border-color);">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔍</div>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">
                    ไม่พบข้อมูลแจ้งซ่อมสำหรับ "{{ $search }}"
                </h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
                    กรุณาตรวจสอบหมายเลข Ticket (เช่น IT-20260924-001) หรือเบอร์โทรศัพท์ที่ระบุตอนแจ้งซ่อมใหม่อีกครั้ง
                </p>
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    แจ้งซ่อมใหม่ (New Ticket)
                </a>
            </div>
        @endif
    @endif
</div>
@endsection