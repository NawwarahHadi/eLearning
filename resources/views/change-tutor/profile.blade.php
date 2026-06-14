@extends('layouts.app')

@section('title', 'Tutor Profile')
@section('page-header', 'Tutor Profile')

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
                            <div class="symbol-label fs-1 fw-bold bg-success text-white">
                                {{ substr($tutor->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h3 class="fw-bolder">{{ $tutor->name }}</h3>
                    <p class="text-muted">{{ $tutor->email }}</p>
                    <span class="badge badge-light-success mb-5">Verified Professional Educator</span>

                    <hr>

                    <div class="text-start">
                        <h5 class="small fw-bold text-uppercase text-success mb-3">Teaching Style:</h5>
                        <p class="small text-dark leading-relaxed">
                            {{ $tutor->tutorProfile->tutor_style_description ?? 'Dedicated educator committed to student growth and academic excellence.' }}
                        </p>

                        <div class="mt-5">
                            <h5 class="small fw-bold text-uppercase text-success mb-1">Experience:</h5>
                            <p class="small text-dark">{{ $tutor->tutorProfile->experience ?? 0 }} year(s)</p>
                        </div>

                        <div class="mt-5">
                            <h5 class="small fw-bold text-uppercase text-success mb-1">University:</h5>
                            <p class="small text-dark">{{ $tutor->tutorProfile->university ?? '-' }}</p>
                        </div>

                        <div class="mt-5">
                            <h5 class="small fw-bold text-uppercase text-success mb-1">Course:</h5>
                            <p class="small text-dark">{{ $tutor->tutorProfile->course ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: CLASS DETAILS + CHANGE REQUEST --}}
                <div class="col-md-8 p-10">

                    {{-- Notice this is a CHANGE request --}}
                    <div class="alert alert-light-warning border border-warning d-flex align-items-center mb-6">
                        <i class="ki-duotone ki-arrows-circle fs-2x text-warning me-3">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <div class="text-gray-700 fs-7">
                            You are requesting to <strong>change tutor</strong> for this subject.
                            Your request will be sent to admin for approval.
                        </div>
                    </div>

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
                        <h5 class="text-success mb-3"><i class="fas fa-bullseye me-2"></i> Learning Objectives</h5>
                        <div class="p-6 bg-light-success rounded border border-dashed border-success">
                            <div class="mb-0 text-gray-700">
                                @if($class->learning_objective)
                                    {!! nl2br(e($class->learning_objective)) !!}
                                @else
                                    Comprehensive syllabus coverage with proven teaching methods.
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Slot Selection --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-dark fw-bold mb-0">
                            <i class="fas fa-clock me-2 text-warning"></i> Available Learning Slots
                        </h5>
                        <span class="badge badge-light-success fs-7" id="slot-counter">0 slot(s) selected</span>
                    </div>
                    <p class="text-muted small mb-4">Select up to 3 slots to request this tutor.</p>

                    <form action="{{ route('change-tutor.requestChange') }}" method="POST" id="changeTutorForm">
                        @csrf
                        <input type="hidden" name="old_class_id" value="{{ $oldClassId }}">
                        <input type="hidden" name="new_class_id" value="{{ $class->id }}">
                        <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

                        <div class="row g-3 mb-8">
                            @forelse($class->schedules->where('is_temporary', 0) as $slot)
                            <div class="col-sm-6">
                                <input type="checkbox"
                                       class="btn-check slot-checkbox"
                                       name="schedule_id[]"
                                       id="slot_{{ $slot->id }}"
                                       value="{{ $slot->id }}">

                                <label class="btn btn-outline btn-outline-dashed btn-active-light-success d-flex flex-column text-start p-5 h-100"
                                       for="slot_{{ $slot->id }}">
                                    <span class="d-flex align-items-center mb-2">
                                        <span class="symbol symbol-20px me-2">
                                            <i class="fas fa-calendar-day text-success"></i>
                                        </span>
                                        <span class="fs-6 fw-bold text-gray-800">{{ $slot->day }}</span>
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
                                    No available slots at the moment.
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <div class="d-flex gap-3">
                            <a href="{{ route('change-tutor.index', $oldClassId) }}" class="btn btn-light btn-lg w-100 fw-bold">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn btn-success btn-lg w-100 shadow-sm fw-bold"
                                    onclick="return validateSlots()">
                                <i class="ki-duotone ki-arrows-circle fs-2x me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Request Change Tutor
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

    document.querySelectorAll('.slot-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const checked = document.querySelectorAll('.slot-checkbox:checked');

            if (checked.length > maxSlots) {
                this.checked = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximum Slots Reached',
                    text: `You can only select up to ${maxSlots} slots.`,
                    confirmButtonColor: '#50CD89',
                });
            }

            const current = document.querySelectorAll('.slot-checkbox:checked').length;
            counter.textContent = `${current} slot(s) selected`;
            counter.className = current > 0
                ? 'badge badge-light-success fs-7'
                : 'badge badge-light-primary fs-7';
        });
    });

    function validateSlots() {
        const checked = document.querySelectorAll('.slot-checkbox:checked');
        if (checked.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No Slot Selected',
                text: 'Please select at least one slot before requesting.',
                confirmButtonColor: '#50CD89',
            });
            return false;
        }
        return Swal.fire({
            title: 'Request Tutor Change?',
            text: 'Your request will be sent to admin for approval.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, request it',
            confirmButtonColor: '#50CD89',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('changeTutorForm').submit();
            }
        }), false;
    }
</script>
@endsection
