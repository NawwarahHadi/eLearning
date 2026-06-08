@extends('layouts.app')

@section('title', 'List of Tutors')

@section('page-header', 'List of Tutors')

@section('CSS')
<style>
    .grayscale {
        filter: grayscale(100%);
        opacity: 0.6;
    }
    .cursor-not-allowed {
        cursor: not-allowed !important;
    }
</style>
@endsection

@section('content')

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- HARDCODED DEMO DATA (Option B) — remove when wiring real data  --}}
{{-- ════════════════════════════════════════════════════════════ --}}
@php
    // The subject shown in the page header
    $class = (object)['subject' => (object)['name' => 'Physics']];

    // The AI-recommended tutor id (highlights one card)
    $recommended_tutor_id = 2;

    // Helper to build a fake enrollments collection of N "approved" rows
    $approved = function ($n) {
        return collect(range(1, max($n, 0)))->map(fn($i) => (object)['status' => 'approved']);
    };

    // 10 hardcoded tutor classes
    $classes = collect([
        (object)[
            'id' => 101, 'max_students' => 15, 'enrollments' => $approved(8),
            'tutor' => (object)[
                'id' => 1, 'name' => 'Nurul Aisyah', 'email' => 'nurul.aisyah@tutormail.com',
                'tutorProfile' => (object)['age' => 29, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 102, 'max_students' => 12, 'enrollments' => $approved(5),
            'tutor' => (object)[
                'id' => 2, 'name' => 'Daniel Tan', 'email' => 'daniel.tan@tutormail.com',
                'tutorProfile' => (object)['age' => 31, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 103, 'max_students' => 10, 'enrollments' => $approved(10), // FULL
            'tutor' => (object)[
                'id' => 3, 'name' => 'Priya Devi', 'email' => 'priya.devi@tutormail.com',
                'tutorProfile' => (object)['age' => 27, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 104, 'max_students' => 15, 'enrollments' => $approved(3),
            'tutor' => (object)[
                'id' => 4, 'name' => 'Mohd Hafiz', 'email' => 'hafiz.ismail@tutormail.com',
                'tutorProfile' => (object)['age' => 34, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 105, 'max_students' => 12, 'enrollments' => $approved(7),
            'tutor' => (object)[
                'id' => 5, 'name' => 'Sarah Lee', 'email' => 'sarah.lee@tutormail.com',
                'tutorProfile' => (object)['age' => 30, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 106, 'max_students' => 20, 'enrollments' => $approved(12),
            'tutor' => (object)[
                'id' => 6, 'name' => 'Ahmad Faizal', 'email' => 'ahmad.faizal@tutormail.com',
                'tutorProfile' => (object)['age' => 38, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 107, 'max_students' => 10, 'enrollments' => $approved(10), // FULL
            'tutor' => (object)[
                'id' => 7, 'name' => 'Lim Mei Hua', 'email' => 'lim.meihua@tutormail.com',
                'tutorProfile' => (object)['age' => 26, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 108, 'max_students' => 15, 'enrollments' => $approved(2),
            'tutor' => (object)[
                'id' => 8, 'name' => 'Siti Khadijah', 'email' => 'siti.khadijah@tutormail.com',
                'tutorProfile' => (object)['age' => 33, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 109, 'max_students' => 12, 'enrollments' => $approved(9),
            'tutor' => (object)[
                'id' => 9, 'name' => 'Rajesh Kumar', 'email' => 'rajesh.kumar@tutormail.com',
                'tutorProfile' => (object)['age' => 36, 'profile_photo' => null],
            ],
        ],
        (object)[
            'id' => 110, 'max_students' => 18, 'enrollments' => $approved(6),
            'tutor' => (object)[
                'id' => 10, 'name' => 'Nor Hidayah', 'email' => 'nor.hidayah@tutormail.com',
                'tutorProfile' => (object)['age' => 28, 'profile_photo' => null],
            ],
        ],
    ]);
@endphp

<div class="container mt-5">
    <div class="mb-10">
        <h3 class="fw-bolder text-dark">List of Tutors for {{ $class->subject->name }}</h3>
        <p class="text-muted">Choose your preferred tutor to see their profile and schedule.</p>
    </div>

    <div class="row">
        @forelse($classes as $item) {{-- Use $classes to access quota data --}}
            @php
                $tutor = $item->tutor;
                $profile = $tutor->tutorProfile;
                $isRecommended = ($tutor->id == $recommended_tutor_id);

                // logic: Check if approved students reached the max quota
                $currentEnrollments = $item->enrollments->where('status', 'approved')->count();
                $isFull = ($currentEnrollments >= $item->max_students);
            @endphp

            <div class="col-12 mb-5">
                {{-- If full, add opacity and remove the recommendation border --}}
                <div class="card shadow-sm border-0 {{ $isFull ? 'bg-light-secondary opacity-75' : ($isRecommended ? 'border border-primary bg-light-primary' : '') }}">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-65px symbol-circle me-5">
                                @if($profile && $profile->profile_photo)
                                    {{-- Apply grayscale filter to the photo if full --}}
                                    <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                        class="{{ $isFull ? 'grayscale' : '' }}"
                                        alt="{{ $tutor->name }}" />
                                @else
                                    <div class="symbol-label fs-2 fw-bold bg-secondary text-dark">
                                        {{ substr($tutor->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <h5 class="mb-0 fw-bolder {{ $isFull ? 'text-muted' : ($isRecommended ? 'text-primary' : 'text-dark') }}">
                                    {{ $tutor->name }}
                                    @if($isFull)
                                        <span class="badge badge-secondary ms-2">Quota Full</span>
                                    @elseif($isRecommended)
                                        <span class="badge badge-primary ms-2">Recommended Match</span>
                                    @endif
                                </h5>
                                <div class="fs-7 fw-bold text-muted mt-1">
                                    {{ $profile->age ?? 'N/A' }} years old • {{ $tutor->email }}
                                </div>
                                {{-- Added Quota Counter for transparency --}}
                                <div class="fs-8 text-muted fw-semibold mt-1">
                                    Current Quota: {{ $currentEnrollments }} / {{ $item->max_students }} Students
                                </div>
                            </div>
                        </div>

                        <div>
                            @if($isFull)
                                {{-- Disabled grey button --}}
                                <button class="btn btn-sm btn-secondary fw-bold px-6 cursor-not-allowed" disabled>
                                    Class Full
                                </button>
                            @else
                                <a href="{{ route('enrollment.tutor-profile', [$item->id, $tutor->id]) }}"
                                class="btn btn-sm {{ $isRecommended ? 'btn-primary' : 'btn-light-primary' }} fw-bold px-6">
                                    View Full Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-10">
                <p class="text-muted">No tutors found for this subject at the moment.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
