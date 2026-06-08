@extends('layouts.app')

@section('title', 'Tutor Dashboard')
@section('page-header', 'Dashboard')

@section('js_after')
<script>
    function updateScheduleStatuses() {
        const now = new Date();
        const nowMins = now.getHours() * 60 + now.getMinutes();

        const clock = document.getElementById('live-clock');
        if (clock) {
            clock.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        document.querySelectorAll('tr[data-start]').forEach(row => {
            const [sh, sm] = row.dataset.start.split(':').map(Number);
            const [eh, em] = row.dataset.end.split(':').map(Number);
            const startMins = sh * 60 + sm;
            const endMins   = eh * 60 + em;

            const badge = row.querySelector('.status-badge');
            if (!badge) return;

            let status, cls;
            if (nowMins < startMins) {
                status = 'Upcoming';  cls = 'badge-light-warning';
            } else if (nowMins >= startMins && nowMins <= endMins) {
                status = 'Ongoing';   cls = 'badge-light-primary';
            } else {
                status = 'Completed'; cls = 'badge-light-success';
            }

            badge.textContent = status;
            badge.className = 'badge fw-bold status-badge ' + cls;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateScheduleStatuses();
        setInterval(updateScheduleStatuses, 30000); // refresh every 30s
    });
</script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Stat cards --}}
    <div class="row g-5 g-xl-8 mb-5">
        @php
            $cards = [
                ['label' => 'My Classes',       'value' => $stats['total_classes'],   'icon' => 'ki-book',                 'color' => 'primary'],
                ['label' => 'Total Students',   'value' => $stats['total_students'],  'icon' => 'ki-people',               'color' => 'success'],
                ['label' => 'Avg Rating',       'value' => $stats['avg_rating'].' ★', 'icon' => 'ki-star',                 'color' => 'warning'],
                ['label' => 'Quizzes Created',  'value' => $stats['total_quizzes'],   'icon' => 'ki-questionnaire-tablet', 'color' => 'info'],
            ];
        @endphp
        @foreach($cards as $c)
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <span class="symbol symbol-50px me-4">
                        <span class="symbol-label bg-light-{{ $c['color'] }}">
                            <i class="ki-duotone {{ $c['icon'] }} fs-1 text-{{ $c['color'] }}">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </span>
                    </span>
                    <div>
                        <div class="fs-2 fw-bold text-gray-900">{{ $c['value'] }}</div>
                        <div class="fs-7 text-muted fw-semibold">{{ $c['label'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Today's Class Schedule (auto-updating) --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold text-dark">Today's Class Schedule
                <span class="text-muted fw-semibold fs-7 ms-2">{{ now()->format('l, d M Y') }}</span>
            </h3>
            <div class="card-toolbar">
                <span class="badge badge-light-primary fs-8" id="live-clock">--:--</span>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-dashed align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-150px">Class Name</th>
                            <th class="min-w-100px">Time</th>
                            <th class="min-w-100px text-center">Status</th>
                            <th class="min-w-100px text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todaySchedule as $s)
                            <tr data-start="{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}"
                                data-end="{{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}">
                                <td><span class="text-dark fw-bold fs-6">{{ $s->classModule->subject->name ?? 'Class' }}</span></td>
                                <td>
                                    <span class="text-muted fw-semibold">
                                        {{ \Carbon\Carbon::parse($s->start_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($s->end_time)->format('h:i A') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge fw-bold status-badge">—</span>
                                </td>
                                <td class="text-end">
                                    @if($s->todayMaterial && $s->todayMaterial->webex_link)
                                        <a href="{{ $s->todayMaterial->webex_link }}" target="_blank"
                                           class="btn btn-sm btn-success">
                                            <i class="ki-duotone ki-entrance-right fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                                            Join Zoom
                                        </a>
                                    @else
                                        <span class="text-muted fs-8">No link yet</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-6">No classes scheduled for today</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row g-5">
        {{-- Pending reschedule requests --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-900">Pending Reschedule Requests</h3>
                    @if($pendingReschedules->count())
                        <div class="card-toolbar">
                            <span class="badge badge-light-danger">{{ $pendingReschedules->count() }} pending</span>
                        </div>
                    @endif
                </div>
                <div class="card-body pt-3">
                    @forelse($pendingReschedules as $r)
                        <div class="d-flex align-items-center border-bottom py-3">
                            <div class="flex-grow-1">
                                <div class="fw-bold text-gray-800">{{ $r->student->name ?? 'Student' }}</div>
                                <div class="fs-7 text-muted">
                                    {{ $r->classModule->subject->name ?? 'Class' }} &middot;
                                    {{ \Carbon\Carbon::parse($r->proposed_time)->format('d M, h:i A') }}
                                </div>
                                <div class="fs-8 text-gray-500">Reason: {{ \Illuminate\Support\Str::limit($r->reason, 50) }}</div>
                            </div>
                            <a href="{{ route('chat.show', $r->student_id) }}" class="btn btn-sm btn-light-primary">Review</a>
                        </div>
                    @empty
                        <div class="text-muted text-center py-6">No pending requests 🎉</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent feedback --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-900">Recent Student Feedback</h3>
                </div>
                <div class="card-body pt-3">
                    @forelse($recentFeedback as $f)
                        <div class="border-bottom py-3">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-gray-800">{{ $f->student->name ?? 'Student' }}</span>
                                <span class="text-warning fw-bold">{{ str_repeat('★', $f->rating) }}{{ str_repeat('☆', 5 - $f->rating) }}</span>
                            </div>
                            <div class="fs-7 text-muted">{{ $f->class->subject->name ?? '' }}</div>
                            @if($f->comment)
                                <div class="fs-7 text-gray-600 mt-1">"{{ \Illuminate\Support\Str::limit($f->comment, 80) }}"</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted text-center py-6">No feedback yet</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- My classes --}}
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bold text-gray-900">My Classes</h3>
                    <div class="card-toolbar">
                        <span class="text-muted fs-7">Avg evaluation score given: <strong>{{ $avgEvaluation }}/5</strong></span>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4">
                            <thead>
                                <tr class="text-muted fw-bold text-uppercase fs-7">
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Students</th>
                                    <th>Hours/Week</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myClasses as $cls)
                                    <tr>
                                        <td class="fw-bold text-gray-800">{{ $cls->subject->name ?? 'N/A' }}</td>
                                        <td>{{ $cls->category_code }}</td>
                                        <td><span class="badge badge-light-primary">{{ $cls->students_count }} / {{ $cls->max_students }}</span></td>
                                        <td>{{ $cls->hours_per_week }}h</td>
                                        <td class="text-end">
                                            <a href="{{ route('class.show', $cls->id) }}" class="btn btn-sm btn-light">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-6">No classes created yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
