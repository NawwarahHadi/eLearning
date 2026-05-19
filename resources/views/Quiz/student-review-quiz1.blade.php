@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght=700;900&display=swap" rel="stylesheet">

<style>
    .review-container {
        background-color: #1c1936;
        min-height: 100vh;
        padding: 40px 20px;
        font-family: 'Nunito', sans-serif;
        color: #fff;
    }
    .review-card {
        background: #fffdf6;
        border: 4px solid #ffaa00;
        border-radius: 24px;
        color: #1e293b;
        max-width: 700px;
        margin: 30px auto;
        padding: 24px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }
    .question-title {
        font-family: 'Fredoka One', cursive;
        font-size: 1.3rem;
        color: #1e293b;
        margin-bottom: 20px;
    }
    .option-box {
        padding: 14px 18px;
        border-radius: 14px;
        border: 2px solid #cbd5e1;
        margin-bottom: 12px;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
    }

    /* Correct Option Style (Green) */
    .option-correct {
        background-color: #d1fae5 !important;
        border-color: #10b981 !important;
        color: #065f46 !important;
    }

    /* Wrong Option Style (Red) */
    .option-wrong {
        background-color: #fee2e2 !important;
        border-color: #ef4444 !important;
        color: #991b1b !important;
    }

    .badge-status {
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 8px;
        color: white;
        font-weight: 900;
    }
    .bg-success-badge { background-color: #10b981; }
    .bg-danger-badge { background-color: #ef4444; }
</style>

<div class="review-container">
    <div class="text-center mb-5">
        <h1 style="font-family:'Fredoka One'; font-size: 2.8rem; text-shadow: 0 4px 0 #e07a16;">Review Mode</h1>
        <p class="text-muted fs-5">Analyzing Attempt Score: <b class="text-warning">{{ $latestAttempt->score }}%</b></p>
    </div>

    @foreach($quiz->questions as $index => $question)
        @php
            // 1. Force the database choice to lower case string formats to bypass casing glitches
            $studentChoice = isset($selectedAnswers[$question->id]) ? strtolower($selectedAnswers[$question->id]) : null;
            $correctOption = strtolower($question->correct_option);
        @endphp

        <div class="review-card">
            <div class="question-title">Q{{ $index + 1 }}. {{ $question->question_text }}</div>

            @php
                $options = [
                    'a' => $question->option_a,
                    'b' => $question->option_b,
                    'c' => $question->option_c,
                    'd' => $question->option_d
                ];
            @endphp

            @foreach($options as $key => $text)
                @php
                    $boxClass = '';
                    $badgeText = null;
                    $badgeClass = '';

                    if ($key === $correctOption) {
                        // Correct option layout engine block
                        $boxClass = 'option-correct';
                        if ($studentChoice === $key) {
                            $badgeText = '✨ CORRECT CHOICE!';
                            $badgeClass = 'bg-success-badge';
                        } else {
                            $badgeText = '✅ CORRECT ANSWER';
                            $badgeClass = 'bg-success-badge';
                        }
                    } elseif ($studentChoice === $key) {
                        // Wrong choice matching layout block
                        $boxClass = 'option-wrong';
                        $badgeText = '❌ YOUR CHOICE';
                        $badgeClass = 'bg-danger-badge';
                    }
                @endphp

                <div class="option-box {{ $boxClass }}">
                    <span>{{ strtoupper($key) }}. {{ $text }}</span>
                    @if($badgeText)
                        <span class="badge-status {{ $badgeClass }}">
                            {{ $badgeText }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="text-center mt-5">
        <a href="{{ route('quiz.map', $quiz->class_id) }}" class="btn btn-lg btn-warning fw-bold text-white px-5 shadow-lg" style="border-radius:20px; font-family:'Fredoka One'; font-size: 1.3rem;">
            RETURN TO MAP
        </a>
    </div>
</div>
@endsection
