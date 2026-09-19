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
