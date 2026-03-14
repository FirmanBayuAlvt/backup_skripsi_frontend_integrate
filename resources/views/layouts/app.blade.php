<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - TernakPark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f4c3a 0%, #1e7b5e 100%); }
        .sidebar-item { transition: all 0.2s; border-radius: 0.5rem; }
        .sidebar-item:hover { background: rgba(255,255,255,0.15); transform: translateX(4px); }
        .sidebar-item-active { background: rgba(255,255,255,0.2); border-left: 4px solid #fbbf24; }
        .card-hover { transition: all 0.3s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .loading-spinner { border: 3px solid #e2e8f0; border-top: 3px solid #1e7b5e; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 sidebar-gradient text-white flex flex-col shadow-2xl">
            <div class="p-5 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo TernakPark Wonosalam.png') }}" alt="TernakPark" class="h-10 w-auto">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">TernakPark</h1>
                        <p class="text-xs text-green-100">Wonosalam Jombang</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-chart-pie w-5 text-center"></i><span class="text-sm font-medium">Dashboard</span>
                </a>
                <a href="{{ route('livestocks.index') }}" class="sidebar-item flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('livestocks.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-cow w-5 text-center"></i><span class="text-sm font-medium">Ternak</span>
                </a>
                <a href="{{ route('pens.index') }}" class="sidebar-item flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('pens.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-warehouse w-5 text-center"></i><span class="text-sm font-medium">Kandang</span>
                </a>
                <a href="{{ route('feeds.index') }}" class="sidebar-item flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('feeds.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-seedling w-5 text-center"></i><span class="text-sm font-medium">Pakan</span>
                </a>
                <a href="{{ route('predictions.index') }}" class="sidebar-item flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('predictions.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-brain w-5 text-center"></i><span class="text-sm font-medium">Prediksi</span>
                </a>
                <a href="{{ route('reports.index') }}" class="sidebar-item flex items-center space-x-3 px-3 py-2.5 {{ request()->routeIs('reports.*') ? 'sidebar-item-active' : '' }}">
                    <i class="fas fa-chart-bar w-5 text-center"></i><span class="text-sm font-medium">Laporan</span>
                </a>
            </nav>
            <div class="p-3 border-t border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ session('user')['name'] ?? 'Admin' }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-green-200 hover:text-white transition">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-3 flex justify-between items-center sticky top-0 z-10 shadow-sm">
                <h1 class="text-2xl font-semibold text-gray-800">@yield('header-title', 'Dashboard')</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y, H:i') }}</span>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100 transition">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-20">
                            <div class="p-3 border-b"><span class="font-semibold">Notifikasi</span></div>
                            <div class="p-3 text-sm text-gray-600">Stok pakan menipis</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Page Header (jika ada) --}}
                @hasSection('page-header')
                    <div class="mb-6">
                        @yield('page-header')
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        window.TernakPark = {
            api: {
                fetchData: async (url, options = {}) => {
                    const res = await fetch(url, options);
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return await res.json();
                }
            },
            format: {
                number: (num) => new Intl.NumberFormat('id-ID').format(num),
                currency: (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(num),
                date: (d) => new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }),
                timeAgo: (d) => {
                    const diff = Date.now() - new Date(d);
                    const min = Math.floor(diff / 60000);
                    if (min < 60) return min + ' menit lalu';
                    const jam = Math.floor(min / 60);
                    if (jam < 24) return jam + ' jam lalu';
                    return Math.floor(jam / 24) + ' hari lalu';
                }
            },
            ui: {
                showToast: (msg, type = 'success') => {
                    alert(`${type.toUpperCase()}: ${msg}`);
                }
            }
        };
    </script>
    @stack('scripts')
</body>
</html>
