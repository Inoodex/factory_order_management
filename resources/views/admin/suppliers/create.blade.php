@extends('admin.layouts.master')

@section('title', 'Add Supplier')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Add Supplier / Factory</h2>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to List
        </a>
    </div>

    <div class="panel mt-6">
        <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="font-semibold">Supplier / Factory Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-input" />
                @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="location" class="font-semibold">Location / Factory Zone</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" class="form-input" />
                    @error('location') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="contact_person" class="font-semibold">Contact Person</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" class="form-input" />
                    @error('contact_person') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="phone" class="font-semibold">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-input" />
                    @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="font-semibold">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Supplier</button>
            </div>
        </form>
    </div>
@endsection
