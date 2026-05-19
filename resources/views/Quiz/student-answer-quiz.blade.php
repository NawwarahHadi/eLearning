@extends('layouts.app')

@section('content')
<!-- Game Assets -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>
    .rounded-xl { border-radius: 1.5rem !important; }
    .fw-extra-bold { font-weight: 800 !important; }
    .transition-3 { transition: all 0.3s ease; }
    .game-card { border: none; border-radius: 20px; transition: transform 0.3s; }
    .game-card:hover { transform: translateY(-5px); }
    .option-label { cursor: pointer; border: 2px solid #eff2f5; transition: all 0.2s; }
    .option-label:hover { background-color: #f1faff; border-color: #009ef7; }
    .btn-check:checked + .option-label { background-color: #f1faff; border-color: #009ef7; transform: scale(1.02); box-shadow: 0 10px 20px rgba(0,158,247,0.1); }
    .bg-gradient-game { background: linear-gradient(135deg, #1e1e2d 0%, #3e3e5e 100%); }
    .floating-xp { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 5rem; font-weight: 900; color: #ffc700; text-shadow: 0 0 20px rgba(255,199,0,0.5); z-index: 9999; pointer-events: none; }
</style>

<!-- Audio Engine -->
<audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-10.mp3" type="audio/mpeg"></audio>
<audio id="correctSfx"><source src="https://assets.mixkit.co/active_storage/sfx/2000/2000-preview.mp3" type="audio/mpeg"></audio>
<audio id="wrongSfx"><source src="https://assets.mixkit.co/active_storage/sfx/2959/2959-preview.mp3" type="audio/mpeg"></audio>

<div class="container-xxl py-10">

    {{-- 1. GAME HUD --}}
    @if(!isset($score))
    <div class="sticky-top bg-white shadow-sm p-5 mb-8 rounded-xl animate__animated animate__fadeInDown d-flex align-items-center justify-content-between" style="top: 80px; z-index: 1000;">
        <div class="d-flex align-items-center">
            <div class="symbol symbol-50px me-4">
                <div class="symbol-label bg-light-danger text-danger"><i class="ki-duotone ki-time fs-1"></i></div>
            </div>
            <div>
                <span class="text-muted fw-bold fs-8 d-block uppercase">TIME REMAINING</span>
                <span id="timer" class="fs-2 fw-extra-bold text-danger">05:00</span>
            </div>
        </div>

        <div class="flex-grow-1 mx-10">
            <div class="d-flex justify-content-between mb-1">
                <span class="fs-7 fw-extra-bold text-primary italic">PROGRESS: <span id="progressText">0%</span></span>
            </div>
            <div class="progress h-15px bg-light-primary rounded-pill">
                <div id="progressBar" class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
            </div>
        </div>

        <div class="d-flex gap-2 fs-1" id="livesContainer">
            <span>❤️</span><span>❤️</span><span>❤️</span>
        </div>
    </div>
    @endif

    {{-- 2. FLOATING XP POPUP --}}
    @if(isset($xpEarned))
        <div id="xpPopup" class="floating-xp animate__animated animate__fadeOutUp">
            +{{ $xpEarned }} XP
        </div>
    @endif

    {{-- 3. RESULT HERO --}}
    @if(isset($score))
    <div class="card shadow-lg mb-10 overflow-hidden animate__animated animate__jackInTheBox">
        <div class="card-body p-0 text-center">
            <div class="bg-gradient-game p-15">
                <div class="display-1 mb-5">🥇</div>
                <h1 class="text-white display-4 fw-extra-bold">MISSION COMPLETE</h1>
                <p class="text-white opacity-75 fs-2">You cleared the quest with <strong>{{ $score }}%</strong> accuracy!</p>

                @if(isset($leveledUp) && $leveledUp)
                    <div class="badge badge-light-warning fs-3 p-4 animate__animated animate__tada animate__infinite mt-4">
                        ⭐ NEW LEVEL UNLOCKED: LEVEL {{ auth()->user()->level }} ⭐
                    </div>
                @endif
            </div>
            <div class="p-10 bg-white">
                <div class="row g-5 mb-10 justify-content-center">
                    <div class="col-md-3">
                        <div class="bg-light rounded-xl p-8 border border-dashed border-primary">
                            <span class="fs-3 text-muted d-block fw-bold mb-2">XP EARNED</span>
                            <span class="fs-2hx fw-extra-bold text-primary">+{{ $xpEarned }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light rounded-xl p-8 border border-dashed border-success">
                            <span class="fs-3 text-muted d-block fw-bold mb-2">SCORE</span>
                            <span class="fs-2hx fw-extra-bold text-success">{{ $correct }}/{{ $total }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('quiz.map', $quiz->class_id) }}" class="btn btn-primary btn-lg px-20 rounded-pill hover-scale fw-bold">CONTINUE TO WORLD MAP</a>
            </div>
        </div>
    </div>
    @endif

    {{-- 4. QUESTION QUESTS --}}
    <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST" id="quizForm">
        @csrf
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @foreach($quiz->questions as $index => $question)
                    <div class="card game-card shadow-sm mb-8 animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.15 }}s">
                        <div class="card-body p-10">
                            <div class="d-flex align-items-center mb-8">
                                <span class="badge badge-circle badge-primary fs-3 fw-extra-bold me-4" style="width: 45px; height: 45px;">{{ $index + 1 }}</span>
                                <h2 class="fw-extra-bold text-gray-800 m-0">{{ $question->question_text }}</h2>
                            </div>

                            <div class="row g-5">
                                @foreach(['A', 'B', 'C', 'D'] as $opt)
                                    @php
                                        $optionKey = 'option_' . strtolower($opt);
                                        $userSelected = (isset($results) && isset($results[$question->id]) && $results[$question->id]['user_answer'] === $opt);
                                        $isCorrect = ($question->correct_option === $opt);

                                        $labelStyle = '';
                                        if(isset($results)) {
                                            if($isCorrect) $labelStyle = 'border-success bg-light-success pulse pulse-success';
                                            elseif($userSelected) $labelStyle = 'border-danger bg-light-danger';
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check quiz-option" name="q_{{ $question->id }}" value="{{ $opt }}"
                                            id="q{{$question->id}}_{{$opt}}" {{ isset($results) ? 'disabled' : '' }} {{ $userSelected ? 'checked' : '' }} required />

                                        <label class="btn option-label rounded-xl p-6 d-flex align-items-center w-100 {{ $labelStyle }}" for="q{{$question->id}}_{{$opt}}">
                                            <div class="symbol symbol-40px me-4">
                                                <span class="symbol-label bg-white fw-extra-bold text-primary shadow-sm border">{{ $opt }}</span>
                                            </div>
                                            <span class="fs-4 fw-bold text-gray-700 text-start">{{ $question->$optionKey }}</span>
                                            @if(isset($results) && $isCorrect) <span class="ms-auto fs-1">✔️</span> @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(!isset($results))
                <div class="text-center py-10">
                    <button type="submit" class="btn btn-lg btn-success px-20 rounded-pill shadow-lg hover-scale fs-2 fw-extra-bold">
                        FINISH QUEST <i class="ki-duotone ki-rocket fs-1 ms-3"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>

<script>
    // Game Audio Control
    const bgMusic = document.getElementById('bgMusic');
    const correctSfx = document.getElementById('correctSfx');
    const wrongSfx = document.getElementById('wrongSfx');

    document.addEventListener('click', () => {
        if(bgMusic.paused) bgMusic.play();
    }, {once: true});

    // Option Progress & Sounds
    const options = document.querySelectorAll('.quiz-option');
    options.forEach(opt => {
        opt.addEventListener('change', () => {
            const answered = new Set();
            document.querySelectorAll('.quiz-option:checked').forEach(e => answered.add(e.name));
            const progress = Math.round((answered.size / {{ $quiz->questions->count() }}) * 100);

            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressText').innerText = progress + '%';

            // Play Click SFX
            const click = new Audio('https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3');
            click.volume = 0.4;
            click.play();
        });
    });

    // Timer Logic
    @if(!isset($score))
        let seconds = 300;
        const timerInterval = setInterval(() => {
            seconds--;
            let mins = Math.floor(seconds / 60);
            let secs = seconds % 60;
            document.getElementById('timer').innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            if (seconds <= 0) { clearInterval(timerInterval); document.getElementById('quizForm').submit(); }
        }, 1000);
    @endif

    // Result Celebration
    @if(isset($score))
        bgMusic.pause();
        confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 } });
    @endif
</script>
@endsection
