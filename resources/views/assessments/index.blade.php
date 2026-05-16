@extends('layouts.app')

@section('title', 'Course Assessment')

@section('page-header', 'Course Assessment')

{{-- Update the breadcrumb if you have the English version defined --}}
{{-- @section('breadcrumbs', Breadcrumbs::render('announcement')) --}}

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary py-5">
            <h3 class="card-title text-white flex-column align-items-start">
                <span class="fw-bold">Teaching & Course Assessment</span>
                <small class="text-white opacity-75 fs-7 mt-1">Class: {{ $class->class_name }} | Tutor: {{ $tutor->name }}</small>
            </h3>
        </div>

        <form action="{{ route('course_assesment.store') }}" method="POST">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            <input type="hidden" name="tutor_id" value="{{ $tutor->id }}">

            <div class="card-body">
                <div class="mb-10">
                    <h4 class="text-dark fw-bold mb-5">Section 1: Learning Outcomes (PLO)</h4>
                    <div class="separator separator-dashed mb-5"></div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">1. To what extent have the learning outcomes (PLO1) been achieved?</label>
                        @include('assessments.partials-rating', ['name' => 'plo1_score'])
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">2. To what extent has the course developed your thinking skills (PLO2)?</label>
                        @include('assessments.partials-rating', ['name' => 'plo2_score'])
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="text-dark fw-bold mb-5">Section 2: Course Content</h4>
                    <div class="separator separator-dashed mb-5"></div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">1. The content of this course is relevant to my field of study.</label>
                        @include('assessments.partials-rating', ['name' => 'content_relevance'])
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">2. The references and materials used are up-to-date.</label>
                        @include('assessments.partials-rating', ['name' => 'content_updated'])
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="text-dark fw-bold mb-5">Section 3: Delivery & Facilities</h4>
                    <div class="separator separator-dashed mb-5"></div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">1. The use of e-learning platforms (e.g., this portal) is effective.</label>
                        @include('assessments.partials-rating', ['name' => 'delivery_elearn'])
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">2. The teaching facilities provided are satisfactory.</label>
                        @include('assessments.partials-rating', ['name' => 'delivery_facilities'])
                    </div>
                </div>
                <div class="mb-10">
                    <h4 class="text-dark fw-bold mb-5">Section 4: Implementation</h4>
                    <div class="separator separator-dashed mb-5"></div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">1. Continuous assessment is implemented effectively.</label>
                        @include('assessments.partials-rating', ['name' => 'assess_continuous'])
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold">2. The assessment load is appropriate for the credits.</label>
                        @include('assessments.partials-rating', ['name' => 'assess_load'])
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="text-dark fw-bold mb-5">Section 4: Overall Comments</h4>
                    <div class="separator separator-dashed mb-5"></div>
                    <textarea name="overall_comments" class="form-control form-control-solid" rows="4" placeholder="Share your suggestions for improvement..."></textarea>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-success">Submit Assessment</button>
            </div>
        </form>
    </div>
</div>
@endsection
