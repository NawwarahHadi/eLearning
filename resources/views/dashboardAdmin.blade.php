@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-header', 'Dashboard')

@section('css_after')
@endsection

@section('js_after')
<script>
    // Registration Bar Chart
    var regOptions = {
        series: [{ name: 'Approved', data: [45, 52, 88, 95, 110, 145] }, { name: 'Rejected', data: [4, 8, 5, 12, 7, 10] }],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 5 } },
        colors: ['#009EF7', '#F1416C'],
        xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
    };
    new ApexCharts(document.querySelector("#kt_registration_chart"), regOptions).render();

    // Payment Area Chart
    var payOptions = {
        series: [{ name: 'Revenue', data: [12000, 14000, 13000, 17000, 15250, 22000] }],
        chart: { type: 'area', height: 350, toolbar: { show: false } },
        stroke: { curve: 'smooth' },
        colors: ['#50CD89'],
        xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
        yaxis: { labels: { formatter: function (val) { return "RM " + val; } } }
    };
    new ApexCharts(document.querySelector("#kt_payment_chart"), payOptions).render();
</script>
@endsection



@section('content')
<div class="container-xxl py-10" id="kt_content_container">

    {{-- Row 1: Statistics Cards --}}
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 bg-primary">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <span class="fs-2hx fw-bold text-white">{{ $stats['total_students'] }}</span>
                    <span class="text-white opacity-75 fw-semibold fs-6">Registered Students</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 bg-info">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <span class="fs-2hx fw-bold text-white">{{ $stats['pending_apps'] }}</span>
                    <span class="text-white opacity-75 fw-semibold fs-6">Pending Approval</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 bg-success">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <span class="fs-2hx fw-bold text-white">RM {{ number_format($stats['revenue'], 2) }}</span>
                    <span class="text-white opacity-75 fw-semibold fs-6">Monthly Revenue</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 bg-danger">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <span class="fs-2hx fw-bold text-white">{{ $stats['rejected_apps'] }}</span>
                    <span class="text-white opacity-75 fw-semibold fs-6">Rejected Applications</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Charts --}}
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-xl-6">
            <div class="card card-flush shadow-sm h-md-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Application Status</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Jan - Jun 2026 Trend</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="kt_registration_chart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card card-flush shadow-sm h-md-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Revenue Analytics</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Monthly fee collection</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="kt_payment_chart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Popular Classes List --}}
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark fs-3">Popular Subject Categories</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Top performing subjects this month</span>
            </h3>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Subject Name</th>
                            <th class="min-w-150px">Tutor</th>
                            <th class="min-w-150px">Total Students</th>
                            <th class="min-w-100px text-end">Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $classes = [
                                ['name' => 'Additional Mathematics', 'tutor' => 'Dr. Ramona', 'students' => 450, 'growth' => '+12%'],
                                ['name' => 'Physics Year 1', 'tutor' => 'Sir Wan Naira', 'students' => 380, 'growth' => '+8%'],
                                ['name' => 'Chemistry Form 5', 'tutor' => 'Madam Siti', 'students' => 310, 'growth' => '+15%'],
                                ['name' => 'English Proficiency', 'tutor' => 'Ms. Alya', 'students' => 290, 'growth' => '+5%'],
                            ];
                        @endphp
                        @foreach($classes as $c)
                        <tr>
                            <td><span class="text-dark fw-bold fs-6">{{ $c['name'] }}</span></td>
                            <td><span class="text-muted fw-semibold fs-6">{{ $c['tutor'] }}</span></td>
                            <td><span class="text-dark fw-bold fs-6">{{ $c['students'] }}</span></td>
                            <td class="text-end">
                                <span class="badge badge-light-success fs-7 fw-bold">{{ $c['growth'] }}</span>
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

