@extends('layouts.app')

@section('title', 'Class Management')
@section('page-header', 'Class')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).on('click', '.hapus-data', function(e) {
            e.preventDefault();
            let url = $(this).attr("href");
            Swal.fire({
                title: 'Warning',
                text: 'Click to proceed delete.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Proceed',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-danger",
                }
            }).then((result) => {
                if (result.value) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">List of Classes</h3>
            <div class="card-toolbar">
                <a href="{{ route('class.create') }}" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-plus-square fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Create Class
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Class Name</th>
                        <th class="min-w-100px">Category</th>
                        <th class="min-w-200px">Schedule</th>
                        <th class="min-w-150px">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($listClass as $item)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold fs-6">{{ $item->subject->name ?? 'N/A' }}</span>
                                    <span class="text-muted fs-7">{{ $item->language_code }}</span>
                                </div>
                            </td>

                            <td>
                                <span class="text-gray-700 fw-bold">{{ $item->category_code }}</span>
                            </td>

                            <td>
                                @foreach($item->schedules as $schedule)
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="ki-duotone ki-time fs-2 text-gray-400 me-2">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <span class="fs-7">
                                            <strong class="text-gray-800">{{ $schedule->day }}:</strong>
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        </span>
                                    </div>
                                @endforeach
                            </td>

                            <td>
                                {{-- <a href="{{ route('class.edit', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ki-duotone text-warning ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </a> --}}

                                <a href="{{ route('class.destroy', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm hapus-data me-1" data-bs-toggle="tooltip" title="Delete">
                                    <i class="ki-duotone text-danger ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </a>

                                <a href="{{ route('learning-material.index', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Materials">
                                    <i class="ki-duotone text-info ki-file-up fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </a>

                                <a href="{{ route('class.show', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm" data-bs-toggle="tooltip" title="View Students">
                                    <i class="ki-duotone ki-user ki-graph-up text-success fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <a href="{{ route('quiz.review.quizzes', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm" data-bs-toggle="tooltip" title="View Quiz Result">
                                    <i class="ki-duotone text-warning ki-award fs-2">
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
