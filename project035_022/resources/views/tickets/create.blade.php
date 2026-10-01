@extends('layouts.app')

@section('title', 'สร้างคำขอแจ้งซ่อมใหม่ - ระบบแจ้งซ่อม IT')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('home') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center mb-2">
            ← ย้อนกลับไปหน้าหลัก
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">📝 ฟอร์มแจ้งซ่อมอุปกรณ์ / แจ้งปัญหา IT</h1>
        <p class="text-slate-500 text-sm mt-1">กรุณากรอกข้อมูลปัญหาให้ครบถ้วนเพื่อความรวดเร็วในการประสานงานและเข้าตรวจสอบของช่าง</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: ข้อมูลผู้แจ้ง -->
            <div>
                <h2 class="text-lg font-bold text-slate-900 border-b pb-2 mb-4 flex items-center">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center mr-2">1</span>
                    ข้อมูลผู้แจ้งซ่อม
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="requester_name" class="block text-sm font-medium text-slate-700 mb-1">
                            ชื่อ - นามสกุล <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="requester_name" id="requester_name" value="{{ old('requester_name') }}" required
                            placeholder="เช่น นายสมชาย ใจดี"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label for="requester_email" class="block text-sm font-medium text-slate-700 mb-1">
                            อีเมลสำหรับติดต่อ <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="requester_email" id="requester_email" value="{{ old('requester_email') }}" required
                            placeholder="example@company.com"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label for="requester_phone" class="block text-sm font-medium text-slate-700 mb-1">
                            เบอร์โทรศัพท์ติดต่อ
                        </label>
                        <input type="text" name="requester_phone" id="requester_phone" value="{{ old('requester_phone') }}"
                            placeholder="08X-XXX-XXXX หรือ เบอร์โต๊ะทำงาน"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-slate-700 mb-1">
                            แผนก / อาคารและชั้น
                        </label>
                        <input type="text" name="department" id="department" value="{{ old('department') }}"
                            placeholder="เช่น แผนกบัญชี ชั้น 3 อาคาร A"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Section 2: รายละเอียดปัญหาและฟีเจอร์ใหม่ -->
            <div class="pt-4">
                <h2 class="text-lg font-bold text-slate-900 border-b pb-2 mb-4 flex items-center">
                    <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center mr-2">2</span>
                    รายละเอียดการแจ้งซ่อม
                </h2>

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                            หัวข้อปัญหา <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            placeholder="เช่น เปิดเครื่องคอมพิวเตอร์ไม่ติด มีเสียงร้องเตือน, จอฟ้า Bluescreen"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- ⭐ ฟีเจอร์ที่ 2: หมวดหมู่อุปกรณ์ -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-slate-700 mb-1">
                                หมวดหมู่อุปกรณ์ / ปัญหา <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                                <option value="hardware" {{ old('category') == 'hardware' ? 'selected' : '' }}>💻 คอมพิวเตอร์ / ฮาร์ดแวร์</option>
                                <option value="software" {{ old('category') == 'software' ? 'selected' : '' }}>💾 ซอฟต์แวร์ / ติดตั้งโปรแกรม</option>
                                <option value="network" {{ old('category') == 'network' ? 'selected' : '' }}>🌐 ระบบเครือข่าย / อินเทอร์เน็ต</option>
                                <option value="printer" {{ old('category') == 'printer' ? 'selected' : '' }}>🖨️ เครื่องพิมพ์ / สแกนเนอร์</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>⚙️ อื่นๆ</option>
                            </select>
                        </div>

                        <!-- ⭐ ฟีเจอร์ที่ 2: ระดับความเร่งด่วน -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-slate-700 mb-1">
                                ระดับความเร่งด่วน <span class="text-red-500">*</span>
                            </label>
                            <select name="priority" id="priority" required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>⚪ ต่ำ (Low) - มีเครื่องสำรองใช้งานได้</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>🔵 ปานกลาง (Medium) - รบกวนการทำงานทั่วไป</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 เร่งด่วน (High) - ไม่สามารถทำงานสำคัญได้</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 ด่วนที่สุด (Urgent) - ระบบล่ม/กระทบหลายคน</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
                            รายละเอียดอาการและข้อผิดพลาดที่พบ <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                            placeholder="ระบุอาการอย่างละเอียด เช่น เกิดขึ้นเมื่อไหร่ มีข้อความแจ้งเตือน error code อะไรขึ้นมา..."
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('description') }}</textarea>
                    </div>

                    <!-- ⭐ ฟีเจอร์ที่ 1: แนบรูปถ่ายหลักฐานปัญหา -->
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                        <label for="image" class="block text-sm font-bold text-slate-900 mb-1 flex items-center">
                            <span class="mr-2">📸</span> แนบรูปถ่ายหรือหน้าจอที่พบปัญหา (อุปกรณ์ / ข้อความ Error)
                        </label>
                        <p class="text-xs text-slate-500 mb-3">ช่วยให้ช่างไอทีประเมินอะไหล่และวิเคราะห์สาเหตุได้ทันที (รองรับ JPG, PNG, WEBP ขนาดไม่เกิน 2MB)</p>
                        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-600 file:text-white
                            hover:file:bg-blue-700 file:cursor-pointer cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t flex justify-end space-x-3">
                <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium text-sm transition">
                    ยกเลิก
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition shadow-md shadow-blue-500/20">
                    ยืนยันการส่งคำขอแจ้งซ่อม 🚀
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
