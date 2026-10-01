<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ระบบแจ้งซ่อม IT - Helpdesk System')</title>
    <!-- Google Fonts: Prompt & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Prompt', 'Inter', sans-serif;
        }
        .code-font {
            font-family: 'Inter', monospace;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">
    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md shadow-blue-500/20">
                            IT
                        </div>
                        <div>
                            <span class="text-lg font-bold text-slate-900 tracking-tight block leading-tight">ระบบแจ้งซ่อม IT</span>
                            <span class="text-xs text-slate-500 font-medium">IT Repair & Helpdesk System</span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-slate-600 hover:text-blue-600 font-medium text-sm transition">หน้าหลัก</a>
                    <a href="{{ route('tickets.create') }}" class="text-slate-600 hover:text-blue-600 font-medium text-sm transition">แจ้งซ่อม</a>
                    <a href="{{ route('tickets.track') }}" class="text-slate-600 hover:text-blue-600 font-medium text-sm transition">ติดตามสถานะ</a>

                    @auth
                        <div class="h-5 w-px bg-slate-300 mx-1"></div>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                            🛠️ จัดการงานซ่อม
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium ml-2">ออกจากระบบ</button>
                        </form>
                    @else
                        <div class="h-5 w-px bg-slate-300 mx-1"></div>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100 transition">
                            🔒 เจ้าหน้าที่ IT
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center shadow-sm">
                <span class="mr-2 text-xl">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl flex items-center shadow-sm">
                <span class="mr-2 text-xl">ℹ️</span>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
                <div class="font-semibold mb-1 flex items-center">
                    <span class="mr-2 text-lg">⚠️</span> เกิดข้อผิดพลาด กรุณาตรวจสอบข้อมูล:
                </div>
                <ul class="list-disc list-inside text-sm space-y-0.5 ml-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
            <p>© 2026 IT Helpdesk & Repair System. พัฒนาด้วย Laravel Framework</p>
            <p class="mt-1 text-slate-400">ระบบแจ้งซ่อมและติดตามงานบริการไอที สำหรับการนำเสนอโครงงาน</p>
        </div>
    </footer>
</body>
</html>
