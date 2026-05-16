@extends('layouts.app')

@section('title', 'Application')

@section('page-header', 'Student Application')

{{-- @section('breadcrumbs', Breadcrumbs::render('permohonan-peserta')) --}}

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    {{-- <script src="{{ asset('metronic/js/button_loading.js') }}"></script> --}}
    <script>
        $(document).on('click', '.batal-button', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Warning!',
                text: 'Click to proceed reject the request.',
                icon: 'warning',
                confirmButtonText: 'Proceed',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-danger",
                }
            }).then((result) => {
                if (result.value) {
                    window.location.href = $(this).attr("href");
                }
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    {{-- <div class="card shadow-sm mb-5">
        <div class="card-header">
            <div class="card-title d-flex align-items-center">
                <h3 class="fw-bold m-0 text-gray-800"></h3>
            </div>
            <div class="card-toolbar">
                <div class="box-tools pull-right">
                    <h3 class="mt-1 fw-semibold fs-6"></h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="" method="post">
                @csrf
                <select name="" id="" class="form-select form-select-solid" data-control="select2" data-placeholder="" data-allow-clear="true"multiple="multiple" style="width: 100%;"></select>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-success button-loading">
                        <i class="ki-duotone ki-send"><span class="path1"></span><span class="path2"></span></i>
                        <span class="indicator-label">Add Applicant</span>
                        <span class="indicator-progress">Loading <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div> --}}
    <div class="card">
        <div class="card-header card-header-stretch">
            <div class="card-title d-flex align-items-center">
                <h3 class="fw-bold m-0 text-gray-800">List Student Application</h3>
            </div>
            <div class="card-toolbar m-0">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold">
                    <li class="nav-item">
                        <a id="new_tab" class="nav-link {{ request('tab') == null || request('tab') == 'new' ? 'active' : '' }}" href="{{ route('application.index', ['tab' => 'new'])}}">New</a>
                    </li>
                    <li class="nav-item">
                        <a id="Approve_tab" class="nav-link {{ request('tab') == 'Approve' ? 'active' : '' }}" href="{{ route('application.index', ['tab' => 'Approve'])}} ">Approve</a>
                    </li>
                    <li class="nav-item">
                        <a id="reject_tab" class="nav-link {{ request('tab') == 'reject' ? 'active' : '' }}" href="{{ route('application.index', ['tab' => 'reject'])}} ">Reject</a>
                    </li>
                </ul>

            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div id="new" class="tab-pane fade {{ request('tab') == null || request('tab') == 'new' ? 'show active' : '' }}" role="tabpanel" aria-labelledby="new_tab">

                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Result</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($students as $student)
                                <tr>
                                    <td style="vertical-align: middle;">{{ $student->nama_penuh }}</td>
                                    <td style="vertical-align: middle;">{{ $student->email}}</td>
                                    <td style="vertical-align: middle;"> @if($student->results && $student->results->count() > 0)
                                            @foreach($student->results as $result)
                                                <strong>{{ $result->subject->name ?? '-' }}</strong>: {{ $result->score }}<br>
                                            @endforeach
                                            @else
                                                -
                                             @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('application.student.approve', $student->id) }}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Approve">
                                            <i class="ki-duotone text-success ki-check-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </a>
                                        <a href="{{ route('application.student.reject', $student->id) }}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Reject">
                                            <i class="ki-duotone text-danger ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="Approve" class="tab-pane fade {{ request('tab') == 'Approve' ? 'show active' : '' }}" role="tabpanel" aria-labelledby="Approve_tab">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-">
                                <th class="min-w-200px">Name</th>
                                <th>Email</th>
                                <th class="min-w-100px">Results</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($students as $student)
                                <tr>
                                    <td style="vertical-align: middle;">{{ $student->nama_penuh }}</td>
                                    <td style="vertical-align: middle;">{{ $student->email}}</td>
                                    <td style="vertical-align: middle;"> @if($student->results && $student->results->count() > 0)
                                            @foreach($student->results as $result)
                                                <strong>{{ $result->subject->name ?? '-' }}</strong>: {{ $result->score }}<br>
                                            @endforeach
                                            @else
                                                -
                                             @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $student->status }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('application.student.reject', $student->id) }}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Reject">
                                            <i class="ki-duotone text-danger ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="reject" class="tab-pane fade {{ request('tab') == 'reject' ? 'show active' : '' }}" role="tabpanel" aria-labelledby="reject_tab">
                   <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-">
                                <th >Name</th>
                                <th>Email</th>
                                <th >Results</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($students as $student)
                                <tr>
                                    <td style="vertical-align: middle;">{{ $student->nama_penuh }}</td>
                                    <td style="vertical-align: middle;">{{ $student->email}}</td>
                                    <td style="vertical-align: middle;"> @if($student->results && $student->results->count() > 0)
                                            @foreach($student->results as $result)
                                                <strong>{{ $result->subject->name ?? '-' }}</strong>: {{ $result->score }}<br>
                                            @endforeach
                                            @else
                                                -
                                             @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $student->status }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('application.student.reject', $student->id) }}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Reject">
                                            <i class="ki-duotone text-danger ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
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
</div>
@endsection
