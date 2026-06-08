@extends('layouts.app')

@section('title', 'My Evaluations')
@section('page-header', 'My Evaluations')

@section('content')
<div id="kt_content_container" class="container-xxl">

    @forelse($evaluations as $eval)
    <div class="card shadow-sm mb-5">
        <div class="card-header border-0 pt-6 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold text-gray-900 mb-1">{{ $eval->createClass->subject->name ?? 'Class' }}</h3>
                <span class="text-muted fs-7">
                    Evaluated by {{ $eval->tutor->name ?? 'Tutor' }} &middot;
                    {{ $eval->updated_at->format('d M Y') }}
                </span>
            </div>
            <div class="text-end">
                <div class="fs-1 fw-bold text-primary">{{ $eval->overall_score }}<span class="fs-5 text-muted">/5</span></div>
                <span class="badge badge-light-{{
                    $eval->progress_level == 'Excellent' ? 'success' :
                    ($eval->progress_level == 'Good' ? 'primary' :
                    ($eval->progress_level == 'Satisfactory' ? 'warning' : 'danger'))
                }} fw-bold">{{ $eval->progress_level }}</span>
            </div>
        </div>

        <div class="card-body pt-4">
            {{-- Score breakdown --}}
            <div class="row g-4 mb-5">
                @php
                    $items = [
                        'Understanding'   => $eval->understanding_score,
                        'Participation'   => $eval->participation_score,
                        'Homework'        => $eval->homework_score,
                    ];
                @endphp
                @foreach($items as $label => $score)
                    <div class="col-md-4">
                        <div class="border border-gray-300 border-dashed rounded p-4">
                            <div class="fw-semibold fs-7 text-gray-500 mb-2">{{ $label }}</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-2 fw-bold text-gray-900">{{ $score }}</span>
                                <span class="text-muted fs-7">/ 5</span>
                            </div>
                            {{-- visual bar --}}
                            <div class="progress h-6px mt-2 bg-light">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ ($score / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tutor comment --}}
            @if($eval->comments)
            <div class="bg-light-primary rounded p-4">
                <div class="fw-bold text-gray-800 mb-1">
                    <i class="ki-duotone ki-message-text-2 fs-4 text-primary me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Tutor's Feedback
                </div>
                <p class="text-gray-700 mb-0 fs-6">{{ $eval->comments }}</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="card shadow-sm">
        <div class="card-body text-center py-15">
            <i class="ki-duotone ki-file-deleted fs-5x text-muted mb-4"><span class="path1"></span><span class="path2"></span></i>
            <h3 class="text-gray-700">No evaluations yet</h3>
            <p class="text-muted">Your tutors haven't submitted any evaluations for you yet.</p>
        </div>
    </div>
    @endforelse

</div>
@endsection
