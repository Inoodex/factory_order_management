<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get configured global currency symbol (e.g. $, BDT, €, £)
     */
    function currency_symbol(string $default = '$'): string
    {
        return get_setting('currency_symbol', $default) ?: $default;
    }
}

if (!function_exists('currency_code')) {
    /**
     * Get configured global currency code (e.g. USD, EUR, BDT)
     */
    function currency_code(string $default = 'USD'): string
    {
        return get_setting('currency_code', $default) ?: $default;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format an amount with currency symbol or code
     */
    function format_currency($amount, bool $showSymbol = true, int $decimals = 2): string
    {
        $formatted = number_format((float) $amount, $decimals);
        if ($showSymbol) {
            return currency_symbol() . ' ' . $formatted;
        }
        return $formatted;
    }
}

if (!function_exists('get_pdf_bg_path')) {
    /**
     * Get the absolute normalized file path for PDF background image.
     *
     * @param string $type 'invoice' or 'report'
     * @return string|null
     */
    function get_pdf_bg_path(string $type = 'report'): ?string
    {
        $key = ($type === 'invoice') ? 'pdf_invoice_bg' : 'pdf_report_bg';
        $settingValue = get_setting($key);

        if ($settingValue && Storage::disk('public')->exists($settingValue)) {
            $fullPath = Storage::disk('public')->path($settingValue);
            return 'file:///' . str_replace('\\', '/', $fullPath);
        }

        $defaultPath = public_path('assets/images/inoodex_invoice.jpg');
        if (file_exists($defaultPath)) {
            return 'file:///' . str_replace('\\', '/', $defaultPath);
        }

        return null;
    }
}
