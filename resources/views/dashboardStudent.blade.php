@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page-header', 'Dashboard')

@section('js_after')
<script>
    function updateScheduleStatuses() {
        const now = new Date();
        const nowMins = now.getHours() * 60 + now.getMinutes();
        const clock = document.getElementById('live-clock');
        if (clock) clock.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        document.querySelectorAll('tr[data-start]').forEach(row => {
            const [sh, sm] = row.dataset.start.split(':').map(Number);
            const [eh, em] = row.dataset.end.split(':').map(Number);
            const startMins = sh * 60 + sm, endMins = eh * 60 + em;
            const badge = row.querySelector('.status-badge');
            const joinCell = row.querySelector('.join-cell');
            const zoom = row.dataset.zoom;
            if (!badge) return;

            let status, cls;
            if (nowMins < startMins)        { status = 'Upcoming';  cls = 'badge-light-warning'; }
            else if (nowMins <= endMins)    { status = 'Ongoing';   cls = 'badge-light-primary'; }
            else                            { status = 'Completed'; cls = 'badge-light-success'; }

            badge.textContent = status;
            badge.className = 'badge fw-bold status-badge ' + cls;

            if (joinCell) {
                if (status === 'Ongoing' && zoom) {
                    joinCell.innerHTML = `<a href="${zoom}" target="_blank" class="btn btn-sm btn-success">Join Now</a>`;
                } else if (status === 'Ongoing') {
                    joinCell.innerHTML = `<span class="text-muted fs-8">No link yet</span>`;
                } else {
                    joinCell.innerHTML = `<span class="text-muted fs-8">—</span>`;
                }
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        updateScheduleStatuses();
        setInterval(updateScheduleStatuses, 30000);
    });
</script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Stat cards --}}
    <div class="row g-5 g-xl-8 mb-5">
        @php
            $cards = [
                ['label' => 'Enrolled Classes', 'value' => $stats['enrolled_classes'], 'color' => 'primary'],
                ['label' => 'Quizzes Taken',    'value' => $stats['quizzes_taken'],    'color' => 'success'],
                ['label' => 'Avg Quiz Score',   'value' => $stats['avg_quiz'].'%',     'color' => 'info'],
                ['label' => 'Avg Evaluation',   'value' => $stats['avg_evaluation'].'/5', 'color' => 'warning'],
            ];
        @endphp
        @foreach($cards as $c)
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-100">
                <div class="card-body">
                    <div class="fs-2 fw-bold text-gray-900">{{ $c['value'] }}</div>
                    <div class="fs-7 text-muted fw-semibold">{{ $c['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Today's classes --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold text-dark">Today's Classes
                <span class="text-muted fw-semibold fs-7 ms-2">{{ now()->format('l, d M Y') }}</span>
            </h3>
            <div class="card-toolbar"><span class="badge badge-light fs-8" id="live-clock">--:--</span></div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-dashed align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th>Class</th><th>Time</th><th class="text-center">Status</th><th class="text-end">Join</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todaySchedule as $s)
                            <tr data-start="{{ \Carbon\Carbon::parse($s->schedule->start_time)->format('H:i') }}"
                                data-end="{{ \Carbon\Carbon::parse($s->schedule->end_time)->format('H:i') }}"
                                data-zoom="{{ $s->todayMaterial->webex_link ?? '' }}">
                                <td class="fw-bold text-gray-800">{{ $s->classModule->subject->name ?? 'Class' }}</td>
                                <td class="text-muted">
                                    {{ \Carbon\Carbon::parse($s->schedule->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($s->schedule->end_time)->format('h:i A') }}
                                </td>
                                <td class="text-center"><span class="badge fw-bold status-badge">—</span></td>
                                <td class="text-end"><span class="join-cell"></span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-6">No classes today</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row g-5">
        {{-- Approved makeups --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-900">Approved Makeup Sessions</h3>
                </div>
                <div class="card-body pt-3">
                    @forelse($approvedMakeups as $m)
                        <div class="border-bottom py-3">
                            <div class="fw-bold text-gray-800">{{ $m->classModule->subject->name ?? 'Class' }}</div>
                            <div class="fs-7 text-muted">
                                {{ \Carbon\Carbon::parse($m->start_time)->format('d M Y, h:i A') }}
                                @if($m->tutor) &middot; {{ $m->tutor->name }} @endif
                            </div>
                            @if($m->zoom_link)
                                <a href="{{ $m->zoom_link }}" target="_blank" class="btn btn-sm btn-light-success mt-2">Join Makeup</a>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted text-center py-6">No approved makeups</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Rejected requests --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-900">Rejected Reschedule Requests</h3>
                </div>
                <div class="card-body pt-3">
                    @forelse($rejectedRequests as $r)
                        <div class="border-bottom py-3">
                            <div class="fw-bold text-gray-800">{{ $r->classModule->subject->name ?? 'Class' }}</div>
                            <div class="fs-7 text-muted">
                                Requested: {{ \Carbon\Carbon::parse($r->proposed_time)->format('d M Y, h:i A') }}
                            </div>
                            <div class="fs-8 text-danger">Reason: {{ \Illuminate\Support\Str::limit($r->reason, 60) }}</div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-6">No rejected requests</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent quizzes --}}
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-900">Recent Quiz Results</h3>
                </div>
                <div class="card-body pt-3">
                    <table class="table align-middle table-row-dashed fs-6 gy-4">
                        <thead>
                            <tr class="text-muted fw-bold text-uppercase fs-7">
                                <th>Quiz</th><th>Score</th><th>Correct</th><th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentQuizzes as $q)
                                <tr>
                                    <td class="fw-bold text-gray-800">{{ $q->quiz->title ?? 'Quiz' }}</td>
                                    <td><span class="badge badge-light-primary">{{ $q->score }}%</span></td>
                                    <td>{{ $q->correct_answers }} / {{ $q->total_questions }}</td>
                                    <td class="text-muted">{{ $q->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-6">No quizzes taken yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
