@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-header', 'Dashboard')

@section('js_after')
<script>
    // Registration Bar Chart
    var regOptions = {
        series: [
            { name: 'Approved', data: {!! json_encode($approvedData) !!} },
            { name: 'Rejected', data: {!! json_encode($rejectedData) !!} }
        ],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 5 } },
        colors: ['#009EF7', '#F1416C'],
        xaxis: { categories: {!! json_encode($months) !!} },
        legend: { position: 'top' },
        dataLabels: { enabled: false },
    };
    new ApexCharts(document.querySelector("#kt_registration_chart"), regOptions).render();

    // Payment Area Chart
    var payOptions = {
        series: [{ name: 'Revenue (RM)', data: {!! json_encode($revenueData) !!} }],
        chart: { type: 'area', height: 350, toolbar: { show: false } },
        stroke: { curve: 'smooth' },
        colors: ['#50CD89'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } },
        xaxis: { categories: {!! json_encode($months) !!} },
        yaxis: { labels: { formatter: function (val) { return "RM " + val.toLocaleString(); } } },
        dataLabels: { enabled: false },
        tooltip: { y: { formatter: function(val) { return "RM " + val.toLocaleString(); } } }
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
                <div class="card-body d-flex flex-column justify-content-between p-6">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <span class="text-white opacity-75 fw-semibold fs-6">Registered Students</span>
                        <div class="symbol symbol-40px">
                            <div class="symbol-label bg-white bg-opacity-20">
                                <i class="ki-duotone ki-people fs-2x text-white">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                </i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-2hx fw-bold text-white">{{ number_format($stats['total_students']) }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 bg-warning">
                <div class="card-body d-flex flex-column justify-content-between p-6">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <span class="text-white opacity-75 fw-semibold fs-6">Active Tutors</span>
                        <div class="symbol symbol-40px">
                            <div class="symbol-label bg-white bg-opacity-20">
                                <i class="ki-duotone ki-teacher fs-2x text-white">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-2hx fw-bold text-white">{{ number_format($stats['total_tutors']) }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 bg-success">
                <div class="card-body d-flex flex-column justify-content-between p-6">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <span class="text-white opacity-75 fw-semibold fs-6">Monthly Revenue</span>
                        <div class="symbol symbol-40px">
                            <div class="symbol-label bg-white bg-opacity-20">
                                <i class="ki-duotone ki-dollar fs-2x text-white">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-2hx fw-bold text-white">RM {{ number_format($stats['revenue'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flush shadow-sm h-md-100 bg-info">
                <div class="card-body d-flex flex-column justify-content-between p-6">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <span class="text-white opacity-75 fw-semibold fs-6">Pending Approvals</span>
                        <div class="symbol symbol-40px">
                            <div class="symbol-label bg-white bg-opacity-20">
                                <i class="ki-duotone ki-time fs-2x text-white">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-2hx fw-bold text-white">{{ number_format($stats['pending_apps']) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Secondary Stats --}}
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-md-3">
            <div class="card card-flush shadow-sm">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-book-open fs-2x text-primary">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Total Classes</span>
                            <span class="text-gray-800 fw-bolder fs-3">{{ number_format($stats['total_classes']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flush shadow-sm">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-verify fs-2x text-success">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Total Enrollments</span>
                            <span class="text-gray-800 fw-bolder fs-3">{{ number_format($stats['total_enrollments']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flush shadow-sm">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-bill fs-2x text-warning">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Pending Payments</span>
                            <span class="text-gray-800 fw-bolder fs-3">{{ number_format($stats['pending_payments']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-flush shadow-sm">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-danger">
                                <i class="ki-duotone ki-cross-circle fs-2x text-danger">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Rejected Applications</span>
                            <span class="text-gray-800 fw-bolder fs-3">{{ number_format($stats['rejected_apps']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Charts --}}
    <div class="row g-5 g-xl-10 mb-10">
        <div class="col-xl-6">
            <div class="card card-flush shadow-sm h-md-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Application Status</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Last 6 months trend</span>
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

    {{-- Row 4: Popular Classes --}}
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark fs-3">Popular Subject Classes</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Top enrolled classes</span>
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
                            <th class="min-w-100px text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($popularClasses as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                                            {{ substr($item->class->subject->name ?? 'N', 0, 1) }}

                                        </div>
                                    </div> --}}
                                    <span class="text-dark fw-bold fs-6">
                                        {{ $item->class->subject->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted fw-semibold fs-6">
                                    {{ $item->class->tutor->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-dark fw-bold fs-6">{{ $item->total_students }}</span>
                                <span class="text-muted fs-8 d-block">enrolled</span>
                            </td>
                            <td class="text-end">
                                <span class="badge badge-light-success fs-7 fw-bold">Active</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-6">No classes found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
