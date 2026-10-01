@extends('layouts.app')

@section('title', 'เข้าสู่ระบบเจ้าหน้าที่ - ระบบแจ้งซ่อม IT')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3">
            🔐
        </div>
        <h1 class="text-2xl font-bold text-slate-900">เข้าสู่ระบบเจ้าหน้าที่ IT</h1>
        <p class="text-slate-500 text-xs mt-1">สำหรับผู้ดูแลระบบและช่างประจำศูนย์บริการ IT Helpdesk</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">อีเมลผู้ใช้งาน</label>
                <input type="email" name="email" id="email" value="{{ old('email', 'admin@ithelpdesk.com') }}" required autofocus
                    placeholder="admin@ithelpdesk.com"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">รหัสผ่าน</label>
                <input type="password" name="password" id="password" required
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-2">
                    จดจำการเข้าสู่ระบบ
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition shadow-md shadow-indigo-500/20">
                เข้าสู่ระบบ 🚀
            </button>
        </form>

        <!-- Demo Accounts Box for Presentation -->
        <div class="mt-6 pt-4 border-t border-slate-100 bg-slate-50 p-3.5 rounded-xl text-xs text-slate-600">
            <p class="font-bold text-slate-800 mb-1">💡 บัญชีทดสอบสำหรับการนำเสนอ (Demo Credentials):</p>
            <p><strong>Email:</strong> admin@ithelpdesk.com</p>
            <p><strong>Password:</strong> password123</p>
        </div>
    </div>
</div>
@endsection
