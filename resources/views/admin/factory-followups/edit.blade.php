@extends('admin.layouts.master')

@section('title', 'Update Follow-up #' . $factoryFollowup->factoryOrder?->customerOrder?->order_no)

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold uppercase">Update Production Follow-up</h2>
            <p class="text-sm text-gray-500">
                Order #{{ $factoryFollowup->factoryOrder?->customerOrder?->order_no }}
                (Style: {{ $factoryFollowup->factoryOrder?->customerOrder?->style_no }})
            </p>
        </div>
        <a href="{{ route('admin.factory-followups.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.factory-followups.update', $factoryFollowup) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Pre-Production & Shipment Samples -->
            <div class="border-b pb-4">
                <h3 class="text-md font-bold uppercase text-primary mb-3">1. Sampling Milestones</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="pps_date" class="font-semibold">PPS Target/Submission Date</label>
                        <input type="date" id="pps_date" name="pps_date"
                            value="{{ old('pps_date', $factoryFollowup->pps_date?->format('Y-m-d')) }}" class="form-input" />
                        @error('pps_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="pps_comments_status" class="font-semibold">PPS Comments Status <span class="text-danger">*</span></label>
                        <select name="pps_comments_status" id="pps_comments_status" class="form-select" required>
                            @foreach (\App\Models\FactoryFollowup::PPS_STATUSES as $status)
                                <option value="{{ $status }}" {{ old('pps_comments_status', $factoryFollowup->pps_comments_status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('pps_comments_status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 mt-4">
                    <div>
                        <label for="shs_sending_date" class="font-semibold">SHS Sending Date</label>
                        <input type="date" id="shs_sending_date" name="shs_sending_date"
                            value="{{ old('shs_sending_date', $factoryFollowup->shs_sending_date?->format('Y-m-d')) }}" class="form-input" />
                        @error('shs_sending_date') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="shs_comments_status" class="font-semibold">SHS Comments Status <span class="text-danger">*</span></label>
                        <select name="shs_comments_status" id="shs_comments_status" class="form-select" required>
                            @foreach (\App\Models\FactoryFollowup::SHS_STATUSES as $status)
                                <option value="{{ $status }}" {{ old('shs_comments_status', $factoryFollowup->shs_comments_status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('shs_comments_status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Production Stages -->
            <div class="border-b pb-4">
                <h3 class="text-md font-bold uppercase text-primary mb-3">2. Factory Production Stages</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label for="knitting_status" class="font-semibold">Knitting<span class="text-danger">*</span></label>
                        <select name="knitting_status" id="knitting_status" class="form-select" required>
                            @foreach (\App\Models\FactoryFollowup::PRODUCTION_STATUSES as $status)
                                <option value="{{ $status }}" {{ old('knitting_status', $factoryFollowup->knitting_status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('knitting_status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="dyeing_status" class="font-semibold">Dyeing<span class="text-danger">*</span></label>
                        <select name="dyeing_status" id="dyeing_status" class="form-select" required>
                            @foreach (\App\Models\FactoryFollowup::PRODUCTION_STATUSES as $status)
                                <option value="{{ $status }}" {{ old('dyeing_status', $factoryFollowup->dyeing_status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('dyeing_status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="cutting_status" class="font-semibold">Cutting<span class="text-danger">*</span></label>
                        <select name="cutting_status" id="cutting_status" class="form-select" required>
                            @foreach (\App\Models\FactoryFollowup::PRODUCTION_STATUSES as $status)
                                <option value="{{ $status }}" {{ old('cutting_status', $factoryFollowup->cutting_status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('cutting_status') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Followup Commercials -->
            <div>
                <h3 class="text-md font-bold uppercase text-primary mb-3">3. FOB & Sub Contracting Commercials</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="fob_price" class="font-semibold">FOB Price</label>
                        <input type="number" step="0.01" id="fob_price" name="fob_price"
                            value="{{ old('fob_price', $factoryFollowup->fob_price) }}" class="form-input" />
                        @error('fob_price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="sub_price" class="font-semibold">Sub Price</label>
                        <input type="number" step="0.01" id="sub_price" name="sub_price"
                            value="{{ old('sub_price', $factoryFollowup->sub_price) }}" class="form-input" />
                        @error('sub_price') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.factory-followups.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Follow-up Changes</button>
            </div>
        </form>
    </div>
@endsection
