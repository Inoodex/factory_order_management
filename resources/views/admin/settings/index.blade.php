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
                        <li class="mr-2">
                            <a href="#" @click.prevent="activeTab = 'pdf'"
                                :class="{ 'text-primary border-primary': activeTab === 'pdf', 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'pdf' }"
                                class="inline-block p-4 border-b-2 border-transparent rounded-t-lg">PDF & Templates</a>
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
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="app_name">Application Name</label>
                                    <input id="app_name" type="text" name="app_name"
                                        value="{{ $settings['app_name'] ?? config('app.name') }}" class="form-input" />
                                </div>
                                <div>
                                    <label for="currency_symbol">Currency Symbol</label>
                                    <input id="currency_symbol" type="text" name="currency_symbol"
                                        value="{{ $settings['currency_symbol'] ?? '$' }}" placeholder="$, BDT, €, £" class="form-input font-bold font-mono" />
                                </div>
                                <div>
                                    <label for="currency_code">Currency Code</label>
                                    <input id="currency_code" type="text" name="currency_code"
                                        value="{{ $settings['currency_code'] ?? 'USD' }}" placeholder="USD, EUR, BDT" class="form-input font-bold uppercase font-mono" />
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

                        <!-- PDF & Templates Tab -->
                        <div x-show="activeTab === 'pdf'" style="display: none;">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <!-- Invoice Background Box -->
                                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-[#1b2e4b]/40">
                                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                                        <div>
                                            <h6 class="font-bold text-base text-gray-800 dark:text-white-light">1. Invoice PDF Background</h6>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Used for Customer Invoices and Payment Receipts</p>
                                        </div>
                                        <span class="badge {{ !empty($settings['pdf_invoice_bg']) ? 'badge-outline-primary' : 'badge-outline-secondary' }}">
                                            {{ !empty($settings['pdf_invoice_bg']) ? 'Custom Upload' : 'Default Preset' }}
                                        </span>
                                    </div>

                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="pdf_invoice_bg" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Upload New Background Image (JPG / PNG - Max 4MB, A4 recommended)</label>
                                            <input id="pdf_invoice_bg" type="file" name="pdf_invoice_bg" accept="image/jpeg,image/png,image/jpg"
                                                class="form-input file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" />
                                        </div>

                                        @if (!empty($settings['pdf_invoice_bg']))
                                            <div class="flex items-start gap-4 rounded-md border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0e1726]">
                                                <a href="{{ asset('storage/' . $settings['pdf_invoice_bg']) }}" target="_blank" title="Click to view full size">
                                                    <img src="{{ asset('storage/' . $settings['pdf_invoice_bg']) }}"
                                                        alt="Invoice Background Preview" class="h-28 w-20 object-cover rounded shadow-sm border border-gray-200 dark:border-gray-700 hover:opacity-90 transition" />
                                                </a>
                                                <div class="flex-1 text-xs space-y-2">
                                                    <p class="font-semibold text-gray-700 dark:text-gray-300">Active Custom Background</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-[11px]">Click thumbnail to view full image in new tab.</p>
                                                    <label class="inline-flex items-center gap-2 cursor-pointer text-danger font-medium hover:underline pt-1">
                                                        <input type="checkbox" name="remove_pdf_invoice_bg" value="1" class="form-checkbox text-danger rounded" />
                                                        <span>Reset to default template</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-start gap-4 rounded-md border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0e1726]">
                                                <a href="{{ asset('assets/images/inoodex_invoice.jpg') }}" target="_blank" title="Click to view full size">
                                                    <img src="{{ asset('assets/images/inoodex_invoice.jpg') }}"
                                                        alt="Default Invoice Background" class="h-28 w-20 object-cover rounded shadow-sm border border-gray-200 dark:border-gray-700 hover:opacity-90 transition" />
                                                </a>
                                                <div class="flex-1 text-xs space-y-1">
                                                    <p class="font-semibold text-gray-700 dark:text-gray-300">Default Template Active</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-[11px]">Using standard inoodex invoice background letterhead.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Report Background Box -->
                                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-[#1b2e4b]/40">
                                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                                        <div>
                                            <h6 class="font-bold text-base text-gray-800 dark:text-white-light">2. Report PDF Background</h6>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Used for Financial Summary, Balance Sheet, Payments, Expenses & Journal Vouchers</p>
                                        </div>
                                        <span class="badge {{ !empty($settings['pdf_report_bg']) ? 'badge-outline-primary' : 'badge-outline-secondary' }}">
                                            {{ !empty($settings['pdf_report_bg']) ? 'Custom Upload' : 'Default Preset' }}
                                        </span>
                                    </div>

                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="pdf_report_bg" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Upload New Background Image (JPG / PNG - Max 4MB, A4 recommended)</label>
                                            <input id="pdf_report_bg" type="file" name="pdf_report_bg" accept="image/jpeg,image/png,image/jpg"
                                                class="form-input file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" />
                                        </div>

                                        @if (!empty($settings['pdf_report_bg']))
                                            <div class="flex items-start gap-4 rounded-md border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0e1726]">
                                                <a href="{{ asset('storage/' . $settings['pdf_report_bg']) }}" target="_blank" title="Click to view full size">
                                                    <img src="{{ asset('storage/' . $settings['pdf_report_bg']) }}"
                                                        alt="Report Background Preview" class="h-28 w-20 object-cover rounded shadow-sm border border-gray-200 dark:border-gray-700 hover:opacity-90 transition" />
                                                </a>
                                                <div class="flex-1 text-xs space-y-2">
                                                    <p class="font-semibold text-gray-700 dark:text-gray-300">Active Custom Background</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-[11px]">Click thumbnail to view full image in new tab.</p>
                                                    <label class="inline-flex items-center gap-2 cursor-pointer text-danger font-medium hover:underline pt-1">
                                                        <input type="checkbox" name="remove_pdf_report_bg" value="1" class="form-checkbox text-danger rounded" />
                                                        <span>Reset to default template</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-start gap-4 rounded-md border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-[#0e1726]">
                                                <a href="{{ asset('assets/images/inoodex_invoice.jpg') }}" target="_blank" title="Click to view full size">
                                                    <img src="{{ asset('assets/images/inoodex_invoice.jpg') }}"
                                                        alt="Default Report Background" class="h-28 w-20 object-cover rounded shadow-sm border border-gray-200 dark:border-gray-700 hover:opacity-90 transition" />
                                                </a>
                                                <div class="flex-1 text-xs space-y-1">
                                                    <p class="font-semibold text-gray-700 dark:text-gray-300">Default Template Active</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-[11px]">Using standard inoodex invoice background letterhead.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
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
