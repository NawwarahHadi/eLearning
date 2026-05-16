@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header', 'Dashboard')

@section('css_after')
@endsection

@section('js_after')
<script>
    // Performance Chart (Line Chart)
    var performanceOptions = {
        series: [{ name: 'Avg Quiz Score', data: [75, 78, 82, 80, 85, 90] }],
        chart: { type: 'line', height: 350, toolbar: { show: false } },
        stroke: { curve: 'smooth', width: 4 },
        colors: ['#009EF7'],
        xaxis: { categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'] },
        markers: { size: 5 }
    };
    new ApexCharts(document.querySelector("#kt_tutor_performance_chart"), performanceOptions).render();

    // Feedback Pie Chart (Hardcoded Rating Distribution)
    var feedbackOptions = {
        series: [65, 20, 10, 3, 2], // Representing 5 stars, 4 stars, etc.
        chart: { type: 'donut', height: 300 },
        labels: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
        colors: ['#50CD89', '#009EF7', '#FFC700', '#F1416C', '#7239EA'],
        legend: { position: 'bottom' }
    };
    new ApexCharts(document.querySelector("#kt_tutor_feedback_pie"), feedbackOptions).render();
</script>
@endsection

@section('content')
<div class="container-xxl py-10" id="kt_content_container">

    {{-- Row 1: Tutor Performance Widgets --}}
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 border-start border-primary border-4">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="fs-4 fw-semibold text-gray-400 d-block">Active Classes</span>
                    <span class="fs-2hx fw-bold text-dark">{{ $stats['total_classes'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 border-start border-info border-4">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="fs-4 fw-semibold text-gray-400 d-block">Total Students</span>
                    <span class="fs-2hx fw-bold text-dark">{{ $stats['total_students'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 border-start border-warning border-4">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="fs-4 fw-semibold text-gray-400 d-block">Avg Rating</span>
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-dark me-2">{{ $stats['avg_rating'] }}</span>
                        <i class="ki-duotone ki-star text-warning fs-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 border-start border-success border-4">
                <div class="card-body d-flex flex-column justify-content-center">
                    <span class="fs-4 fw-semibold text-gray-400 d-block">Attendance Rate</span>
                    <span class="fs-2hx fw-bold text-dark">{{ $stats['attendance_rate'] }}%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Charts --}}
    <div class="row g-5 g-xl-10 mb-10">
        {{-- Student Progress Chart --}}
        <div class="col-xl-8">
            <div class="card card-flush shadow-sm h-md-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Student Grade Trends</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Average scores across your classes</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="kt_tutor_performance_chart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        {{-- Feedback Breakdown --}}
        <div class="col-xl-4">
            <div class="card card-flush shadow-sm h-md-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Rating Distribution</span>
                    </h3>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div id="kt_tutor_feedback_pie" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Class Schedule Table --}}
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold text-dark">Today's Class Schedule</h3>
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
                        @php
                            $schedule = [
                                ['name' => 'Additional Mathematics', 'time' => '08:00 AM', 'status' => 'Completed'],
                                ['name' => 'Physics Year 1', 'time' => '10:30 AM', 'status' => 'Ongoing'],
                                ['name' => 'Chemistry Form 5', 'time' => '02:00 PM', 'status' => 'Upcoming'],
                            ];
                        @endphp
                        @foreach($schedule as $s)
                        <tr>
                            <td><span class="text-dark fw-bold fs-6">{{ $s['name'] }}</span></td>
                            <td><span class="text-muted fw-semibold">{{ $s['time'] }}</span></td>
                            <td class="text-center">
                                <span class="badge badge-light-{{ $s['status'] == 'Completed' ? 'success' : ($s['status'] == 'Ongoing' ? 'primary' : 'warning') }} fw-bold">
                                    {{ $s['status'] }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                    <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

