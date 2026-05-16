@extends('layouts.app')

@section('title', 'List of Class')

@section('page-header', 'My Enrolled Classes')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">My Classes</h3>
            </div>
        </div>

        <div class="card-body py-4">
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Class</th>
                        <th class="min-w-150px"> Tutor</th>
                        <th class="min-w-200px">Schedule</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-150px">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($enrolledClasses as $classId => $group)
                        @php
                            // Get the first record of the group to extract common details like Tutor and Subject
                            $firstItem = $group->first();
                        @endphp
                        <tr>
                            {{-- CLASS NAME --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold fs-6">{{ $firstItem->class->subject->name }}</span>
                                    <span class="text-muted fs-7">{{ $firstItem->class->category_code }}</span>
                                </div>
                            </td>

                            {{-- TUTOR NAME --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column">
                                        {{-- Use 'nama_penuh' as per your User model --}}
                                        <span class="text-gray-800 fw-bold fs-7">{{ $firstItem->tutor->name }}</span>
                                        <span class="text-muted fs-8">{{ $firstItem->tutor->email }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- SELECTED SCHEDULES (Picked by Student) --}}
                            <td>
                                @foreach($group as $enrollment)
                                    @if($enrollment->schedule)
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="ki-duotone ki-time fs-2 text-primary me-2">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            <span class="fs-7">
                                                <strong class="text-gray-800">{{ $enrollment->schedule->day }}:</strong>
                                                {{ \Carbon\Carbon::parse($enrollment->schedule->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($enrollment->schedule->end_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge badge-light-success fs-7 fw-bold">Enrolled</span>
                            </td>

                            {{-- ACTIONS --}}
                            <td>
                                <a href="{{ route('enrollment.changeTutor', $firstItem->class_id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Change Tutor">
                                    <i class="ki-duotone ki-update-file text-info fs-1">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                    </i>
                                </a>

                                <a href="{{ route('student.class.materials', $firstItem->class_id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Learning Material">
                                    <i class="ki-duotone ki-some-files text-success fs-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </a>

                                <a href="{{ route ('feedback.index', $firstItem->class_id)}}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Rate">
                                    <i class="ki-duotone ki-dots-circle text-warning fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </a>

                                <a href="{{ route('chat.show', $firstItem->tutor_id) }}"
                                    class="btn btn-icon btn-bg-light btn-active-color-success btn-sm"
                                    data-bs-toggle="tooltip"
                                    title="Chat with {{ $firstItem->tutor->name }}">
                                        <i class="ki-duotone ki-message-text-2 fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
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
@endsection
