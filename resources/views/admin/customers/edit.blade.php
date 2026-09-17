@extends('admin.layouts.master')

@section('title', 'Edit Customer')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Customer / Buyer</h2>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="font-semibold">Customer / Buyer Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                    class="form-input" />
                @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="brand" class="font-semibold">Brand</label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand', $customer->brand) }}"
                        class="form-input" />
                    @error('brand') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="session" class="font-semibold">Season (Session)</label>
                    <input type="text" id="session" name="session" value="{{ old('session', $customer->session) }}"
                        class="form-input" />
                    <span class="text-xs text-gray-500">Buying or production season</span>
                    @error('session') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="email" class="font-semibold">Contact Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                        class="form-input" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="phone" class="font-semibold">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                        class="form-input" />
                    @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="address" class="font-semibold">Office / Factory Address</label>
                <textarea id="address" name="address" rows="3" class="form-textarea">{{ old('address', $customer->address) }}</textarea>
                @error('address') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Customer</button>
            </div>
        </form>
    </div>
@endsection
