@extends('layouts.app')

@section('title', 'Enrollment Application')
@section('page-header', 'Class Enrollment Management')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).on('click', '.action-button', function(e) {
            e.preventDefault();
            let action = $(this).data('action'); // Example: 'approve' or 'reject'
            let url = $(this).attr("href");

            Swal.fire({
                title: 'Warning!',
                text: 'Click Proceed to ' + action + ' this registration application.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Proceed',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-danger",
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // This part creates a hidden form to send a secure POST request
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header card-header-stretch">
            <div class="card-title">
                <h3 class="fw-bold m-0 text-gray-800">Enrollment Applications</h3>
            </div>
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == null || request('tab') == 'new' ? 'active' : '' }}"
                           href="{{ route('enrollment.admin.index', ['tab' => 'new']) }}">New</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'Approve' ? 'active' : '' }}"
                           href="{{ route('enrollment.admin.index', ['tab' => 'Approve']) }}">Approved</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') == 'reject' ? 'active' : '' }}"
                           href="{{ route('enrollment.admin.index', ['tab' => 'reject']) }}">Rejected</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div id="new" class="tab-pane fade {{ request('tab') == null || request('tab') == 'new' ? 'show active' : '' }}" role="tabpanel" aria-labelledby="new_tab">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5 w-auto mw-100">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>Student</th>
                                <th >Subject & Tutor</th>
                                <th >Schedule</th>
                                <th >Status</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">{{ $enrollment->student->nama_penuh }}</span>
                                            <span class="text-muted fs-7">{{ $enrollment->student->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold">{{ $enrollment->class->subject->name }}</span>
                                            <span class="text-muted fs-7">Tutor: {{ $enrollment->tutor->nama_penuh }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($enrollment->class->schedules as $schedule)
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="fs-7">
                                                    <strong class="text-gray-800">{{ substr($schedule->day, 0, 3) }}:</strong>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:iA') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:iA') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($enrollment->status == 'approved')
                                            <span class="badge badge-light-success">Approved</span>
                                        @elseif($enrollment->status == 'rejected')
                                            <span class="badge badge-light-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-light-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($enrollment->status == 'pending')
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('enrollment.admin.approve', $enrollment->id) }}"
                                                   class="btn btn-icon btn-bg-light btn-active-color-success btn-sm action-button"
                                                   data-action="meluluskan" title="Approve">
                                                    <i class="ki-duotone text-success ki-check-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                </a>
                                                <a href="{{ route('enrollment.admin.reject', $enrollment->id) }}"
                                                   class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm action-button"
                                                   data-action="menolak" title="Reject">
                                                    <i class="ki-duotone text-danger ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted fs-8 italic">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="Approve" class="tab-pane fade {{ request('tab') == 'Approve' ? 'show active' : '' }}" role="tabpanel" aria-labelledby="Approve_tab">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5 w-auto mw-100">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>Student</th>
                                <th >Subject & Tutor</th>
                                <th >Schedule</th>
                                <th >Status</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">{{ $enrollment->student->nama_penuh }}</span>
                                            <span class="text-muted fs-7">{{ $enrollment->student->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold">{{ $enrollment->class->subject->name }}</span>
                                            <span class="text-muted fs-7">Tutor: {{ $enrollment->tutor->nama_penuh }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($enrollment->class->schedules as $schedule)
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="fs-7">
                                                    <strong class="text-gray-800">{{ substr($schedule->day, 0, 3) }}:</strong>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:iA') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:iA') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($enrollment->status == 'approved')
                                            <span class="badge badge-light-success">Approved</span>
                                        @elseif($enrollment->status == 'rejected')
                                            <span class="badge badge-light-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-light-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('enrollment.admin.reject', $enrollment->id) }}"
                                               class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm action-button"
                                               data-action="menolak" title="Reject">
                                                <i class="ki-duotone text-danger ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="reject" class="tab-pane fade {{ request('tab') == 'reject' ? 'show active' : '' }}" role="tabpanel" aria-labelledby="reject_tab">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5 w-auto mw-100">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>Student</th>
                                <th >Subject & Tutor</th>
                                <th >Schedule</th>
                                <th >Status</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">{{ $enrollment->student->nama_penuh }}</span>
                                            <span class="text-muted fs-7">{{ $enrollment->student->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold">{{ $enrollment->class->subject->name }}</span>
                                            <span class="text-muted fs-7">Tutor: {{ $enrollment->tutor->nama_penuh }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($enrollment->class->schedules as $schedule)
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="fs-7">
                                                    <strong class="text-gray-800">{{ substr($schedule->day, 0, 3) }}:</strong>
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:iA') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:iA') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($enrollment->status == 'approved')
                                            <span class="badge badge-light-success">Approved</span>
                                        @elseif($enrollment->status == 'rejected')
                                            <span class="badge badge-light-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-light-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('enrollment.admin.approve', $enrollment->id) }}"
                                                class="btn btn-icon btn-bg-light btn-active-color-success btn-sm action-button"
                                                data-action="meluluskan" title="Approve">
                                                <i class="ki-duotone text-success ki-check-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                            </a>
                                        </div>
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
