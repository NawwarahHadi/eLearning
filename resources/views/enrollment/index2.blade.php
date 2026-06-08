@extends('layouts.app')

@section('title', 'Enrollment Class')
@section('page-header', 'Enrollment Class')

@section('css_after')
<style>
    /* ── Page wrapper ── */
    #enrollmentPage { padding: 0 0.5rem; }

    /* ── Section headers ── */
    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        color: #9a9a9a;
        margin-bottom: 4px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── AI recommendation cards ── */
    .ai-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 2.5rem;
    }
    .ai-card {
        background: #fff;
        border: 1.5px dashed #c7c2f8;
        border-radius: 14px;
        padding: 1.25rem;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        height: 100%;
    }
    .ai-card:hover { border-color: #4f46e5; background: #fafafe; }
    .ai-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    .match-pct {
        font-size: 12px;
        font-weight: 700;
        color: #4f46e5;
        background: #eef2ff;
        padding: 3px 10px;
        border-radius: 999px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .ai-subject {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 3px;
    }
    .ai-score { font-size: 12.5px; color: #9a9a9a; margin-bottom: 1rem; }
    .ai-tutor-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 0.75rem;
        border-top: 0.5px solid rgba(0,0,0,0.07);
    }
    .tutor-meta-label { font-size: 11px; color: #9a9a9a; }
    .tutor-meta-name { font-size: 13px; font-weight: 600; color: #444; }

    /* ── Avatars ── */
    .avatar-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 10px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .avatar-indigo { background: #eef2ff; color: #4f46e5; }
    .avatar-amber  { background: #fffbeb; color: #d97706; }

    /* ── Status badges ── */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
    }
    .badge-pill::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-needs   { background: #fff1f2; color: #be123c; }
    .badge-needs::before   { background: #be123c; }
    .badge-strength { background: #ecfdf5; color: #059669; }
    .badge-strength::before { background: #059669; }
    .badge-discovery { background: #eff6ff; color: #2563eb; }
    .badge-discovery::before { background: #2563eb; }
    .badge-form {
        background: #f1efe8;
        color: #5f5e5a;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 8px;
    }

    /* ── Divider ── */
    .section-divider {
        border: none;
        border-top: 0.5px solid rgba(0,0,0,0.08);
        margin: 2rem 0;
    }

    /* ── Tab bar ── */
    .tab-bar-wrap {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.25rem;
    }
    .tab-bar {
        display: flex;
        background: #f1efe8;
        border-radius: 10px;
        padding: 3px;
        gap: 2px;
    }
    .tab-btn {
        font-size: 12px;
        font-weight: 700;
        padding: 6px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
        color: #888;
        background: transparent;
    }
    .tab-btn.active {
        background: #fff;
        color: #1a1a1a;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    /* ── Class cards ── */
    .class-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    .class-card {
        background: #fff;
        border: 0.5px solid rgba(0,0,0,0.08);
        border-radius: 14px;
        padding: 1.25rem;
        height: 100%;
        transition: border-color 0.15s;
    }
    .class-card:hover { border-color: #c7c2f8; }
    .class-subject {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 6px 0 3px;
    }
    .class-lang { font-size: 12px; color: #9a9a9a; margin-bottom: 0.9rem; }
    .class-tutor-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1rem;
    }
    .btn-select {
        display: block;
        width: 100%;
        text-align: center;
        background: #4f46e5;
        color: #fff !important;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 0;
        border-radius: 9px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-select:hover { background: #4338ca; }

    /* ── Empty state ── */
    .empty-state {
        background: #eff6ff;
        border: 1.5px dashed #93c5fd;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        font-size: 13.5px;
        color: #2563eb;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .ai-grid    { grid-template-columns: 1fr; }
        .class-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('js_after')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- HARDCODED DEMO DATA (Option B) — remove when wiring real data  --}}
{{-- ════════════════════════════════════════════════════════════ --}}
@php
    $allClasses = collect([
        (object)['id'=>1,'tutor_id'=>1,'category_code'=>'form-1','language_code'=>'English','subject'=>(object)['name'=>'Science'],'tutor'=>(object)['name'=>'Nurul Aisyah']],
        (object)['id'=>2,'tutor_id'=>2,'category_code'=>'form-1','language_code'=>'Bahasa Melayu','subject'=>(object)['name'=>'Mathematics'],'tutor'=>(object)['name'=>'Mohd Hafiz']],
        (object)['id'=>3,'tutor_id'=>3,'category_code'=>'form-2','language_code'=>'English','subject'=>(object)['name'=>'Science'],'tutor'=>(object)['name'=>'Priya Devi']],
        (object)['id'=>4,'tutor_id'=>2,'category_code'=>'form-2','language_code'=>'English','subject'=>(object)['name'=>'Mathematics'],'tutor'=>(object)['name'=>'Mohd Hafiz']],
        (object)['id'=>5,'tutor_id'=>1,'category_code'=>'form-3','language_code'=>'Bahasa Melayu','subject'=>(object)['name'=>'Science'],'tutor'=>(object)['name'=>'Nurul Aisyah']],
        (object)['id'=>6,'tutor_id'=>4,'category_code'=>'form-3','language_code'=>'English','subject'=>(object)['name'=>'Mathematics'],'tutor'=>(object)['name'=>'Daniel Tan']],
        (object)['id'=>7,'tutor_id'=>2,'category_code'=>'form-4','language_code'=>'English','subject'=>(object)['name'=>'Physics'],'tutor'=>(object)['name'=>'Daniel Tan']],
        (object)['id'=>8,'tutor_id'=>1,'category_code'=>'form-4','language_code'=>'English','subject'=>(object)['name'=>'Biology'],'tutor'=>(object)['name'=>'Nurul Aisyah']],
        (object)['id'=>9,'tutor_id'=>3,'category_code'=>'form-4','language_code'=>'Bahasa Melayu','subject'=>(object)['name'=>'Chemistry'],'tutor'=>(object)['name'=>'Priya Devi']],
        (object)['id'=>10,'tutor_id'=>5,'category_code'=>'form-5','language_code'=>'English','subject'=>(object)['name'=>'Physics'],'tutor'=>(object)['name'=>'Sarah Lee']],
        (object)['id'=>11,'tutor_id'=>5,'category_code'=>'form-5','language_code'=>'English','subject'=>(object)['name'=>'Biology'],'tutor'=>(object)['name'=>'Sarah Lee']],
        (object)['id'=>12,'tutor_id'=>4,'category_code'=>'form-5','language_code'=>'Bahasa Melayu','subject'=>(object)['name'=>'Additional Mathematics'],'tutor'=>(object)['name'=>'Daniel Tan']],
    ]);

    $recommended = collect([
        (object)['id'=>7,'tutor_id'=>2,'subject_id'=>101,'ai_match_percentage'=>94,'subject'=>(object)['name'=>'Physics'],'tutor'=>(object)['name'=>'Daniel Tan']],
        (object)['id'=>8,'tutor_id'=>1,'subject_id'=>102,'ai_match_percentage'=>89,'subject'=>(object)['name'=>'Biology'],'tutor'=>(object)['name'=>'Nurul Aisyah']],
        (object)['id'=>11,'tutor_id'=>5,'subject_id'=>103,'ai_match_percentage'=>86,'subject'=>(object)['name'=>'Biology'],'tutor'=>(object)['name'=>'Sarah Lee']],
        (object)['id'=>9,'tutor_id'=>3,'subject_id'=>104,'ai_match_percentage'=>82,'subject'=>(object)['name'=>'Chemistry'],'tutor'=>(object)['name'=>'Priya Devi']],
    ]);
@endphp

<div id="enrollmentPage" class="container mt-5">

    {{-- SECTION 1: AI RECOMMENDATION --}}
    <div class="mb-10">
        <div class="section-label">Personalized for you</div>
        <div class="section-title">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <circle cx="8" cy="8" r="7" stroke="#4f46e5" stroke-width="1.5"/>
                <path d="M5 8.5l2 2 4-4" stroke="#4f46e5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Recommended for you
        </div>

        <div class="ai-grid">
            @forelse($recommended as $item)
                @php
                    // Hardcoded demo: no DB lookup for the student's past result.
                    $res = null;
                    $initials = strtoupper(substr($item->tutor->name, 0, 2));
                @endphp
                <div class="ai-card"
                     onclick="window.location.href='{{ route('enrollment.tutors', [$item->id, $item->tutor_id]) }}'">
                    {{-- <div class="ai-card-top">
                        @if($res && $res->score < 50)
                            <span class="badge-pill badge-needs">Priority: needs improvement</span>
                        @elseif($res && $res->score >= 50)
                            <span class="badge-pill badge-strength">Strength reinforcement</span>
                        @else
                            <span class="badge-pill badge-discovery">AI discovery</span>
                        @endif
                        <span class="match-pct">{{ $item->ai_match_percentage }}% match</span>
                    </div> --}}

                    <div class="ai-subject">{{ $item->subject->name }}</div>
                    <div class="ai-score">
                        @if($res)
                            Your current score: {{ $res->score }}%
                        @else
                            New subject for your learning style!
                        @endif
                    </div>

                    <div class="ai-tutor-row">
                        <span class="avatar-circle avatar-indigo">{{ $initials }}</span>
                        <div>
                            <div class="tutor-meta-label">Recommended tutor</div>
                            <div class="tutor-meta-name">{{ $item->tutor->name }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2">
                    <div class="empty-state">
                        No AI recommendations yet — try updating your profile or academic results!
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <hr class="section-divider">

    {{-- SECTION 2: ALL CLASSES --}}
    <div x-data="{ activeTab: 'junior' }">
        <div class="tab-bar-wrap">
            <div>
                <div class="section-label">Browse all</div>
                <div class="section-title mb-0">All available classes</div>
            </div>
            <div class="tab-bar">
                <button @click="activeTab = 'junior'"
                        :class="activeTab === 'junior' ? 'active' : ''"
                        class="tab-btn">F1 – F3</button>
                <button @click="activeTab = 'senior'"
                        :class="activeTab === 'senior' ? 'active' : ''"
                        class="tab-btn">F4 – F5</button>
            </div>
        </div>

        <div class="class-grid">
            @foreach($allClasses as $class)
                @php
                    $isJunior = in_array($class->category_code, ['form-1', 'form-2', 'form-3']);
                    $isSenior = in_array($class->category_code, ['form-4', 'form-5']);
                    $group = $isJunior ? 'junior' : ($isSenior ? 'senior' : 'other');
                    $initials = strtoupper(substr($class->tutor->name, 0, 2));
                    $formLabel = strtoupper(str_replace('-', ' ', $class->category_code));
                @endphp

                <div x-show="activeTab === '{{ $group }}'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    <div class="class-card">
                        <span class="badge-form">{{ $formLabel }}</span>
                        <div class="class-subject">{{ $class->subject->name }}</div>
                        <div class="class-lang">Language: {{ $class->language_code }}</div>
                        <div class="class-tutor-row">
                            <span class="avatar-circle avatar-amber">{{ $initials }}</span>
                            <span style="font-size:13px;font-weight:600;color:#444">{{ $class->tutor->name }}</span>
                        </div>
                        <a href="{{ route('enrollment.tutors', $class->id) }}" class="btn-select">
                            Select class
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert("{{ session('success') }}");
        });
    </script>
@endif
@endsection
