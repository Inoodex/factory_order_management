<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-settings');
    }

    public function index()
    {
        $this->authorize('manage-settings');

        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorize('manage-settings');

        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'date_format' => 'nullable|string|max:50',
            'enable_registration' => 'nullable|boolean',
            'maintenance_mode' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $keys = [
            'app_name',
            'contact_email',
            'contact_phone',
            'address',
            'social_facebook',
            'social_twitter',
            'social_linkedin',
            'date_format',
            'enable_registration',
            'maintenance_mode',
            'meta_title',
            'meta_description',
            'meta_keywords',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            } else {
                // Handle checkboxes that are unchecked (not sent in request)
                if (in_array($key, ['enable_registration', 'maintenance_mode'])) {
                    Setting::updateOrCreate(['key' => $key], ['value' => '0']);
                }
            }
        }

        if ($request->hasFile('app_logo')) {
            $oldLogo = Setting::where('key', 'app_logo')->value('value');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logo = $request->file('app_logo');
            $logoPath = $logo->store('uploads/settings', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $logoPath]);
        }

        if ($request->hasFile('app_favicon')) {
            $oldFavicon = Setting::where('key', 'app_favicon')->value('value');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $favicon = $request->file('app_favicon');
            $faviconPath = $favicon->store('uploads/settings', 'public');
            Setting::updateOrCreate(['key' => 'app_favicon'], ['value' => $faviconPath]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
