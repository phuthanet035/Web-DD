@extends('layouts.app')

@section('title', 'แบบฟอร์มแจ้งซ่อม IT | IT Service Desk')

@section('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }

    .form-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-md);
        padding: 2.5rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .section-heading {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.4rem;
    }

    .form-label .required {
        color: #ef4444;
    }

    .form-control, .form-select, textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        font-family: inherit;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: var(--text-main);
        transition: all 0.2s ease;
        outline: none;
    }

    .form-control:focus, .form-select:focus, textarea:focus {
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-help {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.35rem;
    }

    .field-error {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.35rem;
    }

    /* Category Pill Selector */
    .category-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .category-radio {
        display: none;
    }

    .category-label {
        display: block;
        padding: 0.85rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .category-radio:checked + .category-label {
        border-color: var(--primary);
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
    }

    /* Priority Radio Cards */
    .priority-options {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    @media (max-width: 640px) {
        .priority-options {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .priority-radio {
        display: none;
    }

    .priority-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.85rem 0.5rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
        transition: all 0.2s ease;
    }

    .priority-radio[value="low"]:checked + .priority-label {
        border-color: #64748b;
        background: #f1f5f9;
        color: #334155;
    }

    .priority-radio[value="medium"]:checked + .priority-label {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .priority-radio[value="high"]:checked + .priority-label {
        border-color: #f59e0b;
        background: #fef3c7;
        color: #b45309;
    }

    .priority-radio[value="urgent"]:checked + .priority-label {
        border-color: #e11d48;
        background: #ffe4e6;
        color: #be123c;
    }

    .file-upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
    }

    .file-upload-box:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:0.35rem; color:var(--text-muted); font-size:0.9rem; margin-bottom:0.75rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            กลับสู่หน้าหลัก
        </a>
        <h1 class="form-title">แบบฟอร์มแจ้งซ่อมและบริการ IT</h1>
        <p class="form-subtitle">กรุณากรอกรายละเอียดปัญหาให้ชัดเจน เพื่อให้เจ้าหน้าที่ IT เตรียมความพร้อมและเข้าแก้ไขได้อย่างรวดเร็ว</p>
    </div>

    <div class="form-card">
        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 1. ข้อมูลผู้แจ้งซ่อม -->
            <div class="form-section">
                <div class="section-heading">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    ข้อมูลผู้แจ้งซ่อมและสถานที่
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="requester_name">ชื่อ-นามสกุล ผู้แจ้ง <span class="required">*</span></label>
                        <input type="text" id="requester_name" name="requester_name" class="form-control" placeholder="เช่น คุณสมศรี เจริญสุข" value="{{ old('requester_name') }}" required>
                        @error('requester_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="requester_phone">เบอร์โทรศัพท์ติดต่อ (ใช้ติดตามงาน) <span class="required">*</span></label>
                        <input type="tel" id="requester_phone" name="requester_phone" class="form-control" placeholder="เช่น 081-234-5678 หรือ เบอร์ต่อภายใน 102" value="{{ old('requester_phone') }}" required>
                        @error('requester_phone')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="requester_email">อีเมล (ถ้ามี)</label>
                        <input type="email" id="requester_email" name="requester_email" class="form-control" placeholder="example@company.com" value="{{ old('requester_email') }}">
                        @error('requester_email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="location">สถานที่ / อาคาร / ชั้น / ห้อง <span class="required">*</span></label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="เช่น อาคาร 2 ชั้น 3 ฝ่ายบัญชีและการเงิน" value="{{ old('location') }}" required>
                        @error('location')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. หมวดหมู่อุปกรณ์และรายละเอียดปัญหา -->
            <div class="form-section">
                <div class="section-heading">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                    รายละเอียดปัญหาและอุปกรณ์
                </div>

                <div class="form-group">
                    <label class="form-label">เลือกหมวดหมู่อุปกรณ์ <span class="required">*</span></label>
                    <div class="category-options">
                        @foreach($categories as $cat)
                            <label>
                                <input type="radio" name="category_id" value="{{ $cat->id }}" class="category-radio" {{ (old('category_id', $selectedCategory) == $cat->id) ? 'checked' : ($loop->first && !old('category_id') && !$selectedCategory ? 'checked' : '') }} required>
                                <div class="category-label">
                                    <div style="font-weight:600; font-size:0.95rem;">{{ $cat->name }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('category_id')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="device_code">รหัสทรัพย์สิน / หมายเลขเครื่อง (Asset Tag)</label>
                        <input type="text" id="device_code" name="device_code" class="form-control" placeholder="เช่น PC-ACC-012 หรือ PRT-05 (ถ้ามี)" value="{{ old('device_code') }}">
                        <div class="form-help">ดูได้จากสติกเกอร์บาร์โค้ดที่ติดอยู่บนตัวเครื่อง</div>
                        @error('device_code')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="title">หัวข้อปัญหาที่พบ <span class="required">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="เช่น คอมพิวเตอร์เปิดไม่ติด, พิมพ์งานไม่ออก" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">รายละเอียดอาการเสียหรือข้อความ Error <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="4" placeholder="อธิบายอาการที่เกิดขึ้น เช่น กดปุ่มเปิดเครื่องแล้วมีเสียงร้อง 3 ครั้ง, หน้าจอขึ้นข้อความ error สีฟ้า, หรือโปรแกรมค้างขณะกดบันทึก..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="form-group">
                    <label class="form-label">ระดับความเร่งด่วน <span class="required">*</span></label>
                    <div class="priority-options">
                        <label>
                            <input type="radio" name="priority" value="low" class="priority-radio" {{ old('priority') == 'low' ? 'checked' : '' }}>
                            <div class="priority-label">
                                <span>🟢 ต่ำ (Low)</span>
                                <span style="font-size:0.75rem;font-weight:normal;color:#64748b;margin-top:2px;">ไม่กระทบงานหลัก</span>
                            </div>
                        </label>
                        <label>
                            <input type="radio" name="priority" value="medium" class="priority-radio" {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }}>
                            <div class="priority-label">
                                <span>🔵 ปานกลาง (Medium)</span>
                                <span style="font-size:0.75rem;font-weight:normal;color:#64748b;margin-top:2px;">กระทบงานแต่รอได้</span>
                            </div>
                        </label>
                        <label>
                            <input type="radio" name="priority" value="high" class="priority-radio" {{ old('priority') == 'high' ? 'checked' : '' }}>
                            <div class="priority-label">
                                <span>🟠 สูง (High)</span>
                                <span style="font-size:0.75rem;font-weight:normal;color:#64748b;margin-top:2px;">กระทบงานสำคัญ</span>
                            </div>
                        </label>
                        <label>
                            <input type="radio" name="priority" value="urgent" class="priority-radio" {{ old('priority') == 'urgent' ? 'checked' : '' }}>
                            <div class="priority-label">
                                <span>🔴 เร่งด่วนมาก (Urgent)</span>
                                <span style="font-size:0.75rem;font-weight:normal;color:#64748b;margin-top:2px;">ระบบล่ม/ใช้งานไม่ได้</span>
                            </div>
                        </label>
                    </div>
                    @error('priority')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- File Attachment -->
                <div class="form-group">
                    <label class="form-label">แนบรูปภาพปัญหาหรือภาพหน้าจอ Error (ถ้ามี)</label>
                    <div class="file-upload-box" onclick="document.getElementById('attachment').click()">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.5" style="margin-bottom:0.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <div style="font-weight:600; font-size:0.9rem; color:var(--secondary);">คลิกเพื่อเลือกรูปภาพ หรือ ถ่ายภาพหน้าจอ</div>
                        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.25rem;">รองรับไฟล์ JPG, PNG, WEBP ขนาดไม่เกิน 5MB</div>
                        <div id="file-name" style="font-size:0.85rem; color:var(--primary); margin-top:0.5rem; font-weight:600;"></div>
                    </div>
                    <input type="file" id="attachment" name="attachment" accept="image/*" style="display:none;" onchange="showFilename(this)">
                    @error('attachment')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div style="display:flex; justify-content:flex-end; gap:1rem; align-items:center;">
                <a href="{{ route('home') }}" class="btn btn-secondary">ยกเลิก</a>
                <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem; font-size:1.05rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    ส่งคำขอแจ้งซ่อมทันที
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showFilename(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file-name').textContent = 'ไฟล์ที่เลือก: ' + input.files[0].name;
        }
    }
</script>
@endsection