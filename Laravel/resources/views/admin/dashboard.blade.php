@extends('layouts.app')

@section('title', 'ระบบจัดการงานแจ้งซ่อม IT (Dashboard) | IT Service Desk')

@section('styles')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .stat-pill-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-pill {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
    }

    .stat-pill:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    /* Filter Bar */
    .filter-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 0.75rem;
        align-items: center;
    }

    @media (max-width: 992px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 640px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Table */
    .table-container {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        overflow-x: auto;
        box-shadow: var(--shadow-sm);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.9rem;
    }

    .table th {
        background: #f8fafc;
        padding: 1rem 1.25rem;
        font-weight: 700;
        color: var(--secondary);
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }

    .table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tr:hover td {
        background: #f8fafc;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .ticket-link {
        font-family: 'Plus Jakarta Sans', monospace;
        font-weight: 700;
        color: var(--primary);
    }

    .pagination-wrap {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<!-- Header -->
<div class="admin-header">
    <div>
        <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--secondary);">
            ศูนย์จัดการงานแจ้งซ่อม IT (Admin Dashboard)
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">
            จัดการมอบหมายงาน ตรวจสอบสถานะ และบันทึกผลการซ่อมบำรุง
        </p>
    </div>

    <div style="display:flex; gap:0.5rem;">
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            สร้างใบแจ้งซ่อมใหม่
        </a>
    </div>
</div>

<!-- KPI Stat Cards -->
<div class="stat-pill-group">
    <a href="{{ route('admin.dashboard') }}" class="stat-pill">
        <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
        </div>
        <div>
            <div style="font-size:1.5rem;font-weight:700;line-height:1;">{{ $stats['total'] ?? 0 }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;">งานทั้งหมด</div>
        </div>
    </a>

    <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="stat-pill">
        <div style="width:40px;height:40px;border-radius:10px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
            <div style="font-size:1.5rem;font-weight:700;line-height:1;color:#b45309;">{{ $stats['pending'] ?? 0 }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;">รอดำเนินการ</div>
        </div>
    </a>

    <a href="{{ route('admin.dashboard', ['status' => 'in_progress']) }}" class="stat-pill">
        <div style="width:40px;height:40px;border-radius:10px;background:#dbeafe;color:#1d4ed8;display:flex;align-items:center;justify-content:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <div>
            <div style="font-size:1.5rem;font-weight:700;line-height:1;color:#1d4ed8;">{{ $stats['in_progress'] ?? 0 }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;">กำลังดำเนินการ</div>
        </div>
    </a>

    <a href="{{ route('admin.dashboard', ['status' => 'waiting_parts']) }}" class="stat-pill">
        <div style="width:40px;height:40px;border-radius:10px;background:#f3e8ff;color:#6b21a8;display:flex;align-items:center;justify-content:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div>
            <div style="font-size:1.5rem;font-weight:700;line-height:1;color:#6b21a8;">{{ $stats['waiting_parts'] ?? 0 }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;">รออะไหล่/จัดซื้อ</div>
        </div>
    </a>

    <a href="{{ route('admin.dashboard', ['status' => 'resolved']) }}" class="stat-pill">
        <div style="width:40px;height:40px;border-radius:10px;background:#d1fae5;color:#059669;display:flex;align-items:center;justify-content:center;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <div style="font-size:1.5rem;font-weight:700;line-height:1;color:#047857;">{{ $stats['resolved'] ?? 0 }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;">ซ่อมเสร็จสิ้น</div>
        </div>
    </a>
</div>

<!-- Filter Bar -->
<div class="filter-card">
    <form action="{{ route('admin.dashboard') }}" method="GET" class="filter-grid">
        <!-- Keyword Search -->
        <div>
            <input type="text" name="search" class="form-control" placeholder="ค้นหา Ticket, ปัญหา, ชื่อผู้แจ้ง, รหัสเครื่อง..." value="{{ request('search') }}" style="width:100%;padding:0.6rem 0.85rem;border-radius:6px;border:1px solid #cbd5e1;font-size:0.9rem;">
        </div>

        <!-- Status Filter -->
        <div>
            <select name="status" class="form-select" style="width:100%;padding:0.6rem 0.85rem;border-radius:6px;border:1px solid #cbd5e1;font-size:0.9rem;">
                <option value="">ทุกสถานะงาน</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>มอบหมายงานแล้ว</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                <option value="waiting_parts" {{ request('status') === 'waiting_parts' ? 'selected' : '' }}>รออะไหล่/จัดซื้อ</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>ซ่อมเสร็จสิ้น</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
            </select>
        </div>

        <!-- Priority Filter -->
        <div>
            <select name="priority" class="form-select" style="width:100%;padding:0.6rem 0.85rem;border-radius:6px;border:1px solid #cbd5e1;font-size:0.9rem;">
                <option value="">ทุกความเร่งด่วน</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>เร่งด่วนมาก</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>สูง</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>ปานกลาง</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>ต่ำ</option>
            </select>
        </div>

        <!-- Category Filter -->
        <div>
            <select name="category_id" class="form-select" style="width:100%;padding:0.6rem 0.85rem;border-radius:6px;border:1px solid #cbd5e1;font-size:0.9rem;">
                <option value="">ทุกหมวดหมู่อุปกรณ์</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit & Reset -->
        <div style="display:flex;gap:0.35rem;">
            <button type="submit" class="btn btn-primary btn-sm" style="padding:0.6rem 1rem;">
                กรอง
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm" style="padding:0.6rem 0.85rem;">
                ล้าง
            </a>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>หมายเลข Ticket</th>
                <th>หัวข้อแจ้งซ่อม / หมวดหมู่</th>
                <th>สถานที่ / รหัสเครื่อง</th>
                <th>ผู้แจ้งซ่อม</th>
                <th>ความเร่งด่วน</th>
                <th>สถานะ</th>
                <th>ช่างผู้รับผิดชอบ</th>
                <th style="text-align: right;">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $t)
                <tr>
                    <td>
                        <a href="{{ route('admin.tickets.show', $t->id) }}" class="ticket-link">
                            {{ $t->ticket_number }}
                        </a>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">
                            {{ $t->created_at->format('d/m/Y H:i') }}
                        </div>
                    </td>

                    <td>
                        <div style="font-weight: 600; color: var(--secondary); max-width: 240px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $t->title }}
                        </div>
                        <div style="font-size:0.8rem; color:var(--text-muted);">
                            {{ $t->category->name }}
                        </div>
                    </td>

                    <td>
                        <div style="font-size: 0.85rem; font-weight: 500;">{{ $t->location }}</div>
                        @if($t->device_code)
                            <div style="font-size:0.75rem; color:#64748b; font-family:monospace;">Tag: {{ $t->device_code }}</div>
                        @endif
                    </td>

                    <td>
                        <div style="font-weight: 500;">{{ $t->requester_name }}</div>
                        <div style="font-size:0.8rem; color:var(--text-muted);">{{ $t->requester_phone }}</div>
                    </td>

                    <td>
                        <span class="badge priority-{{ $t->priority }}">
                            {{ $t->priority_label }}
                        </span>
                    </td>

                    <td>
                        <span class="badge badge-{{ $t->status }}">
                            {{ $t->status_label }}
                        </span>
                    </td>

                    <td>
                        @if($t->technician)
                            <span style="font-weight: 600; font-size: 0.85rem; color: var(--primary);">
                                🔧 {{ $t->technician->name }}
                            </span>
                        @else
                            <span style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">ยังไม่ได้มอบหมาย</span>
                        @endif
                    </td>

                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('admin.tickets.show', $t->id) }}" class="btn btn-outline-primary btn-sm">
                            จัดการงาน &rarr;
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📋</div>
                        <div>ไม่พบรายการแจ้งซ่อมตามเงื่อนไขที่เลือก</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="pagination-wrap">
    {{ $tickets->links() }}
</div>
@endsection