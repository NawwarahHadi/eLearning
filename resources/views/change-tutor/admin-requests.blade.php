@extends('layouts.app')

@section('title', 'Tutor Change Requests')
@section('page-header', 'Tutor Change Requests')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        function confirmAction(formId, title, text, icon) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: icon === 'success' ? 'btn btn-success' : 'btn btn-warning',
                    cancelButton: 'btn btn-light'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card card-flush">
        <div class="card-header pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">Pending Tutor Change Requests</h3>
            </div>
        </div>

        <div class="card-body py-4">
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Student</th>
                        <th>Requested Tutor</th>
                        <th>Subject</th>
                        <th>Requested Slots</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($pendingEnrollments as $key => $group)
                        @php
                            $first   = $group->first();
                            $student = $first->student;
                            $tutor   = $first->tutor;
                            $class   = $first->class;
                        @endphp
                        <tr>
                            {{-- Student --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-7">{{ $student->name }}</span>
                                        <span class="text-muted fs-8">{{ $student->email }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Requested Tutor --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40px me-3">
                                        @if($tutor->tutorProfile?->profile_photo)
                                            <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}"
                                                 class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                        @else
                                            <div class="symbol-label bg-light-success text-success fw-bold fs-6">
                                                {{ substr($tutor->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-7">{{ $tutor->name }}</span>
                                        <span class="text-muted fs-8">{{ $tutor->email }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Subject --}}
                            <td>
                                <span class="badge badge-light-info fs-8">{{ $class->subject->name }}</span>
                                <span class="text-muted fs-8 d-block mt-1">{{ $class->category_code }}</span>
                            </td>

                            {{-- Requested Slots --}}
                            <td>
                                @foreach($group as $enrollment)
                                    @if($enrollment->schedule)
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="ki-duotone ki-time fs-5 text-primary me-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <span class="fs-8 text-gray-700">
                                            <strong>{{ $enrollment->schedule->day }}:</strong>
                                            {{ \Carbon\Carbon::parse($enrollment->schedule->start_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($enrollment->schedule->end_time)->format('h:i A') }}
                                        </span>
                                    </div>
                                    @endif
                                @endforeach
                            </td>

                            {{-- Actions --}}
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    {{-- Approve --}}
                                    <button type="button"
                                            class="btn btn-success btn-sm fw-bold"
                                            onclick="confirmAction('approveForm-{{ $student->id }}-{{ $class->id }}', 'Approve Change?', 'Approve {{ $student->name }} to change to {{ $tutor->name }}?', 'success')">
                                        <i class="ki-duotone ki-check fs-3 me-1">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Approve
                                    </button>

                                    {{-- Reject --}}
                                    <button type="button"
                                            class="btn btn-light-danger btn-sm fw-bold"
                                            onclick="confirmAction('rejectForm-{{ $student->id }}-{{ $class->id }}', 'Reject Request?', 'Reject this tutor change request?', 'warning')">
                                        <i class="ki-duotone ki-cross fs-3 me-1">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        Reject
                                    </button>
                                </div>

                                {{-- Hidden Forms --}}
                                <form id="approveForm-{{ $student->id }}-{{ $class->id }}"
                                    action="{{ route('change-tutor.approveChange', ['student_id' => $student->id, 'class_id' => $class->id]) }}"
                                    method="POST" class="d-none">
                                    @csrf
                                </form>
                                <form id="rejectForm-{{ $student->id }}-{{ $class->id }}"
                                    action="{{ route('change-tutor.rejectChange', ['student_id' => $student->id, 'class_id' => $class->id]) }}"
                                    method="POST" class="d-none">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
