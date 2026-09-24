@extends('layouts.app')

@section('title', 'เข้าสู่ระบบเจ้าหน้าที่ IT | IT Service Desk')

@section('styles')
<style>
    .login-container {
        max-width: 440px;
        margin: 3rem auto;
    }

    .login-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        padding: 2.5rem 2rem;
    }

    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-icon {
        width: 52px;
        height: 52px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
    }

    .demo-box {
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: var(--radius-md);
        border: 1px dashed var(--border-color);
        font-size: 0.85rem;
    }

    .demo-pill {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        cursor: pointer;
        font-family: monospace;
        color: var(--primary);
        margin: 0.2rem 0;
    }

    .demo-pill:hover {
        background: var(--primary-light);
        border-color: var(--primary-border);
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--secondary);">เข้าสู่ระบบเจ้าหน้าที่ IT</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">สำหรับทีมงาน IT Support และผู้ดูแลระบบ</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label for="email" style="display:block; font-size:0.9rem; font-weight:600; margin-bottom:0.35rem;">อีเมล (Email)</label>
                <input type="email" id="email" name="email" class="form-control" style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid #cbd5e1; outline:none; font-family:inherit;" placeholder="admin@it.com" value="{{ old('email', 'admin@it.com') }}" required autofocus>
                @error('email')
                    <div style="color:#ef4444; font-size:0.8rem; margin-top:0.35rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="password" style="display:block; font-size:0.9rem; font-weight:600; margin-bottom:0.35rem;">รหัสผ่าน (Password)</label>
                <input type="password" id="password" name="password" class="form-control" style="width:100%; padding:0.75rem 1rem; border-radius:8px; border:1px solid #cbd5e1; outline:none; font-family:inherit;" placeholder="••••••••" value="password123" required>
                @error('password')
                    <div style="color:#ef4444; font-size:0.8rem; margin-top:0.35rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; font-size:0.85rem;">
                <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer;">
                    <input type="checkbox" name="remember" value="1" checked>
                    <span>จดจำการเข้าสู่ระบบ</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:0.85rem; font-size:1rem;">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="demo-box">
            <div style="font-weight:600; color:var(--secondary); margin-bottom:0.35rem;">🔑 บัญชีทดสอบระบบ (Test Accounts):</div>
            <div>• ผู้ดูแลระบบ (Admin): <span class="demo-pill" onclick="fillLogin('admin@it.com', 'password123')">admin@it.com</span></div>
            <div>• ช่างเทคนิค (Tech): <span class="demo-pill" onclick="fillLogin('tech@it.com', 'password123')">tech@it.com</span></div>
            <div style="margin-top:0.35rem; color:#64748b;">รหัสผ่าน: <code style="font-weight:600;">password123</code> (คลิกเพื่อกรอกอัตโนมัติ)</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fillLogin(email, pwd) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pwd;
    }
</script>
@endsection