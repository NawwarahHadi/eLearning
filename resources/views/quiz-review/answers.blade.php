@extends('layouts.app')
@section('title', 'Student Answers')
@section('page-header', 'Student Answers')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm mb-5">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold flex-column align-items-start">
                <span>{{ $student->name }} — {{ $quiz->title }}</span>
                <span class="text-muted fs-7 fw-semibold">
                    Score: {{ $attempt->score }}% ({{ $attempt->correct_answers }}/{{ $attempt->total_questions }} correct)
                </span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('quiz.review.attempts', $quiz->id) }}" class="btn btn-sm btn-light">← Back</a>
            </div>
        </div>
        <div class="card-body">
            @foreach($questions as $index => $q)
                @php
                    $studentChoice = $selected[$q->id] ?? null;   // 'A'/'B'/'C'/'D' or null if unanswered
                    $isCorrect = $studentChoice === $q->correct_option;
                    $options = ['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d];
                @endphp

                <div class="border border-gray-300 border-dashed rounded p-4 mb-4">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="fw-bold text-gray-900 mb-0">Q{{ $index + 1 }}. {{ $q->question_text }}</h5>
                        @if($studentChoice === null)
                            <span class="badge badge-light">Not answered</span>
                        @elseif($isCorrect)
                            <span class="badge badge-light-success">Correct</span>
                        @else
                            <span class="badge badge-light-danger">Wrong</span>
                        @endif
                    </div>

                    @foreach($options as $letter => $text)
                        @php
                            $isStudentPick = $studentChoice === $letter;
                            $isAnswer      = $q->correct_option === $letter;
                            $class = '';
                            if ($isAnswer)            $class = 'bg-light-success text-success fw-bold'; // correct answer
                            elseif ($isStudentPick)   $class = 'bg-light-danger text-danger fw-bold';   // student's wrong pick
                        @endphp
                        <div class="d-flex align-items-center rounded px-3 py-2 mb-2 {{ $class }}">
                            <span class="fw-bold me-2">{{ $letter }}.</span>
                            <span>{{ $text }}</span>
                            @if($isStudentPick)
                                <span class="ms-auto fs-8 fst-italic">← student's answer</span>
                            @endif
                            @if($isAnswer && !$isStudentPick)
                                <span class="ms-auto fs-8 fst-italic">✓ correct answer</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
