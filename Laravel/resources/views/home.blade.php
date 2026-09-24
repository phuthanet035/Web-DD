@extends('layouts.app')

@section('title', 'หน้าแรก | ระบบแจ้งซ่อมและสนับสนุนงาน IT')

@section('styles')
<style>
    .hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-xl);
        padding: 3.5rem 2.5rem;
        color: #ffffff;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
    }

    .hero::after {
        content: "";
        position: absolute;
        top: -40%;
        right: -10%;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, rgba(37, 99, 235, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-content {
        max-width: 700px;
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.9rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 1.25rem;
        color: #93c5fd;
    }

    .hero h1 {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }

    .hero p {
        font-size: 1.1rem;
        color: #cbd5e1;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .quick-track-box {
        margin-top: 2rem;
        background: rgba(255, 255, 255, 0.08);
        padding: 1rem 1.25rem;
        border-radius: var(--radius-lg);
        border: 1px solid rgba(255, 255, 255, 0.12);
        max-width: 550px;
    }

    .quick-track-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .quick-track-input {
        flex: 1;
        padding: 0.65rem 1rem;
        border-radius: 9999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.95);
        color: #0f172a;
        font-size: 0.95rem;
        font-family: inherit;
        outline: none;
    }

    .quick-track-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }

    /* Stats bar */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: #ffffff;
        padding: 1.25rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.1;
        color: var(--secondary);
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Categories section */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .section-desc {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .category-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .category-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-border);
    }

    .cat-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .cat-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cat-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 0.25rem;
    }

    .cat-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }

    .cat-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px dashed var(--border-color);
    }

    .cat-link {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Process steps */
    .steps-section {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 2.5rem;
        margin-bottom: 2rem;
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .step-item {
        position: relative;
        padding: 1rem;
    }

    .step-num {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 1rem;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .step-title {
        font-weight: 600;
        color: var(--secondary);
        margin-bottom: 0.35rem;
    }

    .step-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#38bdf8;"></span>
            ศูนย์บริการสนับสนุนด้านเทคโนโลยีสารสนเทศ IT Helpdesk
        </div>
        <h1>ระบบแจ้งซ่อม IT สะดวก รวดเร็ว ติดตามสถานะได้ตลอดเวลา</h1>
        <p>พบปัญหาคอมพิวเตอร์ โปรแกรม ปริ้นเตอร์ หรือระบบเครือข่าย แจ้งซ่อมออนไลน์ได้ทันที เจ้าหน้าที่ IT พร้อมเข้าช่วยเหลืออย่างรวดเร็ว</p>

        <div class="hero-actions">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary" style="padding: 0.8rem 1.75rem; font-size: 1.05rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                แจ้งซ่อมงานใหม่ (New Ticket)
            </a>
            <a href="{{ route('tickets.track') }}" class="btn btn-secondary" style="padding: 0.8rem 1.5rem; font-size: 1.05rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                ค้นหาและติดตามสถานะ
            </a>
        </div>

        <div class="quick-track-box">
            <label style="font-size: 0.85rem; color: #94a3b8; font-weight: 500;">ติดตามงานด่วนด้วยหมายเลข Ticket หรือเบอร์โทรศัพท์ผู้แจ้ง:</label>
            <form action="{{ route('tickets.track') }}" method="GET" class="quick-track-form">
                <input type="text" name="search" class="quick-track-input" placeholder="เช่น IT-20260924-001 หรือ 0812345678" required>
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem;">ตรวจสอบ</button>
            </form>
        </div>
    </div>
</div>

<!-- Stats Counter -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">งานแจ้งซ่อมทั้งหมด</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">รอดำเนินการรับเรื่อง</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #1d4ed8;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">กำลังดำเนินการซ่อม</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #d1fae5; color: #059669;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['resolved'] }}</div>
            <div class="stat-label">แก้ไขสำเร็จเรียบร้อย</div>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div>
    <h2 class="section-title">เลือกหมวดหมู่อุปกรณ์ที่ต้องการแจ้งซ่อม</h2>
    <p class="section-desc">คลิกเลือกหมวดหมู่ที่ตรงกับปัญหาของคุณเพื่อเริ่มกรอกแบบฟอร์มแจ้งซ่อมอย่างรวดเร็ว</p>

    <div class="category-grid">
        @foreach($categories as $category)
            <div class="category-card">
                <div>
                    <div class="cat-header">
                        <div class="cat-icon-wrap">
                            @if(str_contains($category->name, 'คอมพิวเตอร์'))
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                            @elseif(str_contains($category->name, 'ปริ้นเตอร์'))
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            @elseif(str_contains($category->name, 'เครือข่าย'))
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                            @elseif(str_contains($category->name, 'ซอฟต์แวร์'))
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" x2="20" y1="19" y2="19"/></svg>
                            @elseif(str_contains($category->name, 'อีเมล'))
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                            @else
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9"/><line x1="9" x2="9" y1="1" stroke="currentColor"/><line x1="15" x2="15" y1="1" stroke="currentColor"/><line x1="9" x2="9" y1="23" stroke="currentColor"/><line x1="15" x2="15" y1="23" stroke="currentColor"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="cat-name">{{ $category->name }}</div>
                            <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:0.75rem;">มีงานในระบบ {{ $category->tickets_count }} รายการ</span>
                        </div>
                    </div>
                    <p class="cat-desc">{{ $category->description }}</p>
                </div>
                <div class="cat-action">
                    <span style="font-size: 0.85rem; color: #94a3b8;">ทีม IT พร้อมบริการ</span>
                    <a href="{{ route('tickets.create', ['category_id' => $category->id]) }}" class="cat-link">
                        แจ้งซ่อมหมวดนี้
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Process Workflow -->
<div class="steps-section">
    <div style="text-align: center; max-width: 600px; margin: 0 auto;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">ขั้นตอนการให้บริการแจ้งซ่อม IT</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem;">กระบวนการทำงานที่เป็นมาตรฐานเพื่อความรวดเร็วและตรวจสอบได้</p>
    </div>

    <div class="steps-grid">
        <div class="step-item">
            <div class="step-num">1</div>
            <div class="step-title">1. ส่งเรื่องแจ้งซ่อม</div>
            <div class="step-desc">กรอกแบบฟอร์มรายละเอียดปัญหา สถานที่ และรูปถ่ายอาการเสีย ระบบจะออกหมายเลข Ticket ให้ทันที</div>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <div class="step-title">2. รับงาน & ตรวจสอบ</div>
            <div class="step-desc">เจ้าหน้าที่ IT ประเมินระดับความสำคัญ มอบหมายช่างผู้เชี่ยวชาญ และเตรียมอุปกรณ์/อะไหล่</div>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <div class="step-title">3. เข้าดำเนินการซ่อม</div>
            <div class="step-desc">ช่างติดต่อผู้แจ้ง และเข้าแก้ไขปัญหา ณ สถานที่หรือผ่าน Remote Support พร้อมอัปเดตสถานะ</div>
        </div>
        <div class="step-item">
            <div class="step-num">4</div>
            <div class="step-title">4. ปิดงาน & ทดสอบ</div>
            <div class="step-desc">ทดสอบการใช้งานร่วมกับผู้แจ้ง บันทึกแนวทางการแก้ปัญหา และปิดงานอย่างสมบูรณ์</div>
        </div>
    </div>
</div>
@endsection