@extends('layouts.app')

@section('title', 'Profile Tutor')

@section('page-header', 'Profile Tutor')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="row g-0">

                {{-- LEFT COLUMN: TUTOR BIO --}}
                <div class="col-md-4 bg-light p-10 text-center border-end">
                    <div class="symbol symbol-125px symbol-circle mb-5 shadow">
                        @if($tutor->tutorProfile && $tutor->tutorProfile->profile_photo)
                            <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}" alt="image" />
                        @else
                            <div class="symbol-label fs-1 fw-bold bg-primary text-white">
                                {{ substr($tutor->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h3 class="fw-bolder">{{ $tutor->name }}</h3>
                    <p class="text-muted">{{ $tutor->email }}</p>
                    <span class="badge badge-light-success mb-5">Verified Professional Educator</span>

                    <hr>

                    <div class="text-start">
                        <h5 class="small fw-bold text-uppercase text-primary mb-3">Teaching Style:</h5>
                        <p class="small text-dark leading-relaxed">
                            {{ $tutor->tutorProfile->tutor_style_description ?? 'Dedicated educator committed to student growth and academic excellence.' }}
                        </p>

                        <div class="mt-5">
                            <h5 class="small fw-bold text-uppercase text-primary mb-1">Experience:</h5>
                            <p class="small text-dark">{{ $tutor->tutorProfile->experience ?? '5+' }}</p>
                        </div>

                        <div class="mt-5">
                            <h5 class="small fw-bold text-uppercase text-primary mb-1">Work Experience:</h5>
                            <p class="small text-dark">
                                {{ is_array($tutor->tutorProfile->experience_titles)
                                    ? implode(', ', $tutor->tutorProfile->experience_titles)
                                    : ($tutor->tutorProfile->experience_titles ?? '-') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: CLASS DETAILS + FORM --}}
                <div class="col-md-8 p-10">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $class->subject->name }}</h2>
                            <span class="badge badge-info">{{ $class->category_code }}</span>
                        </div>
                        <div class="text-end">
                            <span class="text-muted fs-8 d-block">Fee</span>
                            <span class="text-muted fs-8 d-block">RM 50 per Hour</span>
                        </div>
                    </div>

                    {{-- Learning Objectives --}}
                    <div class="mb-8">
                        <h5 class="text-primary mb-3"><i class="fas fa-bullseye me-2"></i> Learning Objectives</h5>
                        <div class="p-6 bg-light-primary rounded border border-dashed border-primary">
                            <ul class="mb-0 text-gray-700">
                                @if($class->learning_objective)
                                    {!! nl2br(e($class->learning_objective)) !!}
                                @else
                                    <li class="mb-2"><strong>Comprehensive Syllabus Coverage:</strong> Mastering all core topics as per the latest KSSM/SPM standards.</li>
                                    <li class="mb-2"><strong>Answering Techniques:</strong> Learning how to score maximum marks by understanding marking schemes.</li>
                                    <li><strong>Critical Thinking:</strong> Solving High-Order Thinking Skills (HOTS) questions with ease.</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- Slot Selection --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-dark fw-bold mb-0">
                            <i class="fas fa-clock me-2 text-warning"></i> Available Learning Slots
                        </h5>
                        <span class="badge badge-light-primary fs-7" id="slot-counter">0 slot(s) selected</span>
                    </div>
                    <p class="text-muted small mb-4">You may select up to 3 session slots to proceed with your enrollment.</p>

                    <form action="{{ route('enrollment.store') }}" method="POST" id="enrollmentForm">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

                        <div class="row g-3 mb-8">
                            @forelse($class->schedules as $slot)
                            <div class="col-sm-6">
                                <input type="checkbox"
                                       class="btn-check slot-checkbox"
                                       name="schedule_id[]"
                                       id="slot_{{ $slot->id }}"
                                       value="{{ $slot->id }}">

                                <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-column text-start p-5 h-100"
                                       for="slot_{{ $slot->id }}">
                                    <span class="d-flex align-items-center mb-2">
                                        <span class="symbol symbol-20px me-2">
                                            <i class="fas fa-calendar-day text-primary"></i>
                                        </span>
                                        <span class="fs-6 fw-bold text-gray-800">
                                            {{ $slot->day }}
                                        </span>
                                    </span>
                                    <span class="fs-7 text-muted">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                        –
                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                    </span>
                                </label>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-calendar-times me-2"></i>
                                    No available slots at the moment. Please check back later.
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <div class="d-flex gap-3">
                            <a href="javascript:history.back()" class="btn btn-light btn-lg w-100 fw-bold">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn btn-primary btn-lg w-100 shadow-sm fw-bold"
                                    onclick="return validateSlots()">
                                <i class="fas fa-check-circle me-2"></i> Confirm Enrollment
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_after')
<script>
    const maxSlots = 3;
    const counter  = document.getElementById('slot-counter');

    // Update counter + enforce max
    document.querySelectorAll('.slot-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const checked = document.querySelectorAll('.slot-checkbox:checked');

            if (checked.length > maxSlots) {
                this.checked = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Slots Reached',
                    text: `You can only select up to ${maxSlots} slots.`,
                    confirmButtonColor: '#3085d6',
                });
            }

            // Update counter label
            const current = document.querySelectorAll('.slot-checkbox:checked').length;
            counter.textContent = `${current} slot(s) selected`;
            counter.className = current > 0
                ? 'badge badge-light-success fs-7'
                : 'badge badge-light-primary fs-7';
        });
    });

    // Validate at least 1 selected before submit
    function validateSlots() {
        const checked = document.querySelectorAll('.slot-checkbox:checked');
        if (checked.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No Slot Selected',
                text: 'Please select at least one session slot before confirming.',
                confirmButtonColor: '#3085d6',
            });
            return false;
        }
        return true;
    }
</script>
@endsection
