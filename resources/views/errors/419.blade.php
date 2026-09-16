@extends('errors.layout')
@section('title', '419 - Session Expired')

@section('glow_color_1', '#10b981')
@section('glow_color_2', '#06b6d4')
@section('glow_color_3', '#047857')
@section('status_label', '419 Token Timed Out')
@section('status_dot', 'bg-emerald-500')
@section('status_ping', 'bg-emerald-400')

@section('content')
    <!-- Animated Illustration / Hourglass & Sync Badge -->
    <div class="mb-6 relative flex items-center justify-center">
        <!-- Outer Glowing Ring -->
        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full bg-gradient-to-tr from-emerald-500/20 via-teal-500/15 to-cyan-500/10 dark:from-emerald-500/30 dark:via-teal-500/20 dark:to-cyan-500/10 flex items-center justify-center relative shadow-inner p-2">
            <!-- Ripple effect -->
            <div class="absolute inset-0 rounded-full border border-emerald-500/30 animate-ripple pointer-events-none"></div>
            <div class="absolute inset-3 rounded-full border border-teal-400/20 pointer-events-none"></div>

            <!-- Rotating sync circle -->
            <div class="absolute inset-2 rounded-full border-2 border-dashed border-emerald-500/30 animate-gear pointer-events-none"></div>

            <!-- Inner Core Sphere -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-white to-emerald-50 dark:from-slate-800 dark:to-slate-900 border border-emerald-200 dark:border-emerald-500/30 shadow-lg flex items-center justify-center relative z-10 animate-float">
                <!-- Clock / Sync Refresh SVG -->
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Floating Mini Badge -->
            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-md text-xs font-black">
                <svg class="w-4 h-4 animate-spin" style="animation-duration: 4s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
        </div>
    </div>

    <!-- 419 Gradient Number -->
    <div class="relative inline-block mb-2">
        <h1 class="font-heading text-7xl sm:text-8xl font-extrabold tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-emerald-600 via-teal-500 to-cyan-500 dark:from-emerald-400 dark:via-teal-400 dark:to-cyan-400">
            419
        </h1>
    </div>

    <!-- Title & Description -->
    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
        Session Expired
    </h2>

    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-8">
        Your security token has timed out due to inactivity. This automatic safeguard protects pending orders, financial transactions, and user identity.
    </p>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button type="button" onclick="window.location.reload()" class="btn-modern-primary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh & Resume
        </button>

        <a href="{{ route('tyro-login.login') }}" class="btn-modern-secondary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Log In Again
        </a>
    </div>
@endsection