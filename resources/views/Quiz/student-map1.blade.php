@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght=700;900&display=swap" rel="stylesheet">

<style>
    /* 1. Game Environment Frame */
    .treasure-hunt-world {
        background-color: #2c4a3e;
        background-image: radial-gradient(circle at 50% 30%, #3d6e59 0%, #1e332a 100%);
        min-height: 100vh;
        width: 100%;
        padding: 40px 20px 150px 20px;
        font-family: 'Nunito', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .map-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .map-title {
        font-family: 'Fredoka One', cursive;
        font-size: 2.8rem;
        color: #fffdf6;
        text-shadow: 0 6px 0 #e07a16, 0 10px 20px rgba(0,0,0,0.5);
    }
    .map-subtitle {
        color: #fff;
        background: rgba(0,0,0,0.3);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 700;
    }

    /* 2. Reliable Layout Container */
    .grid-path-container {
        display: flex;
        flex-direction: column;
        gap: 25px;
        width: 100%;
        max-width: 400px;
        justify-content: center;
        margin-top: 20px;
    }

    /* Horizontal Row Container */
    .card-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        position: relative;
    }

    /* Flex direction modifier to physically flip card placement order safely on odd rows */
    .row-reverse-flow {
        flex-direction: row-reverse !important;
    }

    /* 3. The Mission Bundle Cards */
    .bundle-card {
        background: #e2f3fc;
        border: 4px solid #ffffff;
        border-radius: 24px;
        padding: 16px;
        text-align: center;
        position: relative;
        box-shadow: 0 8px 0 rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        min-height: 195px;
        width: 180px;
        transition: transform 0.2s ease;
    }

    .bundle-card.current {
        background: #fff3cc;
        border-color: #ffcc00;
        box-shadow: 0 8px 0 #dca600, 0 12px 25px rgba(255, 204, 0, 0.4);
        animation: cardPulse 2s infinite ease-in-out;
    }

    .bundle-card.completed {
        background: #d1fae5;
        border-color: #34d399;
        box-shadow: 0 8px 0 #059669;
    }

    .bundle-card.locked {
        background: #e2e8f0;
        border-color: #cbd5e1;
        opacity: 0.8;
    }

    .card-badge {
        position: absolute;
        top: -10px;
        left: -5px;
        background: #4ade80;
        color: white;
        font-weight: 900;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .topic-display {
        font-weight: 900;
        color: #1e293b;
        font-size: 0.95rem;
        margin-top: 10px;
        line-height: 1.2;
    }

    .star-display {
        font-size: 1.4rem;
        color: #ffaa00;
        margin: 5px 0;
    }

    /* 4. Action Buttons */
    .action-button {
        width: 100%;
        border-radius: 14px;
        font-family: 'Fredoka One', cursive;
        font-size: 1.1rem;
        padding: 8px 0;
        color: white;
        border: none;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: block;
    }
    .btn-play {
        background: linear-gradient(to bottom, #ff6b3d, #e04416);
        box-shadow: 0 4px 0 #b32d07;
    }
    .btn-play:active {
        transform: translateY(2px);
        box-shadow: 0 2px 0 #b32d07;
    }
    .btn-locked {
        background: linear-gradient(to bottom, #94a3b8, #64748b);
        box-shadow: 0 4px 0 #475569;
        cursor: not-allowed;
    }
    .btn-done {
        background: linear-gradient(to bottom, #10b981, #059669);
        box-shadow: 0 4px 0 #047857;
    }

    /* 5. ARROW CONNECTOR STYLING */
    .flow-arrow {
        background: #498fb3;
        color: white;
        border: 2px solid white;
        border-radius: 6px;
        font-weight: 900;
        font-size: 0.8rem;
        width: 28px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        z-index: 20;
    }

    .horizontal-connector {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .vertical-connector-row {
        display: flex;
        width: 100%;
        margin: -5px 0;
    }
    .justify-left { justify-content: flex-start; padding-left: 76px; }
    .justify-right { justify-content: flex-end; padding-right: 76px; }

    /* 6. Selection Modal Menu Overlay */
    .quest-menu {
        position: absolute;
        width: calc(100% - 20px);
        background: #ffffff;
        border-radius: 16px;
        padding: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        border: 3px solid #ffaa00;
        bottom: 10px;
        z-index: 100;
    }

    @keyframes cardPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
</style>

<div class="treasure-hunt-world">
    <div class="map-header">
        <h1 class="map-title animate__animated animate__fadeInDown">{{ $class->subject->name ?? 'MISSION BUNDLE' }}</h1>
        <span class="map-subtitle">Complete current topic to unlock the next one</span>
    </div>

    <div class="grid-path-container">
        @php
            $roadblockEncountered = false;
            $totalMapNodes = max(8, $materials->count());

            // Build the indexes naturally in incremental order (0, 1, 2, 3...)
            $nodesArray = range(0, $totalMapNodes - 1);
            $rows = array_chunk($nodesArray, 2);
        @endphp

        @foreach($rows as $rowIndex => $pair)
            @php
                $isEvenRow = ($rowIndex % 2 === 0);

                // Keep standard left/right indices consistent with chronological order
                $leftIndex  = $pair[0] ?? null;
                $rightIndex = $pair[1] ?? null;
            @endphp

            <div class="card-row {{ !$isEvenRow ? 'row-reverse-flow' : '' }}">

                @if(!is_null($leftIndex))
                    @php
                        $index = $leftIndex;
                        $material = $materials->get($index);
                        $hasMaterial = !is_null($material);
                        $quiz = $hasMaterial ? $material->quiz : null;
                        $starsEarned = 0;

                        if ($hasMaterial) {
                            if ($quiz) {
                                $bestAttempt = $quiz->attempts->where('student_id', auth()->id())->sortByDesc('score')->first();
                                $isCompleted = !is_null($bestAttempt);
                                if ($isCompleted) {
                                    if ($bestAttempt->score >= 90) $starsEarned = 3;
                                    elseif ($bestAttempt->score >= 60) $starsEarned = 2;
                                    else $starsEarned = 1;
                                }
                            } else { $isCompleted = true; }
                        } else { $isCompleted = false; }

                        $isLocked = ($index === 0) ? false : ($roadblockEncountered || !$hasMaterial);
                        if (!$isCompleted) { $roadblockEncountered = true; }
                        $status = ($isCompleted && $quiz) ? 'completed' : ($isLocked ? 'locked' : 'current');
                    @endphp

                    <div class="bundle-card {{ $status }} animate__animated animate__fadeInUp">
                        <div class="card-badge">TOPIC {{ $index + 1 }}</div>
                        <div class="topic-display text-center">{{ $hasMaterial ? $material->topic : 'Coming Soon' }}</div>
                        <div class="my-2">
                            @if($status === 'completed')
                                <div class="star-display">{!! str_repeat('★', $starsEarned) !!}{!! str_repeat('☆', 3 - $starsEarned) !!}</div>
                            @elseif($status === 'current')
                                <span class="fs-1 animate__animated animate__pulse animate__infinite d-block">📖</span>
                            @else
                                <span class="fs-1 text-muted" style="opacity: 0.5;">🔒</span>
                            @endif
                        </div>
                        <button class="action-button {{ $status === 'completed' ? 'btn-done' : ($status === 'current' ? 'btn-play' : 'btn-locked') }}" {{ $status === 'locked' ? 'disabled' : '' }} onclick="toggleMenu({{ $index }})">
                            {{ $status === 'completed' ? 'COMPLETED' : ($status === 'current' ? 'OPEN' : 'LOCKED') }}
                        </button>

                        @if($hasMaterial && !$isLocked)
                        <div id="menu-{{ $index }}" class="quest-menu animate__animated animate__zoomIn" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">OPTIONS</span>
                                <button class="btn-close btn-sm" onclick="event.stopPropagation(); closeAllMenus()" style="font-size:0.6rem"></button>
                            </div>

                            <a href="{{ asset('storage/' . ($material->lecture_note ?? $material->file_path)) }}" target="_blank" class="btn btn-xs btn-outline-secondary w-100 mb-1 py-1 small fw-bold">📖 NOTES</a>

                            @if($quiz)
                                @if($status === 'completed')
                                    <a href="{{ route('quiz.review', $quiz->id) }}" class="btn btn-xs btn-info text-white w-100 mb-1 py-1 small fw-bold">🔍 REVIEW</a>
                                    <a href="{{ route('quiz.play', $quiz->id) }}" class="btn btn-xs btn-warning text-white w-100 py-1 small fw-bold">🔄 RETRY</a>
                                @else
                                    <a href="{{ route('quiz.play', $quiz->id) }}" class="btn btn-xs btn-warning text-white w-100 py-1 small fw-bold">🚀 QUIZ</a>
                                @endif
                            @endif
                        </div>
                        @endif
                    </div>
                @endif

                @if(!is_null($leftIndex) && !is_null($rightIndex))
                    <div class="flow-arrow horizontal-connector">
                        {!! $isEvenRow ? '》' : '《' !!}
                    </div>
                @endif

                @if(!is_null($rightIndex))
                    @php
                        $index = $rightIndex;
                        $material = $materials->get($index);
                        $hasMaterial = !is_null($material);
                        $quiz = $hasMaterial ? $material->quiz : null;
                        $starsEarned = 0;

                        if ($hasMaterial) {
                            if ($quiz) {
                                $bestAttempt = $quiz->attempts->where('student_id', auth()->id())->sortByDesc('score')->first();
                                $isCompleted = !is_null($bestAttempt);
                                if ($isCompleted) {
                                    if ($bestAttempt->score >= 90) $starsEarned = 3;
                                    elseif ($bestAttempt->score >= 60) $starsEarned = 2;
                                    else $starsEarned = 1;
                                }
                            } else { $isCompleted = true; }
                        } else { $isCompleted = false; }

                        $isLocked = ($index === 0) ? false : ($roadblockEncountered || !$hasMaterial);
                        if (!$isCompleted) { $roadblockEncountered = true; }
                        $status = ($isCompleted && $quiz) ? 'completed' : ($isLocked ? 'locked' : 'current');
                    @endphp

                    <div class="bundle-card {{ $status }} animate__animated animate__fadeInUp">
                        <div class="card-badge">TOPIC {{ $index + 1 }}</div>
                        <div class="topic-display text-center">{{ $hasMaterial ? $material->topic : 'Coming Soon' }}</div>
                        <div class="my-2">
                            @if($status === 'completed')
                                <div class="star-display">{!! str_repeat('★', $starsEarned) !!}{!! str_repeat('☆', 3 - $starsEarned) !!}</div>
                            @elseif($status === 'current')
                                <span class="fs-1 animate__animated animate__pulse animate__infinite d-block">📖</span>
                            @else
                                <span class="fs-1 text-muted" style="opacity: 0.5;">🔒</span>
                            @endif
                        </div>
                        <button class="action-button {{ $status === 'completed' ? 'btn-done' : ($status === 'current' ? 'btn-play' : 'btn-locked') }}" {{ $status === 'locked' ? 'disabled' : '' }} onclick="toggleMenu({{ $index }})">
                            {{ $status === 'completed' ? 'COMPLETED' : ($status === 'current' ? 'OPEN' : 'LOCKED') }}
                        </button>

                        @if($hasMaterial && !$isLocked)
                        <div id="menu-{{ $index }}" class="quest-menu animate__animated animate__zoomIn" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-bold small">OPTIONS</span>
                                <button class="btn-close btn-sm" onclick="event.stopPropagation(); closeAllMenus()" style="font-size:0.6rem"></button>
                            </div>

                            <a href="{{ asset('storage/' . ($material->lecture_note ?? $material->file_path)) }}" target="_blank" class="btn btn-xs btn-outline-secondary w-100 mb-1 py-1 small fw-bold">📖 NOTES</a>

                            @if($quiz)
                                @if($status === 'completed')
                                    <a href="{{ route('quiz.review', $quiz->id) }}" class="btn btn-xs btn-info text-white w-100 mb-1 py-1 small fw-bold">🔍 REVIEW</a>
                                    <a href="{{ route('quiz.play', $quiz->id) }}" class="btn btn-xs btn-warning text-white w-100 py-1 small fw-bold">🔄 RETRY</a>
                                @else
                                    <a href="{{ route('quiz.play', $quiz->id) }}" class="btn btn-xs btn-warning text-white w-100 py-1 small fw-bold">🚀 QUIZ</a>
                                @endif
                            @endif
                        </div>
                        @endif
                    </div>
                @endif

            </div>

            @if(!$loop->last)
                <div class="vertical-connector-row {{ $isEvenRow ? 'justify-right' : 'justify-left' }}">
                    <div class="flow-arrow">︾</div>
                </div>
            @endif

        @endforeach
    </div>
</div>

<script>
    function toggleMenu(index) {
        if (window.event) window.event.stopPropagation();

        const menu = document.getElementById('menu-' + index);
        if (menu) {
            const isCurrentlyVisible = menu.style.display === 'block';
            closeAllMenus();
            if (!isCurrentlyVisible) {
                menu.style.display = 'block';
            }
        }
    }

    function closeAllMenus() {
        document.querySelectorAll('.quest-menu').forEach(m => m.style.display = 'none');
    }

    window.onclick = function() { closeAllMenus(); }
</script>
@endsection
