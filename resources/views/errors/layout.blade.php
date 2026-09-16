<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'Error') | {{ get_setting('app_name', config('app.name', 'Factory Order Management')) }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ get_setting('app_favicon') ? asset('storage/' . get_setting('app_favicon')) : asset('favicon.ico') }}">
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- App Stylesheet -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css') }}">
    
    <!-- Theme Detection Script (Prevents flash) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <style>
        :root {
            --font-heading: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            font-family: var(--font-body);
        }

        h1, h2, h3, .font-heading {
            font-family: var(--font-heading);
        }

        /* Ambient glowing dots and grid background */
        .ambient-grid-light {
            background-image: 
                radial-gradient(rgba(99, 102, 241, 0.08) 1px, transparent 1px),
                radial-gradient(rgba(148, 163, 184, 0.15) 1px, transparent 1px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }

        .dark .ambient-grid-dark {
            background-image: 
                radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
                radial-gradient(rgba(99, 102, 241, 0.12) 1px, transparent 1px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }

        /* Glassmorphic card styling */
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
        }

        .dark .glass-panel {
            background: rgba(14, 23, 38, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        }

        /* Keyframe micro-animations */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.75; transform: scale(1.08); }
        }

        @keyframes radarScan {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes gearRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes gearRotateReverse {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        @keyframes ripple {
            0% { transform: scale(0.9); opacity: 0.8; }
            100% { transform: scale(1.8); opacity: 0; }
        }

        .animate-float {
            animation: floatSlow 6s ease-in-out infinite;
        }

        .animate-pulse-glow {
            animation: pulseGlow 4s ease-in-out infinite;
        }

        .animate-radar {
            animation: radarScan 4s linear infinite;
        }

        .animate-gear {
            animation: gearRotate 12s linear infinite;
        }

        .animate-gear-reverse {
            animation: gearRotateReverse 12s linear infinite;
        }

        .animate-ripple {
            animation: ripple 2.5s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }

        /* Custom buttons */
        .btn-modern-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            font-weight: 600;
            font-size: 0.925rem;
            border-radius: 0.875rem;
            color: #ffffff;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.38);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px 0 rgba(79, 70, 229, 0.48);
            background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
        }

        .btn-modern-primary:active {
            transform: translateY(0);
        }

        .btn-modern-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.925rem;
            border-radius: 0.875rem;
            color: #334155;
            background: rgba(241, 245, 249, 0.8);
            border: 1px solid rgba(203, 213, 225, 0.8);
            backdrop-filter: blur(8px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark .btn-modern-secondary {
            color: #cbd5e1;
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(51, 65, 85, 0.8);
        }

        .btn-modern-secondary:hover {
            transform: translateY(-2px);
            background: #ffffff;
            color: #0f172a;
            border-color: #cbd5e1;
            box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.08);
        }

        .dark .btn-modern-secondary:hover {
            background: rgba(51, 65, 85, 0.8);
            color: #f8fafc;
            border-color: #475569;
            box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.35);
        }

        .btn-modern-secondary:active {
            transform: translateY(0);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col justify-between bg-slate-50 dark:bg-[#070d19] text-slate-700 dark:text-slate-200 antialiased transition-colors duration-300 relative overflow-x-hidden ambient-grid-light dark:ambient-grid-dark">

    <!-- Ambient glowing backdrop blobs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <!-- Blob 1: Top Left Ambient -->
        <div class="absolute -top-40 -left-40 w-96 h-96 sm:w-[520px] sm:h-[520px] rounded-full filter blur-3xl opacity-30 dark:opacity-20 animate-pulse-glow"
             style="background: radial-gradient(circle, @yield('glow_color_1', '#6366f1') 0%, transparent 70%);">
        </div>
        
        <!-- Blob 2: Bottom Right Ambient -->
        <div class="absolute -bottom-40 -right-40 w-96 h-96 sm:w-[560px] sm:h-[560px] rounded-full filter blur-3xl opacity-25 dark:opacity-20 animate-pulse-glow"
             style="animation-delay: 2s; background: radial-gradient(circle, @yield('glow_color_2', '#06b6d4') 0%, transparent 70%);">
        </div>
        
        <!-- Blob 3: Center Halo -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 sm:w-[480px] sm:h-[480px] rounded-full filter blur-3xl opacity-20 dark:opacity-15 pointer-events-none"
             style="background: radial-gradient(circle, @yield('glow_color_3', '#8b5cf6') 0%, transparent 75%);">
        </div>
    </div>

    <!-- Header bar -->
    <header class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 pt-6 sm:pt-8 flex items-center justify-between">
        <!-- Brand identity -->
        <a href="{{ url('/') }}" class="group flex items-center gap-3 transition-transform hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 p-2 border border-slate-200 dark:border-slate-700/60 shadow-sm flex items-center justify-center group-hover:shadow-md transition-all">
                <img src="{{ get_setting('app_logo') ? asset('storage/' . get_setting('app_logo')) : asset('assets/images/logo.svg') }}" 
                     alt="Logo" 
                     class="w-full h-full object-contain"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <span class="font-heading font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight leading-tight block">
                    {{ get_setting('app_name', config('app.name', 'Factory Order Management')) }}
                </span>
                <span class="text-xs text-slate-400 dark:text-slate-400 font-medium">Enterprise Manufacturing Portal</span>
            </div>
        </a>

        <!-- System badge & Theme Toggle -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- System Status indicator -->
            <div class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-white/70 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 backdrop-blur-md shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full @yield('status_ping', 'bg-amber-400') opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 @yield('status_dot', 'bg-amber-500')"></span>
                </span>
                <span class="text-slate-600 dark:text-slate-300">@yield('status_label', 'System Notice')</span>
            </div>

            <!-- Dark / Light theme toggle -->
            <button id="themeToggleBtn" type="button" aria-label="Toggle theme"
                    class="p-2.5 rounded-xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 backdrop-blur-md shadow-sm hover:shadow transition-all">
                <!-- Sun icon (shown in dark mode) -->
                <svg id="sunIcon" class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
                <!-- Moon icon (shown in light mode) -->
                <svg id="moonIcon" class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 sm:px-6 py-10 sm:py-16">
        <div class="w-full max-w-xl mx-auto">
            <div class="glass-panel rounded-3xl p-6 sm:p-10 text-center relative overflow-hidden transition-all">
                @yield('content')
            </div>
            
            <!-- Quick navigation links bar -->
            <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                <a href="{{ route('tyro-dashboard.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <a href="{{ route('admin.factory-orders.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Factory Orders
                </a>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <a href="{{ route('admin.customer-orders.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Customer Orders
                </a>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <button type="button" onclick="window.history.back()" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back (Esc)
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 text-center text-xs text-slate-400 dark:text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2 border-t border-slate-200/60 dark:border-slate-800/60">
        <div>
            &copy; {{ date('Y') }} {{ get_setting('app_name', config('app.name', 'Factory Order Management')) }}. All rights reserved.
        </div>
        <div class="flex items-center gap-4 text-xs">
            <span class="inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                API Gateway Healthy
            </span>
            <span>Version 2.4</span>
        </div>
    </footer>

    <!-- Theme Toggle & Keyboard Shortcut Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('themeToggleBtn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                });
            }

            // Keyboard navigation shortcuts
            document.addEventListener('keydown', function(e) {
                // Esc or Backspace (when not in an input) to go back
                if (e.key === 'Escape') {
                    if (window.history.length > 1) {
                        window.history.back();
                    } else {
                        window.location.href = "{{ route('tyro-dashboard.index') }}";
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>