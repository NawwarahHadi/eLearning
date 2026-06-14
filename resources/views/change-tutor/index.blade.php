@extends('layouts.app')

@section('title', 'Change Tutor')
@section('page-header', 'Change Tutor')

@section('content')
<div id="kt_content_container" class="container-xxl">

    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-cross fs-2x me-3 text-danger">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    {{-- Current Class Info --}}
    <div class="card card-flush mb-6">
        <div class="card-body p-6">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-4">
                        <div class="symbol-label bg-light-primary text-primary fw-bold fs-3">
                            {{ substr($currentClass->subject->name, 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <span class="text-muted fs-7 d-block">Currently Learning</span>
                        <span class="text-gray-800 fw-bold fs-4">{{ $currentClass->subject->name }}</span>
                        <span class="text-muted fs-7 d-block">
                            with <strong>{{ $currentClass->tutor->name }}</strong> ({{ $currentClass->category_code }})
                        </span>
                    </div>
                </div>
                <span class="badge badge-light-primary fs-7">Current Tutor</span>
            </div>
        </div>
    </div>

    {{-- Section Title --}}
    <div class="mb-6">
        <h3 class="fw-bold text-gray-800">Available Tutors for {{ $currentClass->subject->name }}</h3>
        <p class="text-muted fs-7">Click a tutor card to view their full profile and request a change.</p>
    </div>

    {{-- Available Tutors --}}
    <div class="row g-6">
        @forelse($availableClasses as $class)
        <div class="col-md-6 col-xl-4">
            {{-- Whole card is clickable → goes to tutor profile --}}
            <a href="{{ route('change-tutor.profile', ['class_id' => $class->id, 'old_class_id' => $currentClass->id]) }}"
               class="card card-flush h-100 shadow-sm text-decoration-none card-hover">
                <div class="card-body p-6 d-flex flex-column">

                    {{-- Tutor Header --}}
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-60px me-4">
                            @if($class->tutor->tutorProfile?->profile_photo)
                                <img src="{{ asset('storage/' . $class->tutor->tutorProfile->profile_photo) }}"
                                     class="rounded-circle" style="width:60px;height:60px;object-fit:cover;">
                            @else
                                <div class="symbol-label bg-light-success text-success fw-bold fs-2">
                                    {{ substr($class->tutor->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <span class="text-gray-800 fw-bold fs-5 d-block">{{ $class->tutor->name }}</span>
                            <span class="text-muted fs-7">{{ $class->category_code }} • {{ $class->language_code }}</span>
                        </div>
                    </div>

                    {{-- Tutor Details --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ki-duotone ki-medal-star fs-4 text-warning me-2">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span>
                            </i>
                            <span class="text-gray-600 fs-7">
                                {{ $class->tutor->tutorProfile?->experience ?? 0 }} year(s) experience
                            </span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="ki-duotone ki-book fs-4 text-info me-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                            <span class="text-gray-600 fs-7">
                                {{ $class->tutor->tutorProfile?->university ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ki-duotone ki-time fs-4 text-primary me-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            <span class="text-gray-600 fs-7">{{ $class->mode }} • {{ $class->hours_per_week }} hrs/week</span>
                        </div>
                    </div>

                    {{-- View Profile hint --}}
                    <div class="mt-auto">
                        <div class="separator mb-3"></div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-primary fw-bold fs-7">View Profile</span>
                            <i class="ki-duotone ki-arrow-right fs-2x text-primary">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </div>
                    </div>

                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="card card-flush">
                <div class="card-body text-center py-10">
                    <i class="ki-duotone ki-information fs-3x text-muted mb-3 d-block">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <span class="text-muted fs-5">No other tutors available for this subject.</span>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Back Button --}}
    <div class="mt-6">
        <a href="{{ route('student.class.index') }}" class="btn btn-light fw-bold">
            <i class="ki-duotone ki-arrow-left fs-2x me-1">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Back to My Classes
        </a>
    </div>

</div>

<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        border-color: var(--bs-primary) !important;
    }
</style>
@endsection
