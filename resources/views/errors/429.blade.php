@extends('errors.layout')
@section('title', '429 - Too Many Requests')

@section('glow_color_1', '#f97316')
@section('glow_color_2', '#ef4444')
@section('glow_color_3', '#ea580c')
@section('status_label', '429 Rate Throttled')
@section('status_dot', 'bg-orange-500')
@section('status_ping', 'bg-orange-400')

@section('content')
    <!-- Animated Tachometer / Throttle Gauge Badge -->
    <div class="mb-6 relative flex items-center justify-center">
        <!-- Outer Glowing Ring -->
        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full bg-gradient-to-tr from-orange-500/20 via-amber-500/15 to-red-500/10 dark:from-orange-500/30 dark:via-amber-500/20 dark:to-red-500/10 flex items-center justify-center relative shadow-inner p-2">
            <!-- Ripple effect -->
            <div class="absolute inset-0 rounded-full border border-orange-500/30 animate-ripple pointer-events-none"></div>
            <div class="absolute inset-3 rounded-full border border-amber-400/20 pointer-events-none"></div>

            <!-- Inner Core Sphere -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-white to-orange-50 dark:from-slate-800 dark:to-slate-900 border border-orange-200 dark:border-orange-500/30 shadow-lg flex items-center justify-center relative z-10 animate-float">
                <!-- Gauge / Speedometer SVG -->
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" opacity="0.35" />
                </svg>
            </div>

            <!-- Floating Warning Badge -->
            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-lg bg-orange-600 text-white flex items-center justify-center shadow-md text-xs font-black">
                ⚡
            </div>
        </div>
    </div>

    <!-- 429 Gradient Number -->
    <div class="relative inline-block mb-2">
        <h1 class="font-heading text-7xl sm:text-8xl font-extrabold tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-orange-600 via-amber-500 to-red-500 dark:from-orange-400 dark:via-amber-400 dark:to-red-400">
            429
        </h1>
    </div>

    <!-- Title & Description -->
    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
        Too Many Requests
    </h2>

    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-8">
        You've made too many requests in a short time window. Please wait a few seconds before refreshing to prevent server congestion.
    </p>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button type="button" onclick="window.location.reload()" class="btn-modern-primary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Try Again Now
        </button>

        <a href="{{ route('tyro-dashboard.index') }}" class="btn-modern-secondary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Return to Dashboard
        </a>
    </div>
@endsection
