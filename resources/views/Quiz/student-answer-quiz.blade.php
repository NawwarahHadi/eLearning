@extends('layouts.app')

@section('content')
<div class="container-xxl">
    {{-- 1. Result Header (Only shows after submission) --}}
    @if(isset($score))
        <div class="alert alert-dismissible bg-light-primary border border-primary d-flex flex-column flex-sm-row p-5 mb-10">
            <i class="ki-duotone ki-award fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            <div class="d-flex flex-column pe-0 pe-sm-10">
                <h4 class="fw-bold text-primary">Quiz Completed!</h4>
                <span class="fs-4">You scored <strong>{{ $score }}%</strong> ({{ $correct }} / {{ $total }} Correct).</span>
            </div>
            <div class="d-flex align-items-center ms-sm-auto mt-5 mt-sm-0">
                <a href="{{ route('student.class.index') }}" class="btn btn-primary">Done & Back to Dashboard</a>
            </div>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-success py-5">
            <h3 class="card-title text-white fw-bolder">
                {{ $quiz->title }}
            </h3>
            <div class="card-toolbar">
                <span class="badge badge-light-success fw-bold fs-7">
                    Material: {{ $quiz->learningMaterial->topic ?? 'General' }}
                </span>
            </div>
        </div>

        <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
            @csrf
            <div class="card-body">
                @foreach($quiz->questions as $index => $question)
                    <div class="mb-10">
                        <div class="d-flex align-items-center mb-5">
                            <span class="badge badge-circle badge-outline badge-success me-3">{{ $index + 1 }}</span>
                            <h4 class="fw-bold text-gray-800 mb-0">{{ $question->question_text }}</h4>
                        </div>

                        <div class="row g-5">
                            @foreach(['A', 'B', 'C', 'D'] as $opt)
                                @php
                                    $optionKey = 'option_' . strtolower($opt);
                                    $isCorrectOption = ($question->correct_option === $opt);
                                    $userSelectedThis = (isset($results) && isset($results[$question->id]) && $results[$question->id]['user_answer'] === $opt);

                                    // CSS Logic
                                    $borderClass = 'btn-outline-dashed';
                                    $bgClass = '';

                                    if(isset($results)) {
                                        if($isCorrectOption) {
                                            $borderClass = 'border-success border-2';
                                            $bgClass = 'bg-light-success';
                                        } elseif($userSelectedThis && !$isCorrectOption) {
                                            $borderClass = 'border-danger border-2';
                                            $bgClass = 'bg-light-danger';
                                        }
                                    }
                                @endphp

                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="q_{{ $question->id }}" value="{{ $opt }}"
                                        id="q{{ $question->id }}_{{ $opt }}"
                                        {{ isset($results) ? 'disabled' : '' }}
                                        {{ $userSelectedThis ? 'checked' : '' }}
                                        required />

                                    <label class="btn btn-outline {{ $borderClass }} {{ $bgClass }} btn-active-light-primary p-5 d-flex align-items-center w-100" for="q{{ $question->id }}_{{ $opt }}">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-4 fw-bold text-gray-800">{{ $opt }}. {{ $question->$optionKey }}</span>

                                            @if(isset($results))
                                                @if($isCorrectOption)
                                                    <i class="ki-duotone ki-check-circle fs-1 text-success ms-3"><span class="path1"></span><span class="path2"></span></i>
                                                @elseif($userSelectedThis)
                                                    <i class="ki-duotone ki-cross-circle fs-1 text-danger ms-3"><span class="path1"></span><span class="path2"></span></i>
                                                @endif
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if(!$loop->last) <hr class="my-10 border-gray-200"> @endif
                @endforeach
            </div>

            <div class="card-footer d-flex justify-content-end py-6">
                @if(!isset($results))
                    <a href="{{ url()->previous() }}" class="btn btn-light me-3">Cancel</a>
                    <button type="submit" class="btn btn-success px-10">
                        Submit My Answers
                    </button>
                @else
                    <a href="{{ route('student.class.index') }}" class="btn btn-primary px-10">Finish Quiz</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
