@extends('layouts.app')

@section('title', 'Student Evaluation')
@section('page-header', 'Student Evaluation Form')

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Student header --}}
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <div class="me-7 mb-4">
                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                        <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" />
                    </div>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 fs-2 fw-bold me-1">{{ $student->name }}</span>
                                <span class="badge badge-light-success fw-bold ms-2 fs-8 py-1 px-3">Active Student</span>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-500 me-5 mb-2">
                                    <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                    {{ $student->email }}
                                </span>
                                <span class="d-flex align-items-center text-gray-500 mb-2">
                                    <i class="ki-duotone ki-phone fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                    {{ $student->phone ?? 'No Phone' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap flex-stack">
                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <div class="d-flex flex-wrap">
                                @if($evaluation)
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="fs-2 fw-bold">{{ $evaluation->overall_score }} / 5</div>
                                    <div class="fw-semibold fs-6 text-gray-500">Current Overall</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Evaluation form --}}
    <form method="POST" action="{{ route('evaluation.store', [$class->id, $student->id]) }}" id="kt_evaluation_form">
        @csrf
        <div class="card shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900 fs-3">Academic Performance Evaluation</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Submit milestone feedback for {{ $class->subject->name }}.</span>
                </h3>
            </div>

            <div class="card-body border-top p-9">

                {{-- Subject class (fixed) --}}
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Subject Class</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control form-control-solid form-control-lg"
                               value="{{ $class->subject->name }} ({{ $class->category_code }})" readonly>
                    </div>
                </div>

                {{-- Progress level --}}
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Academic Progress Level</label>
                    <div class="col-lg-8">
                        @php $pl = old('progress_level', $evaluation->progress_level ?? 'Good'); @endphp
                        <select name="progress_level" class="form-select form-select-solid form-select-lg fw-semibold" data-control="select2" data-hide-search="true">
                            <option value="Excellent" {{ $pl=='Excellent'?'selected':'' }}>Excellent - Strong mastery of concepts</option>
                            <option value="Good" {{ $pl=='Good'?'selected':'' }}>Good - Steady progress</option>
                            <option value="Satisfactory" {{ $pl=='Satisfactory'?'selected':'' }}>Satisfactory - Needs minor review</option>
                            <option value="Needs Improvement" {{ $pl=='Needs Improvement'?'selected':'' }}>Needs Improvement - Requires extra help</option>
                        </select>
                    </div>
                </div>

                {{-- Scores 1-5 --}}
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Performance Scores <span class="text-muted fw-normal">(1 = Poor, 5 = Excellent)</span></label>
                    <div class="col-lg-8">
                        @php
                            $scores = [
                                'understanding_score' => 'Understanding of Material',
                                'participation_score' => 'Participation & Engagement',
                                'homework_score'      => 'Homework Completion',
                            ];
                        @endphp

                        @foreach($scores as $field => $label)
                            <div class="mb-5">
                                <label class="form-label fs-7 text-muted fw-bold d-block mb-2">{{ $label }}</label>
                                <div class="d-flex gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="rating-option">
                                            <input type="radio" name="{{ $field }}" value="{{ $i }}"
                                                {{ old($field, $evaluation->$field ?? '') == $i ? 'checked' : '' }}
                                                required class="d-none rating-input">
                                            <span class="rating-circle">{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                @error($field)<div class="text-danger fs-8 mt-1">{{ $message }}</div>@enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Comments --}}
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Tutor Commentary & Recommendations</label>
                    <div class="col-lg-8">
                        <textarea name="comments" class="form-control form-control-solid" rows="4"
                                  placeholder="Provide analysis on learning blockages, exam prep, or modifications...">{{ old('comments', $evaluation->comments ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="{{ route('class.show', $class->id) }}" class="btn btn-light btn-active-light-primary me-2">Cancel</a>
                <button type="submit" id="kt_evaluation_submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check-square me-2 fs-3"><span class="path1"></span><span class="path2"></span></i>
                    <span class="indicator-label">{{ $evaluation ? 'Update Evaluation' : 'Submit Evaluation' }}</span>
                    <span class="indicator-progress">Saving... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .rating-circle {
        display: inline-flex; align-items: center; justify-content: center;
        width: 46px; height: 46px; border-radius: 50%;
        border: 2px solid #e1e3ea; font-weight: 700; color: #5e6278;
        cursor: pointer; transition: all .15s;
    }
    .rating-option:hover .rating-circle { border-color: #3b82f6; color: #3b82f6; }
    .rating-input:checked + .rating-circle {
        background: #3b82f6; border-color: #3b82f6; color: #fff;
    }
</style>
@endsection

@section('js_after')
<script>
    $(document).ready(function () {
        $('[data-control="select2"]').select2();
    });
</script>
@endsection
