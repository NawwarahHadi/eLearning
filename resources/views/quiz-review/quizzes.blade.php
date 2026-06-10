@extends('layouts.app')
@section('title', 'Quiz Review')
@section('page-header', 'Quiz Review')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm mb-5">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold">{{ $class->subject->name }} </h3>
        </div>
        <div class="card-body">
            @forelse($quizzes as $quiz)
            <div class="border border-gray-300 border-dashed rounded p-4 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold text-gray-900 mb-1">{{ $quiz->title }}</h4>
                    <div class="text-muted fs-7">
                        Topic: {{ $quiz->learningMaterial->topic ?? '—' }} &middot;
                        {{ $quiz->attempts_count }} student(s) attempted
                        @if($quiz->avg_score !== null)
                            &middot; Avg score: {{ round($quiz->avg_score) }}%
                        @endif
                    </div>
                </div>
                <a href="{{ route('quiz.review.attempts', $quiz->id) }}" class="btn btn-sm btn-light-primary">
                    View Attempts
                </a>
            </div>
            @empty
                <div class="text-muted">No quizzes created for this class yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
