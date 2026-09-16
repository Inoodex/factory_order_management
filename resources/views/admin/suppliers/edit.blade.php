@extends('admin.layouts.master')

@section('title', 'Edit Supplier')

@section('content')
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold uppercase">Edit Supplier / Factory</h2>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>

    <div class="panel mt-6 max-w-3xl">
        <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="font-semibold">Supplier / Factory Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $supplier->name) }}" required
                    class="form-input" />
                @error('name') <span class="text-danger text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="location" class="font-semibold">Location / Factory Zone</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $supplier->location) }}"
                        class="form-input" />
                    @error('location') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="contact_person" class="font-semibold">Contact Person</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                        class="form-input" />
                    @error('contact_person') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="phone" class="font-semibold">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}"
                        class="form-input" />
                    @error('phone') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="font-semibold">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $supplier->email) }}"
                        class="form-input" />
                    @error('email') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-danger">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Supplier</button>
            </div>
        </form>
    </div>
@endsection
