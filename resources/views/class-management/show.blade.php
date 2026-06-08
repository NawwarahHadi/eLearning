@extends('layouts.app')

@section('title', 'Class Enrollment List')

@section('page-header', 'List Of Student')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>

@endsection



@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <h3 class="text-gray-900 fs-2 fw-bold me-1">{{ $class->subject->name }}</h3>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <span class="d-flex align-items-center text-gray-400 me-5 mb-2">
                                <i class="ki-duotone ki-address-book fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                {{ $class->category_code }}</span>
                                <span class="d-flex align-items-center text-gray-400 mb-2">
                                <i class="ki-duotone ki-price-tag fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                RM {{ number_format($class->fee, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Enrolled Students</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Total: {{ $class->students->count() }} / {{ $class->max_students }} students</span>
            </h3>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Student Name</th>
                            <th class="min-w-150px">Contact Info</th>
                            <th class="min-w-150px">Enrollment Date</th>
                            <th class="text-end">Evaluation</th>
                            {{-- <th class="text-end">Status</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <img src="{{ $student->avatar_url }}" class="avatar" alt="{{ $student->name }}"
                                            style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                        <div>
                                            <div class="fw-bold">{{ $student->name }}</div>
                                            <div class="text-muted fs-7">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                    {{-- <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-5">
                                            <span class="symbol-label bg-light-success text-success fw-bold">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bold text-hover-primary fs-6">{{ $student->name }}</span>
                                        </div>
                                    </div> --}}
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold d-block fs-7">{{ $student->phone ?? 'No Phone' }}</span>
                                </td>
                                <td>
                                    <span class="text-gray-600 fw-bold d-block fs-7">{{ $student->pivot->created_at->format('d M Y') }}</span>
                                </td>
                                {{-- <td class="text-end">
                                    <span class="badge badge-light-primary">Active Enrollment</span>
                                </td> --}}
                                <td class="text-end">
                                    <a href="{{route('evaluation.form', [$class->id, $student->id])}}"class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" title="Evalution Form">
                                        <i class="ki-duotone ki-message-edit text-warning fs-2x">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
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
