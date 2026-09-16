@extends('errors.layout')
@section('title', '403 - Access Denied')

@section('glow_color_1', '#ef4444')
@section('glow_color_2', '#f97316')
@section('glow_color_3', '#b91c1c')
@section('status_label', '403 Security Restriction')
@section('status_dot', 'bg-rose-500')
@section('status_ping', 'bg-rose-400')

@section('content')
    <!-- Animated Illustration / Security Shield Badge -->
    <div class="mb-6 relative flex items-center justify-center">
        <!-- Outer Glowing Ring -->
        <div class="w-32 h-32 sm:w-36 sm:h-36 rounded-full bg-gradient-to-tr from-rose-500/20 via-red-500/15 to-amber-500/10 dark:from-rose-500/30 dark:via-red-500/20 dark:to-amber-500/10 flex items-center justify-center relative shadow-inner p-2">
            <!-- Ripple warning effect -->
            <div class="absolute inset-0 rounded-full border border-rose-500/30 animate-ripple pointer-events-none"></div>
            <div class="absolute inset-3 rounded-full border border-red-400/20 pointer-events-none"></div>

            <!-- Inner Core Sphere -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-tr from-white to-rose-50 dark:from-slate-800 dark:to-slate-900 border border-rose-200 dark:border-rose-500/30 shadow-lg flex items-center justify-center relative z-10 animate-float">
                <!-- Lock & Security Shield SVG -->
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            <!-- Floating Warning Badge -->
            <div class="absolute -top-1 -right-1 w-7 h-7 rounded-lg bg-rose-600 text-white flex items-center justify-center shadow-md text-xs font-black">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
        </div>
    </div>

    <!-- 403 Gradient Number -->
    <div class="relative inline-block mb-2">
        <h1 class="font-heading text-7xl sm:text-8xl font-extrabold tracking-tighter leading-none bg-clip-text text-transparent bg-gradient-to-r from-rose-600 via-red-500 to-amber-500 dark:from-rose-400 dark:via-red-400 dark:to-amber-400">
            403
        </h1>
    </div>

    <!-- Title & Description -->
    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-3">
        Access Denied
    </h2>

    <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-6">
        @if($exception && $exception->getMessage() && $exception->getMessage() !== 'This action is unauthorized.')
            <span class="font-medium text-slate-800 dark:text-slate-200 block mb-2">{{ $exception->getMessage() }}</span>
        @else
            You don't have the necessary privileges or assigned role to view this protected resource.
        @endif
    </p>

    <!-- Checklist of reasons in clean card -->
    @if(!$exception || !$exception->getMessage() || $exception->getMessage() === 'This action is unauthorized.')
        <div class="mb-8 text-left bg-slate-50/90 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 rounded-2xl p-4 sm:p-5 space-y-2.5 max-w-md mx-auto text-xs sm:text-sm">
            <div class="flex items-start gap-3">
                <span class="w-5 h-5 rounded-md bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 mt-0.5 font-bold">1</span>
                <span class="text-slate-600 dark:text-slate-300">Your account does not possess the required module clearance.</span>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-5 h-5 rounded-md bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 mt-0.5 font-bold">2</span>
                <span class="text-slate-600 dark:text-slate-300">This order or finance record may be restricted to administrative staff.</span>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-5 h-5 rounded-md bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 mt-0.5 font-bold">3</span>
                <span class="text-slate-600 dark:text-slate-300">If you believe this is an error, please request clearance from your system administrator.</span>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('tyro-dashboard.index') }}" class="btn-modern-primary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Return to Dashboard
        </a>

        <button type="button" onclick="window.history.back()" class="btn-modern-secondary w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Go Back
        </button>
    </div>
@endsection