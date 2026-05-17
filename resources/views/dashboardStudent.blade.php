@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header', 'Dashboard')

@section('css_after')
@endsection

@section('js_after')
<script>
    // Subject Performance (Radar Chart for Students)
    var subjectOptions = {
        series: [{
            name: 'Score %',
            data: [85, 90, 78, 92, 88],
        }],
        chart: {
            height: 350,
            type: 'radar',
            toolbar: { show: false }
        },
        dataLabels: { enabled: true },
        plotOptions: {
            radar: {
                size: 140,
                polygons: {
                    strokeColors: '#e9e9e9',
                    fill: { colors: ['#f8f8f8', '#fff'] }
                }
            }
        },
        colors: ['#009EF7'],
        xaxis: {
            categories: ['Math', 'Physics', 'Chemistry', 'Biology', 'English']
        }
    };

    new ApexCharts(document.querySelector("#kt_student_subject_chart"), subjectOptions).render();
</script>
@endsection

@section('content')
<div class="container-xxl py-10" id="kt_content_container">

    {{-- Row 1: Student Welcome & Quick Stats --}}
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-md-4">
            <div class="card shadow-sm h-md-100 bg-light-primary border-0">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h3 class="text-primary fw-bolder mb-2">Welcome Back, Siti!</h3>
                    <p class="text-gray-600 fw-semibold">You have 2 classes scheduled for today. Stay focused!</p>
                    <a href="#" class="btn btn-primary btn-sm w-fit mt-3">View My Schedule</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row g-5">
                <div class="col-sm-4">
                    <div class="card shadow-sm h-md-100 border-0">
                        <div class="card-body text-center">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Enrolled Classes</span>
                            <span class="fs-2x fw-bold text-dark">{{ $stats['enrolled_classes'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-sm h-md-100 border-0">
                        <div class="card-body text-center">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">Attendance</span>
                            <span class="fs-2x fw-bold text-success">{{ $stats['attendance_percentage'] }}%</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card shadow-sm h-md-100 border-0">
                        <div class="card-body text-center">
                            <span class="fs-4 fw-semibold text-gray-400 d-block">GPA / CGPA</span>
                            <span class="fs-2x fw-bold text-info">{{ $stats['current_gpa'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {{-- 🌟 Approved Makeup Classes Row Panel Section --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-flush shadow-sm">
            <div class="card-header pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-warning">🔄 Approved Makeup & Rescheduled Sessions</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Your dynamic private 1-to-1 makeup slots</span>
                </h3>
            </div>
            <div class="card-body pt-0">
                @if($approvedMakeups->isEmpty())
                    <p class="text-muted mb-0 small">No upcoming temporary makeup classes scheduled at this time.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 mb-0">
                            <thead>
                                <tr class="fw-bolder text-muted fs-7 text-uppercase">
                                    <th class="min-w-150px">Subject / Module</th>
                                    <th class="min-w-125px">Instructor</th>
                                    <th class="min-w-150px">📅 Makeup Date & Time</th>
                                    <th class="min-w-150px">Reason</th>
                                    <th class="min-w-120px text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approvedMakeups as $makeup)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong class="text-dark fs-6">
                                                    {{ $makeup->classModule->subject->name ?? 'Course Module' }}
                                                </strong>
                                                <span class="text-muted fs-7">Code: {{ $makeup->classModule->class_code ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-gray-800 fw-bold fs-6">
                                            {{ $makeup->tutor->name ?? 'Assigned Tutor' }}
                                        </td>
                                        <td>
                                            <span class="text-success fw-bolder fs-6">
                                                {{ \Carbon\Carbon::parse($makeup->start_time)->format('d M Y, h:i A') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-7 text-italic text-truncate d-block" style="max-width: 200px;" title="{{ $makeup->reschedule_reason }}">
                                                "{{ $makeup->reschedule_reason }}"
                                            </span>
                                        </td>
                                        {{-- 🌟 AUTOMATED TIME-LOCK LINK ACTION BLOCK --}}
                                        <td class="text-end">
                                            @if($makeup->zoom_link)
                                                @php
                                                    $classDate = \Carbon\Carbon::parse($makeup->start_time)->startOfDay();
                                                    $today = \Carbon\Carbon::now()->startOfDay();
                                                    $isAvailable = $today->greaterThanOrEqualTo($classDate);
                                                @endphp

                                                @if($isAvailable)
                                                    {{-- 🟢 Clickable button when class day arrives --}}
                                                    <a href="{{ $makeup->zoom_link }}" target="_blank" class="btn btn-sm btn-success fw-bold text-uppercase px-4">
                                                        Join Class
                                                    </a>
                                                @else
                                                    {{-- ⚪ Greyed out button until the scheduled class day --}}
                                                    <button type="button" class="btn btn-sm btn-secondary fw-bold text-uppercase px-4" disabled title="This link unlocks on {{ \Carbon\Carbon::parse($makeup->start_time)->format('d M Y') }}">
                                                        Locked Until {{ \Carbon\Carbon::parse($makeup->start_time)->format('d M') }}
                                                    </button>
                                                @endif
                                            @else
                                                <span class="badge bg-light text-muted fs-7">Link Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

    {{-- Row 3: Progress Chart & Assignments --}}
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-xl-8">
            <div class="card card-flush shadow-sm h-md-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Subject Performance</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Your quiz scores comparison</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="kt_student_subject_chart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-flush shadow-sm h-md-100">
                <div class="card-header pt-5">
                    <h3 class="card-title fw-bold text-dark">Recent Materials</h3>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-danger"><i class="ki-duotone ki-file text-danger fs-2x"><span class="path1"></span><span class="path2"></span></i></span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Calculus_Notes.pdf</a>
                            <span class="text-muted d-block fw-semibold">Math • 2 hours ago</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-info"><i class="ki-duotone ki-video text-info fs-2x"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Newtonian_Physics.mp4</a>
                            <span class="text-muted d-block fw-semibold">Physics • 1 day ago</span>
                        </div>
                    </div>
                    <a href="#" class="btn btn-light-primary btn-sm w-100 mt-5">Browse All Materials</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


