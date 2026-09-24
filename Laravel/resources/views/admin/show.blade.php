@extends('layouts.app')

@section('title', 'จัดการงาน ' . $ticket->ticket_number . ' | IT Service Desk')

@section('styles')
<style>
    .manage-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 900px) {
        .manage-layout {
            grid-template-columns: 1fr;
        }
    }

    .panel-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }

    .panel-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .timeline {
        border-left: 2px solid #e2e8f0;
        padding-left: 1.5rem;
        margin-left: 0.5rem;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.25rem;
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
</style>
@endsection

@section('content')
<!-- Back button & Header -->
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="display:inline-flex; align-items:center; gap:0.35rem; color:var(--text-muted); font-size:0.9rem; margin-bottom:0.75rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        กลับสู่รายการงานทั้งหมด
    </a>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.25rem;">
                <span style="font-family:'Plus Jakarta Sans', monospace; font-size:1.75rem; font-weight:800; color:var(--primary);">
                    {{ $ticket->ticket_number }}
                </span>
                <span class="badge badge-{{ $ticket->status }}" style="font-size: 0.9rem; padding: 0.35rem 0.85rem;">
                    {{ $ticket->status_label }}
                </span>
                <span class="badge priority-{{ $ticket->priority }}" style="font-size: 0.85rem;">
                    ความเร่งด่วน: {{ $ticket->priority_label }}
                </span>
            </div>
            <h1 style="font-size: 1.35rem; font-weight: 700; color: var(--secondary);">
                {{ $ticket->title }}
            </h1>
        </div>

        <div style="text-align: right;">
            <div style="font-size: 0.8rem; color: var(--text-muted);">รับแจ้งเมื่อ:</div>
            <div style="font-weight: 600;">{{ $ticket->created_at->format('d/m/Y H:i น.') }}</div>
        </div>
    </div>
</div>

<div class="manage-layout">
    <!-- Left / Main Panel -->
    <div>
        <!-- Update Action Card -->
        <div class="panel-card" style="border-top: 4px solid var(--primary);">
            <div class="panel-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                อัปเดตสถานะงาน & มอบหมายช่าง (Update & Assign)
            </div>

            <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <!-- Status -->
                    <div>
                        <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.35rem;">เปลี่ยนสถานะงาน:</label>
                        <select name="status" class="form-control" style="width:100%; padding:0.65rem 0.85rem; border-radius:6px; border:1px solid #cbd5e1; font-weight:600;">
                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>⏳ รอดำเนินการ</option>
                            <option value="assigned" {{ $ticket->status === 'assigned' ? 'selected' : '' }}>📋 มอบหมายงานแล้ว</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>🔧 กำลังดำเนินการซ่อม</option>
                            <option value="waiting_parts" {{ $ticket->status === 'waiting_parts' ? 'selected' : '' }}>📦 รออะไหล่ / จัดซื้ออุปกรณ์</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>✅ ซ่อมเสร็จสิ้น (ปิดงาน)</option>
                            <option value="cancelled" {{ $ticket->status === 'cancelled' ? 'selected' : '' }}>❌ ยกเลิกงาน</option>
                        </select>
                    </div>

                    <!-- Assign Technician -->
                    <div>
                        <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.35rem;">ช่างผู้รับผิดชอบงาน:</label>
                        <select name="assigned_to" class="form-control" style="width:100%; padding:0.65rem 0.85rem; border-radius:6px; border:1px solid #cbd5e1;">
                            <option value="">-- ยังไม่มอบหมายช่าง --</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ $ticket->assigned_to == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }} ({{ $tech->department ?: 'IT' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.35rem;">ปรับระดับความเร่งด่วน:</label>
                    <select name="priority" class="form-control" style="width:100%; padding:0.65rem 0.85rem; border-radius:6px; border:1px solid #cbd5e1;">
                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>ต่ำ (Low)</option>
                        <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>ปานกลาง (Medium)</option>
                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>สูง (High)</option>
                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>เร่งด่วนมาก (Urgent)</option>
                    </select>
                </div>

                <!-- Resolution Note -->
                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.35rem;">
                        บันทึกแนวทางแก้ไขปัญหา / ผลการซ่อม (ผู้แจ้งจะมองเห็นเมื่อปิดงาน):
                    </label>
                    <textarea name="resolution_note" rows="3" class="form-control" style="width:100%; padding:0.65rem 0.85rem; border-radius:6px; border:1px solid #cbd5e1; font-family:inherit;" placeholder="เช่น ทำการเปลี่ยน Power Supply ตัวใหม่ และทดสอบรันเครื่อง 30 นาที ใช้งานได้ปกติ...">{{ old('resolution_note', $ticket->resolution_note) }}</textarea>
                </div>

                <!-- Log Comment -->
                <div style="margin-bottom: 1.25rem;">
                    <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.35rem;">
                        บันทึกการทำงาน / หมายเหตุภายในทีม IT (Internal Note):
                    </label>
                    <input type="text" name="comment" class="form-control" style="width:100%; padding:0.65rem 0.85rem; border-radius:6px; border:1px solid #cbd5e1;" placeholder="เช่น เบิกสายเคเบิลจากคลังแล้ว นัดหมายเข้าหน้างาน 14:00 น.">
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:0.75rem; font-size:1rem;">
                    บันทึกการเปลี่ยนแปลง (Save Changes)
                </button>
            </form>
        </div>

        <!-- Issue Details Card -->
        <div class="panel-card">
            <div class="panel-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                รายละเอียดอาการเสีย
            </div>

            <div style="background:#f8fafc; border-radius:8px; padding:1.25rem; font-size:0.95rem; line-height:1.6; white-space:pre-wrap; border:1px solid var(--border-color); margin-bottom:1.5rem;">
                {{ $ticket->description }}
            </div>

            @if($ticket->attachment)
                <div>
                    <div style="font-weight:600; font-size:0.9rem; margin-bottom:0.5rem;">ภาพถ่ายแนบ:</div>
                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank">
                        <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="หลักฐานอาการเสีย" style="max-width:100%; max-height:300px; border-radius:8px; border:1px solid var(--border-color); object-fit:contain;">
                    </a>
                </div>
            @endif
        </div>

        <!-- History Timeline -->
        <div class="panel-card">
            <div class="panel-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                ประวัติการดำเนินการ (Audit Logs)
            </div>

            <div class="timeline">
                @foreach($ticket->histories as $history)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div style="font-size:0.8rem; color:var(--text-muted);">{{ $history->created_at->format('d/m/Y H:i:s น.') }}</div>
                        <div style="font-size:0.9rem; margin-top:0.2rem; font-weight:500;">
                            {{ $history->comment }}
                        </div>
                        @if($history->user)
                            <div style="font-size:0.8rem; color:var(--primary);">
                                โดย: {{ $history->user->name }} ({{ $history->user->role }})
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div>
        <!-- Requester Info Card -->
        <div class="panel-card">
            <div class="panel-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                ข้อมูลผู้แจ้งซ่อม
            </div>

            <div style="font-size:0.9rem; display:flex; flex-direction:column; gap:0.75rem;">
                <div>
                    <span style="color:var(--text-muted); font-size:0.8rem; display:block;">ชื่อผู้แจ้ง:</span>
                    <strong>{{ $ticket->requester_name }}</strong>
                </div>

                <div>
                    <span style="color:var(--text-muted); font-size:0.8rem; display:block;">เบอร์โทรศัพท์:</span>
                    <a href="tel:{{ $ticket->requester_phone }}" style="color:var(--primary); font-weight:600; text-decoration:underline;">
                        📞 {{ $ticket->requester_phone }}
                    </a>
                </div>

                @if($ticket->requester_email)
                    <div>
                        <span style="color:var(--text-muted); font-size:0.8rem; display:block;">อีเมล:</span>
                        <a href="mailto:{{ $ticket->requester_email }}" style="color:var(--primary);">
                            {{ $ticket->requester_email }}
                        </a>
                    </div>
                @endif

                <div>
                    <span style="color:var(--text-muted); font-size:0.8rem; display:block;">สถานที่ / แผนก:</span>
                    <strong>{{ $ticket->location }}</strong>
                </div>

                <div>
                    <span style="color:var(--text-muted); font-size:0.8rem; display:block;">หมวดหมู่อุปกรณ์:</span>
                    <span class="badge" style="background:#f1f5f9; color:var(--secondary);">{{ $ticket->category->name }}</span>
                </div>

                <div>
                    <span style="color:var(--text-muted); font-size:0.8rem; display:block;">รหัสครุภัณฑ์ / Asset Tag:</span>
                    <span style="font-family:monospace; font-weight:600;">{{ $ticket->device_code ?: 'ไม่มี' }}</span>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if(Auth::user()->isAdmin())
            <div class="panel-card" style="border-color:#fecdd3; background:#fff1f2;">
                <div style="font-size:0.95rem; font-weight:700; color:#9f1239; margin-bottom:0.5rem;">
                    พื้นที่ผู้ดูแลระบบ (Admin Only)
                </div>
                <p style="font-size:0.8rem; color:#9f1239; margin-bottom:1rem;">
                    การลบจะลบข้อมูลใบแจ้งซ่อมและประวัติทั้งหมดอย่างถาวร
                </p>

                <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบใบแจ้งซ่อมนี้อย่างถาวรหรือไม่?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">
                        ลบใบแจ้งซ่อมนี้
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection