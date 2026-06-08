@extends('layouts.app')

@section('title', 'Learning Adventure')
@section('page-header', 'Learning Materials')

@section('css_after')
    {{-- <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@700;900&display=swap" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    {{-- <style>
        .quest-world {
            position: relative;
            background:
                radial-gradient(circle at 20% 20%, rgba(124,58,237,0.25) 0%, transparent 40%),
                radial-gradient(circle at 80% 60%, rgba(59,130,246,0.25) 0%, transparent 40%),
                linear-gradient(160deg, #0f0c29 0%, #1a1442 50%, #0d1b3e 100%);
            min-height: 100vh; width: 100%;
            padding: 50px 20px 150px; font-family: 'Nunito', sans-serif;
            display: flex; flex-direction: column; align-items: center; border-radius: 14px;
        }

        .map-header { text-align: center; margin-bottom: 24px; }
        .map-title {
            font-family: 'Fredoka', cursive; font-weight: 700; font-size: 3.2rem;
            background: linear-gradient(90deg, #a78bfa, #60a5fa, #c084fc);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .map-subtitle {
            color: #c7d2fe; background: rgba(124,58,237,0.2); padding: 8px 22px;
            border-radius: 20px; font-size: 1rem; font-weight: 700; display: inline-block; margin-top: 10px;
            border: 1px solid rgba(167,139,250,0.3);
        }

        .coin-bar {
            display: flex; align-items: center; gap: 10px;
            background: rgba(0,0,0,0.4); border: 2px solid #fbbf24;
            border-radius: 30px; padding: 10px 26px; margin: 18px 0;
        }
        .coin-icon { font-size: 1.8rem; }
        .coin-count { color: #fbbf24; font-weight: 900; font-size: 1.6rem; font-family: 'Fredoka', cursive; }

        .progress-track {
            width: 100%; max-width: 520px; background: rgba(0,0,0,0.4);
            border-radius: 20px; height: 26px; margin: 6px 0 36px; overflow: hidden;
            border: 1px solid rgba(167,139,250,0.3); position: relative;
        }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #a78bfa, #60a5fa); border-radius: 20px; }
        .progress-label { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 900; font-size: 0.85rem; }

        .grid-path-container { display: flex; flex-direction: column; gap: 35px; width: 100%; max-width: 560px; }
        .card-row { display: flex; justify-content: space-between; align-items: center; width: 100%; position: relative; }
        .row-reverse-flow { flex-direction: row-reverse !important; }

        /* BIGGER cards */
        .bundle-card {
            background: rgba(30,27,75,0.85);
            border: 2px solid rgba(167,139,250,0.4); border-radius: 26px;
            padding: 22px; text-align: center; position: relative;
            box-shadow: 0 8px 30px rgba(0,0,0,0.5);
            display: flex; flex-direction: column; align-items: center; justify-content: space-between;
            min-height: 260px; width: 240px;
        }
        .bundle-card.current { border-color: #fbbf24; box-shadow: 0 0 30px rgba(251,191,36,0.4), 0 8px 30px rgba(0,0,0,0.5); }
        .bundle-card.completed { border-color: #34d399; box-shadow: 0 0 25px rgba(52,211,153,0.3), 0 8px 30px rgba(0,0,0,0.5); }
        .bundle-card.locked { border-color: rgba(100,116,139,0.4); opacity: 0.55; }

        .card-badge {
            position: absolute; top: -14px; left: -8px; color: #0f0c29; font-weight: 900;
            font-size: 0.8rem; padding: 4px 14px; border-radius: 12px; font-family: 'Fredoka', cursive;
            background: #a78bfa;
        }
        .current .card-badge { background: #fbbf24; }
        .completed .card-badge { background: #34d399; }

        .topic-display { font-weight: 900; color: #e0e7ff; font-size: 1.15rem; margin-top: 12px; line-height: 1.3; }
        .star-display { font-size: 1.9rem; color: #fbbf24; margin: 8px 0; }
        .coin-reward { color: #fbbf24; font-size: 0.9rem; font-weight: 700; }

        .action-button {
            width: 100%; border-radius: 16px; font-family: 'Fredoka', cursive; font-size: 1.25rem;
            padding: 12px 0; color: white; border: none; cursor: pointer; text-decoration: none; display: block;
        }
        .btn-play { background: linear-gradient(to bottom, #8b5cf6, #6d28d9); box-shadow: 0 5px 0 #4c1d95; }
        .btn-play:active { transform: translateY(2px); box-shadow: 0 3px 0 #4c1d95; }
        .btn-locked { background: linear-gradient(to bottom, #475569, #334155); box-shadow: 0 5px 0 #1e293b; cursor: not-allowed; }
        .btn-done { background: linear-gradient(to bottom, #10b981, #059669); box-shadow: 0 5px 0 #047857; }

        .flow-arrow {
            background: rgba(167,139,250,0.9); color: #0f0c29; border: 2px solid #c7d2fe; border-radius: 10px;
            font-weight: 900; font-size: 1rem; width: 36px; height: 32px;
            display: flex; align-items: center; justify-content: center; z-index: 20;
        }
        .horizontal-connector { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); }
        .vertical-connector-row { display: flex; width: 100%; margin: -8px 0; }
        .justify-left { justify-content: flex-start; padding-left: 100px; }
        .justify-right { justify-content: flex-end; padding-right: 100px; }

        .quest-menu {
            position: absolute; width: calc(100% - 24px); background: #1e1b4b;
            border-radius: 18px; padding: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.6);
            border: 2px solid #a78bfa; bottom: 12px; z-index: 100;
        }
    </style> --}}
    <style>
        :root{
            --primary:#6366f1;
            --secondary:#8b5cf6;
            --success:#22c55e;
            --warning:#fbbf24;
            --card-bg:rgba(255,255,255,.08);
        }

        .quest-world{
            position:relative;
            overflow:hidden;

            background:
                radial-gradient(circle at top left,
                    rgba(99,102,241,.18),
                    transparent 35%),
                radial-gradient(circle at bottom right,
                    rgba(168,85,247,.18),
                    transparent 35%),
                linear-gradient(
                    135deg,
                    #0f172a 0%,
                    #111827 50%,
                    #1e293b 100%
                );

            min-height:100vh;
            padding:40px 20px 120px;

            display:flex;
            flex-direction:column;
            align-items:center;

            border-radius:24px;

            font-family:'Plus Jakarta Sans',sans-serif;
        }

        .map-header{
            text-align:center;
            margin-bottom:25px;
        }

        .map-title{
            font-family:'Outfit',sans-serif;
            font-size:3rem;
            font-weight:800;
            color:white;
            margin-bottom:10px;
        }

        .map-subtitle{
            display:inline-block;
            padding:10px 20px;

            border-radius:999px;

            background:rgba(255,255,255,.08);

            border:1px solid rgba(255,255,255,.12);

            color:#cbd5e1;

            backdrop-filter:blur(12px);
        }

        .coin-bar{
            display:flex;
            align-items:center;
            gap:12px;

            background:rgba(255,255,255,.08);

            backdrop-filter:blur(20px);

            border:1px solid rgba(255,255,255,.15);

            border-radius:999px;

            padding:12px 22px;

            margin-bottom:20px;
        }

        .coin-count{
            color:var(--warning);

            font-size:1.5rem;

            font-weight:800;

            font-family:'Outfit',sans-serif;
        }

        .progress-track{
            width:100%;
            max-width:500px;

            height:18px;

            border-radius:999px;

            overflow:hidden;

            background:rgba(255,255,255,.08);

            margin-bottom:40px;

            position:relative;
        }

        .progress-fill{
            height:100%;

            background:
                linear-gradient(
                    90deg,
                    var(--success),
                    #3b82f6
                );
        }

        .progress-label{
            position:absolute;
            inset:0;

            display:flex;
            align-items:center;
            justify-content:center;

            color:white;

            font-size:.8rem;

            font-weight:700;
        }

        .grid-path-container{
            display:flex;
            flex-direction:column;
            gap:30px;

            width:100%;
            max-width:700px;
        }

        .card-row{
            display:flex;
            justify-content:space-between;
            align-items:center;

            position:relative;
        }

        .row-reverse-flow{
            flex-direction:row-reverse!important;
        }

        .bundle-card{
            width:240px;
            min-height:260px;

            padding:22px;

            border-radius:24px;

            background:var(--card-bg);

            backdrop-filter:blur(18px);

            border:1px solid rgba(255,255,255,.12);

            box-shadow:
                0 10px 30px rgba(0,0,0,.25);

            display:flex;
            flex-direction:column;
            justify-content:space-between;
            align-items:center;

            transition:.3s;
        }

        .bundle-card:hover{
            transform:translateY(-8px);
        }

        .bundle-card.current{
            border:2px solid var(--warning);

            box-shadow:
                0 0 30px rgba(251,191,36,.3);
        }

        .bundle-card.completed{
            border:2px solid var(--success);
        }

        .bundle-card.locked{
            opacity:.45;
        }

        .card-badge{
            position:absolute;
            top:15px;
            left:15px;

            background:rgba(99,102,241,.15);

            border:1px solid rgba(99,102,241,.25);

            color:#a5b4fc;

            border-radius:999px;

            padding:6px 12px;

            font-size:.75rem;

            font-weight:700;
        }

        .topic-display{
            color:white;

            text-align:center;

            font-size:1rem;

            font-weight:700;

            line-height:1.5;

            margin-top:25px;
        }

        .star-display{
            font-size:1.8rem;
            color:var(--warning);
        }

        .coin-reward{
            color:var(--warning);
            font-weight:700;
        }

        .action-button{
            width:100%;

            border:none;

            border-radius:14px;

            padding:12px;

            color:white;

            font-weight:700;
        }

        .btn-play{
            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--secondary)
                );
        }

        .btn-done{
            background:
                linear-gradient(
                    135deg,
                    var(--success),
                    #16a34a
                );
        }

        .btn-locked{
            background:#475569;
        }

        .flow-arrow{
            width:36px;
            height:36px;

            border-radius:50%;

            background:white;

            color:#6366f1;

            font-weight:bold;

            display:flex;
            justify-content:center;
            align-items:center;
        }

        .quest-menu{
            position:absolute;
            bottom:10px;

            width:calc(100% - 20px);

            background:rgba(17,24,39,.98);

            border:1px solid rgba(255,255,255,.12);

            border-radius:18px;

            backdrop-filter:blur(20px);

            padding:15px;

            z-index:100;
        }

        @media(max-width:768px){

            .map-title{
                font-size:2rem;
            }

            .bundle-card{
                width:160px;
                min-height:220px;
            }

            .topic-display{
                font-size:.9rem;
            }
        }
    </style>
@endsection

@section('js_after')
<script>
    function toggleMenu(index) {
        if (window.event) window.event.stopPropagation();
        const menu = document.getElementById('menu-' + index);
        if (menu) { const v = menu.style.display === 'block'; closeAllMenus(); if (!v) menu.style.display = 'block'; }
    }
    function closeAllMenus() { document.querySelectorAll('.quest-menu').forEach(m => m.style.display = 'none'); }
    window.onclick = function() { closeAllMenus(); }
</script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="quest-world">

            <div class="map-header">
                <h1 class="map-title">{{ $class->subject->name ?? 'QUEST MAP' }}</h1>
                <span class="map-subtitle">⚔️ Clear quests · earn coins · level up!</span>
            </div>

            {{-- <div class="coin-bar">
                <span class="coin-icon">🪙</span>
                <span class="coin-count">{{ auth()->user()->coins ?? 0 }}</span>
                <span class="text-white-50 fw-bold fs-7">coins</span>
            </div> --}}
            <div class="coin-bar">
                <i class="fas fa-coins text-warning fs-3"></i>
                <span class="coin-count">{{ auth()->user()->coins ?? 0 }}</span>
                <span class="text-white-50">Coins</span>
            </div>

            @php
                $completedCount = 0;
                foreach ($materials as $m) {
                    if ($m->quiz && $m->quiz->attempts->where('student_id', auth()->id())->count() > 0) $completedCount++;
                }
                $totalForBar = max(1, $materials->count());
                $pct = round(($completedCount / $totalForBar) * 100);
            @endphp
            <div class="progress-track">
                <div class="progress-fill" style="width: {{ $pct }}%"></div>
                <div class="progress-label">{{ $completedCount }} / {{ $materials->count() }} quests cleared ({{ $pct }}%)</div>
            </div>

            <div class="grid-path-container">
                @php
                    $roadblockEncountered = false;
                    $totalMapNodes = max(8, $materials->count());
                    $rows = array_chunk(range(0, $totalMapNodes - 1), 2);
                @endphp

                @foreach($rows as $rowIndex => $pair)
                    @php $isEvenRow = ($rowIndex % 2 === 0); $leftIndex = $pair[0] ?? null; $rightIndex = $pair[1] ?? null; @endphp

                    <div class="card-row {{ !$isEvenRow ? 'row-reverse-flow' : '' }}">
                        @foreach([$leftIndex, $rightIndex] as $slot => $nodeIndex)
                            @if(!is_null($nodeIndex))
                                @php
                                    $index = $nodeIndex;
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

                                <div class="bundle-card {{ $status }}">
                                    <div class="card-badge">TOPIC {{ $index + 1 }}</div>
                                    <div class="topic-display">{{ $hasMaterial ? $material->topic : 'Coming Soon' }}</div>
                                    <div class="my-2">
                                        @if($status === 'completed')
                                            <div class="star-display">{!! str_repeat('★', $starsEarned) !!}{!! str_repeat('☆', 3 - $starsEarned) !!}</div>
                                            <div class="coin-reward">🪙 +{{ $starsEarned * 10 }}</div>
                                        @elseif($status === 'current')
                                            <span style="font-size:2.6rem;" class="d-block">⚔️</span>
                                            <div class="coin-reward">🪙 up to +30</div>
                                        @else
                                            <span style="font-size:2.6rem; opacity:0.4;">🔒</span>
                                        @endif
                                    </div>
                                    <button class="action-button {{ $status === 'completed' ? 'btn-done' : ($status === 'current' ? 'btn-play' : 'btn-locked') }}"
                                            {{ $status === 'locked' ? 'disabled' : '' }} onclick="toggleMenu({{ $index }})">
                                        {{ $status === 'completed' ? '✓ CLEARED' : ($status === 'current' ? '▶ START' : 'LOCKED') }}
                                    </button>

                                    @if($hasMaterial && !$isLocked)
                                    <div id="menu-{{ $index }}" class="quest-menu" style="display:none;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-white-50 fw-bold small">⚙️ OPTIONS</span>
                                            <button class="btn-close btn-close-white btn-sm" onclick="event.stopPropagation(); closeAllMenus()" style="font-size:0.7rem"></button>
                                        </div>
                                        <a href="{{ asset('storage/' . ($material->lecture_note ?? $material->file_path)) }}" target="_blank" class="btn btn-sm btn-light w-100 mb-1 fw-bold">📖 NOTES</a>
                                        @if($quiz)
                                            @if($status === 'completed')
                                                <a href="{{ route('quiz.review', $quiz->id) }}" class="btn btn-sm btn-info text-white w-100 mb-1 fw-bold">🔍 REVIEW</a>
                                                <a href="{{ route('quiz.play', $quiz->id) }}" class="btn btn-sm btn-warning text-white w-100 fw-bold">🔄 RETRY</a>
                                            @else
                                                <a href="{{ route('quiz.play', $quiz->id) }}" class="btn btn-sm btn-warning text-white w-100 fw-bold">🚀 QUIZ</a>
                                            @endif
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            @endif

                            @if($slot === 0 && !is_null($leftIndex) && !is_null($rightIndex))
                                <div class="flow-arrow horizontal-connector">➜</div>
                            @endif
                        @endforeach
                    </div>

                    @if(!$loop->last)
                        <div class="vertical-connector-row {{ $isEvenRow ? 'justify-right' : 'justify-left' }}">
                            <div class="flow-arrow">▼</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
