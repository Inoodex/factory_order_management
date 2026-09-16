@extends('admin.layouts.master')

@section('title', 'Application Settings')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Application Settings</h5>
        </div>
        <div class="mb-5">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5" x-data="{ activeTab: 'general' }">
                    <ul class="flex flex-wrap border-b border-gray-200 dark:border-gray-700">
                        <li class="mr-2">
                            <a href="#" @click.prevent="activeTab = 'general'"
                                :class="{ 'text-primary border-primary': activeTab === 'general', 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'general' }"
                                class="inline-block p-4 border-b-2 rounded-t-lg">General</a>
                        </li>
                        <li class="mr-2">
                            <a href="#" @click.prevent="activeTab = 'contact'"
                                :class="{ 'text-primary border-primary': activeTab === 'contact', 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'contact' }"
                                class="inline-block p-4 border-b-2 border-transparent rounded-t-lg">Contact</a>
                        </li>
                        {{-- <li class="mr-2">
                            <a href="#" @click.prevent="activeTab = 'social'"
                                :class="{ 'text-primary border-primary': activeTab === 'social', 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'social' }"
                                class="inline-block p-4 border-b-2 border-transparent rounded-t-lg">Social Media</a>
                        </li>  --}}
                        {{-- <li class="mr-2">
                            <a href="#" @click.prevent="activeTab = 'system'"
                                :class="{ 'text-primary border-primary': activeTab === 'system', 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'system' }"
                                class="inline-block p-4 border-b-2 border-transparent rounded-t-lg">System</a>
                        </li>
                        <li class="mr-2">
                            <a href="#" @click.prevent="activeTab = 'seo'"
                                :class="{ 'text-primary border-primary': activeTab === 'seo', 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'seo' }"
                                class="inline-block p-4 border-b-2 border-transparent rounded-t-lg">SEO</a>
                        </li> --}}
                    </ul>

                    <div class="mt-5">
                        <!-- General Tab -->
                        <div x-show="activeTab === 'general'">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="app_name">Application Name</label>
                                    <input id="app_name" type="text" name="app_name"
                                        value="{{ $settings['app_name'] ?? config('app.name') }}" class="form-input" />
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="app_logo">Application Logo</label>
                                    <input id="app_logo" type="file" name="app_logo"
                                        class="form-input file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" />
                                    @if (!empty($settings['app_logo']))
                                        <div class="mt-2 flex items-center gap-3">
                                            <img src="{{ Illuminate\Support\Str::startsWith($settings['app_logo'], 'http') ? $settings['app_logo'] : asset('storage/' . $settings['app_logo']) }}"
                                                alt="Logo" class="h-20 max-w-[180px] object-contain rounded border border-gray-200 dark:border-gray-700 p-1 bg-white dark:bg-black/20" />
                                        </div>
                                    @else
                                        <div class="mt-2 text-xs text-gray-500">Current: Default Logo</div>
                                    @endif
                                </div>
                                <div>
                                    <label for="app_favicon">Application Favicon</label>
                                    <input id="app_favicon" type="file" name="app_favicon"
                                        class="form-input file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" />
                                    @if (!empty($settings['app_favicon']))
                                        <div class="mt-2 flex items-center gap-3">
                                            <img src="{{ Illuminate\Support\Str::startsWith($settings['app_favicon'], 'http') ? $settings['app_favicon'] : asset('storage/' . $settings['app_favicon']) }}"
                                                alt="Favicon" class="h-10 w-10 object-contain rounded border border-gray-200 dark:border-gray-700 p-1 bg-white dark:bg-black/20" />
                                        </div>
                                    @else
                                        <div class="mt-2 text-xs text-gray-500">Current: Default Favicon</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Contact Tab -->
                        <div x-show="activeTab === 'contact'" style="display: none;">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="contact_email">Contact Email</label>
                                    <input id="contact_email" type="email" name="contact_email"
                                        value="{{ $settings['contact_email'] ?? '' }}" class="form-input" />
                                </div>
                                <div>
                                    <label for="contact_phone">Contact Phone</label>
                                    <input id="contact_phone" type="text" name="contact_phone"
                                        value="{{ $settings['contact_phone'] ?? '' }}" class="form-input" />
                                </div>
                                <div class="col-span-2">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" rows="3" class="form-input">{{ $settings['address'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Social Tab -->
                        {{-- <div x-show="activeTab === 'social'" style="display: none;">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="social_facebook">Facebook URL</label>
                                    <input id="social_facebook" type="url" name="social_facebook"
                                        value="{{ $settings['social_facebook'] ?? '' }}" class="form-input"
                                        placeholder="https://facebook.com/..." />
                                </div>
                                <div>
                                    <label for="social_twitter">Twitter URL</label>
                                    <input id="social_twitter" type="url" name="social_twitter"
                                        value="{{ $settings['social_twitter'] ?? '' }}" class="form-input"
                                        placeholder="https://twitter.com/..." />
                                </div>
                                <div>
                                    <label for="social_linkedin">LinkedIn URL</label>
                                    <input id="social_linkedin" type="url" name="social_linkedin"
                                        value="{{ $settings['social_linkedin'] ?? '' }}" class="form-input"
                                        placeholder="https://linkedin.com/..." />
                                </div>
                            </div>
                        </div> --}}

                        <!-- System Tab -->
                        {{-- <div x-show="activeTab === 'system'" style="display: none;">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="currency_symbol">Currency Symbol</label>
                                    <input id="currency_symbol" type="text" name="currency_symbol"
                                        value="{{ $settings['currency_symbol'] ?? '$' }}" class="form-input" />
                                </div>
                                <div>
                                    <label for="date_format">Date Format</label>
                                    <select id="date_format" name="date_format" class="form-select">
                                        <option value="d/m/Y"
                                            {{ ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>d/m/Y
                                            (31/12/2026)</option>
                                        <option value="Y-m-d"
                                            {{ ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>Y-m-d
                                            (2026-12-31)</option>
                                        <option value="m/d/Y"
                                            {{ ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>m/d/Y
                                            (12/31/2026)</option>
                                        <option value="d-M-Y"
                                            {{ ($settings['date_format'] ?? '') == 'd-M-Y' ? 'selected' : '' }}>d-M-Y
                                            (31-Dec-2026)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="enable_registration" value="1"
                                            class="form-checkbox"
                                            {{ ($settings['enable_registration'] ?? '0') == '1' ? 'checked' : '' }} />
                                        <span class="ml-2">Enable User Registration</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="maintenance_mode" value="1"
                                            class="form-checkbox"
                                            {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }} />
                                        <span class="ml-2">Maintenance Mode</span>
                                    </label>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endsection
