@extends('admin.layouts.master')

@section('title', 'Document Preview')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold uppercase">Document Preview</h2>
                <p class="text-sm text-white-dark break-all">{{ $name }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back
            </a>
        </div>

        <div class="panel p-0">
            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                <div class="rounded-lg bg-black/10 p-4">
                    <img src="{{ $fileUrl }}" alt="{{ $name }}" class="h-[90vh] w-full object-contain">
                </div>
            @elseif ($extension === 'pdf')
                <div class="h-[calc(100vh-80px)]">
                    <iframe
                        src="{{ $fileUrl }}"
                        class="w-full h-full border-0"
                        title="{{ $name }}"
                    ></iframe>
                </div>
                <div class="p-4">
                    <div class="text-sm text-white-dark">If the PDF does not render above, open or download it below.</div>
                    <div class="mt-4 flex items-center justify-center gap-3">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary">Open PDF</a>
                        <a href="{{ $downloadUrl }}" class="btn btn-primary">Download</a>
                    </div>
                </div>
            @else
                <div class="rounded-lg bg-black/10 p-6 text-center">
                    <p class="text-base font-semibold">Inline preview is not available for this file type.</p>
                    <p class="mt-2 text-sm text-white-dark">Supported inline preview: PDF, JPG, JPEG, PNG.</p>
                    <div class="mt-4 flex items-center justify-center gap-3">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary">Open File</a>
                        <a href="{{ $downloadUrl }}" class="btn btn-primary">Download</a>
                    </div>
                </div>
            @endif

            <!-- <div class="mt-4 flex justify-end">
                <a href="{{ $downloadUrl }}" class="btn btn-outline-primary">
                    Download
                </a>
            </div> -->
        </div>
    </div>
@endsection
