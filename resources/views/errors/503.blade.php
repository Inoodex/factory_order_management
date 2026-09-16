@extends('errors.layout')
@section('title', '503 - Service Unavailable')

@section('glow_color_1', '#06b6d4')
@section('glow_color_2', '#3b82f6')
@section('glow_color_3', '#6366f1')
@section('status_label', '503 Scheduled Maintenance')
@section('status_dot', 'bg-sky-500')
@section('status_ping', 'bg-sky-400')

@section('content')
    <!-- Animated Illustration / Industrial Gears in Motion -->
    <div class="mb-6 relative flex items-center justify-center">
        <!-- Outer Glowing Ring -->
        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full bg-gradient-to-tr from-sky-500/20 via-indigo-500/15 to-cyan-500/10 dark:from-sky-500/30 dark:via-indigo-500/20 dark:to-cyan-500/10 flex items-center justify-center relative shadow-inner p-2">
            <!-- Ripple effect -->
            <div class="absolute inset-0 rounded-full border border-sky-500/30 animate-ripple pointer-events-none"></div>
            <div class="absolute inset-3 rounded-full border border-indigo-400/20 pointer-events-none"></div>

            <!-- Interlocking Rotating Gears -->
            <div class="absolute inset-2 flex items-center justify-center pointer-events-none">
                <!-- Outer Gear -->
                <svg class="w-24 h-24 text-sky-500/30 dark:text-sky-400/20 animate-gear" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
                </svg>
            </div>

            <!-- Inner Core Sphere -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-white to-sky-50 dark:from-slate-800 dark:to-slate-900 border border-sky-200 dark:border-sky-500/30 shadow-lg flex items-center justify-center relative z-10 animate-float">
                <!-- Wrench / Tool Cog SVG -->
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-sky-600 dark:text-sky-400 animate-gear" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <!-- Floating Mini Badge -->
            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-lg bg-sky-600 text-white flex items-center justify-center shadow-md text-xs font-black">
                ⚙
            </div>
        </div>
    </div>

    <!-- 503 Gradient Number -->
    <div class="relative inline-block mb-2">
        <h1 class="font-heading text-7xl sm:text-8xl font-extrabold tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-sky-600 via-blue-500 to-indigo-500 dark:from-sky-400 dark:via-blue-400 dark:to-indigo-400">
            503
        </h1>
    </div>

    <!-- Title & Description -->
    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
        System Maintenance
    </h2>

    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-6">
        We're currently performing routine upgrades, performance tuning, and infrastructure improvements to ensure optimal order processing speed and reliability.
    </p>

    <!-- Progress indicator pill -->
    <div class="mb-8 inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 text-xs font-semibold text-sky-700 dark:text-sky-300">
        <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
        Upgrades in Progress — Services Will Resume Shortly
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button type="button" onclick="window.location.reload()" class="btn-modern-primary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Check Status Again
        </button>

        <a href="{{ url('/') }}" class="btn-modern-secondary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Return Home
        </a>
    </div>
@endsection