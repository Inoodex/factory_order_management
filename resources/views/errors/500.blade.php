@extends('errors.layout')
@section('title', '500 - Server Error')

@section('glow_color_1', '#ef4444')
@section('glow_color_2', '#8b5cf6')
@section('glow_color_3', '#dc2626')
@section('status_label', '500 Internal Error')
@section('status_dot', 'bg-rose-500')
@section('status_ping', 'bg-rose-400')

@section('content')
    <!-- Animated Illustration / Server Circuit Badge -->
    <div class="mb-6 relative flex items-center justify-center">
        <!-- Outer Glowing Ring -->
        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full bg-gradient-to-tr from-rose-500/20 via-purple-500/15 to-red-500/10 dark:from-rose-500/30 dark:via-purple-500/20 dark:to-red-500/10 flex items-center justify-center relative shadow-inner p-2">
            <!-- Ripple warning effect -->
            <div class="absolute inset-0 rounded-full border border-rose-500/30 animate-ripple pointer-events-none"></div>
            <div class="absolute inset-3 rounded-full border border-purple-400/20 pointer-events-none"></div>

            <!-- Inner Core Sphere -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-white to-rose-50 dark:from-slate-800 dark:to-slate-900 border border-rose-200 dark:border-rose-500/30 shadow-lg flex items-center justify-center relative z-10 animate-float">
                <!-- Server Alert SVG -->
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                </svg>
            </div>

            <!-- Floating Warning Badge -->
            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-lg bg-rose-600 text-white flex items-center justify-center shadow-md text-xs font-black">
                !
            </div>
        </div>
    </div>

    <!-- 500 Gradient Number -->
    <div class="relative inline-block mb-2">
        <h1 class="font-heading text-7xl sm:text-8xl font-extrabold tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-rose-600 via-purple-600 to-red-500 dark:from-rose-400 dark:via-purple-400 dark:to-red-400">
            500
        </h1>
    </div>

    <!-- Title & Description -->
    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
        Internal Server Error
    </h2>

    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-5">
        @if(config('app.debug') && $exception && $exception->getMessage())
            <span class="font-medium text-rose-600 dark:text-rose-400 block mb-2">{{ $exception->getMessage() }}</span>
        @else
            Something went wrong while processing your request on our servers. Our monitoring team has been notified and is looking into it.
        @endif
    </p>

    <!-- Incident Reference Box -->
    <div class="mb-8 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-xs font-mono text-slate-600 dark:text-slate-300">
        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
        Incident ID: <strong class="font-semibold text-slate-800 dark:text-slate-200">#ERR-{{ strtoupper(substr(md5(url()->current() . now()->timestamp), 0, 8)) }}</strong>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('tyro-dashboard.index') }}" class="btn-modern-primary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Return to Dashboard
        </a>

        <button type="button" onclick="window.location.reload()" class="btn-modern-secondary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Reload Page
        </button>
    </div>
@endsection