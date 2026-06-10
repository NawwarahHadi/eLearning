@extends('layouts.app')
@section('title', 'Quiz Attempts')
@section('page-header', 'Quiz Attempts')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm mb-5">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold flex-column align-items-start">
                <span>{{ $quiz->title }}</span>
                <span class="text-muted fs-7 fw-semibold">Topic: {{ $quiz->learningMaterial->topic ?? '—' }}</span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('quiz.review.quizzes', $quiz->class_id) }}" class="btn btn-sm btn-light">← Back to quizzes</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                    <tr class="fw-bold text-muted">
                        <th>Student</th>
                        <th>Score</th>
                        <th>Correct</th>
                        <th>Attempted</th>
                        <th class="text-end">Answers</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                    <tr>
                        <td class="fw-bold">{{ $attempt->student->name ?? 'Unknown' }}</td>
                        <td>
                            <span class="badge {{ $attempt->score >= 50 ? 'badge-light-success' : 'badge-light-danger' }}">
                                {{ $attempt->score }}%
                            </span>
                        </td>
                        <td>{{ $attempt->correct_answers }} / {{ $attempt->total_questions }}</td>
                        <td class="text-muted fs-7">{{ $attempt->updated_at->format('d M Y, h:i A') }}</td>
                        <td class="text-end">
                            <a href="{{ route('quiz.review.answers', [$quiz->id, $attempt->student_id]) }}"
                               class="btn btn-sm btn-light-primary">View Answers</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-muted">No students have attempted this quiz yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
