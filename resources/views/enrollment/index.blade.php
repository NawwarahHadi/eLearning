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
    .ai-reason {
        font-size: 12.5px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    .ai-reason strong { color: #4f46e5; }
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

    /* ── Status badges (kept for class form labels) ── */
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
<div id="enrollmentPage" class="container mt-5">

    {{-- SECTION 1: AI RECOMMENDATION (LEVEL-BASED) --}}
    <div class="mb-10">
        {{-- <div class="section-label">Personalized for you</div> --}}
        <div class="section-title">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <circle cx="8" cy="8" r="7" stroke="#4f46e5" stroke-width="1.5"/>
                <path d="M5 8.5l2 2 4-4" stroke="#4f46e5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Recommended Class for you
        </div>

        @php
            $levelLabel = ['low' => 'Foundational', 'medium' => 'Standard', 'good' => 'Advanced'];
        @endphp

        <div class="ai-grid">
            @forelse($recommended as $item)
                @php
                    $initials = strtoupper(substr($item->tutor->name ?? '??', 0, 2));
                    $levelText = $levelLabel[$item->level] ?? ucfirst($item->level);
                @endphp
                <div class="ai-card"
                     onclick="window.location.href='{{ route('enrollment.tutors', [$item->id, $item->tutor_id]) }}'">

                    <div class="ai-subject">{{ $item->subject->name }}</div>
                    <div class="ai-score">
                        @isset($item->student_score)
                            Your current score: {{ $item->student_score }}%
                        @else
                            New subject for your learning style!
                        @endisset
                    </div>

                    <div class="ai-reason">
                        Based on your result, we recommend a
                        <strong>{{ $levelText }}</strong> class
                        @isset($item->student_score)
                            to suit your current level.
                        @else
                            to get you started.
                        @endisset
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
                        No recommendations yet — try updating your profile or academic results!
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
