@extends('layouts.app')

@section('title', 'Learning Materials')

@section('page-header', 'Learning Materials')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>

    <script>

        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();
            let url = $(this).attr("href");

            Swal.fire({
                title: 'Are you sure?',
                text: 'File will be deleted',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'De!ete',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-light",
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">Learning Materials List</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('learning-material.create', $class_id) }}" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-plus-square fs-2">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    Add Material
                </a>
            </div>
        </div>

        <div class="card-body py-4">
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-70px">Week</th>
                        <th class="min-w-100px">Date</th>
                        <th class="min-w-120px">Online Class</th>
                        {{-- KOLUM ASING --}}
                        <th class="min-w-120px">Lecture Notes</th>
                        <th class="min-w-120px">Exercises</th>
                        <th class="min-w-250px">Recording</th>
                        <th class="min-w-120px">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($listMaterials as $item)
                        <tr>
                            <td>
                                <span class="badge badge-light-dark fs-7 fw-bold">{{ $item->week }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->class_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->webex_link)
                                    <div class="d-flex flex-column">
                                       <a href="{{ $item->webex_link }}" target="_blank" class="text-primary fw-bold text-hover-primary mb-1">
                                            <i class="ki-duotone ki-video fs-4 me-1 text-primary"><span class="path1"></span><span class="path2"></span></i> Join
                                        </a>
                                        <span class="text-muted fs-8">Code: {{ $item->webex_meeting_code ?? '-' }}</span>
                                    </div>
                                @else
                                    <span class="badge badge-light-secondary fs-8">Physical</span>
                                @endif
                            </td>

                            {{-- KOLUM LECTURE NOTE --}}
                            <td>
                                @if($item->lecture_note)
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-file-up fs-2x text-primary me-3">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('learning-material.download', [$item->id, 'note']) }}" class="text-primary fw-bold fs-6 text-hover-primary">
                                                Topic: {{ $item->topic ?? 'Lecture Note' }}
                                            </a>
                                            <span class="text-muted fs-8">Covered: {{ \Carbon\Carbon::parse($item->class_date)->format('jS M Y') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted fs-7 italic">Not uploaded</span>
                                @endif
                            </td>

                            {{-- KOLUM EXERCISE --}}
                            <td>
                                @if($item->exercise)
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-notepad fs-2x text-success me-3">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('learning-material.download', [$item->id, 'exercise']) }}" class="text-success fw-bold fs-6 text-hover-success">
                                                Practice: {{ $item->topic ?? 'Worksheet' }}
                                            </a>
                                            <span class="text-muted fs-8">Complete after class.</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted fs-7 italic">Not uploaded</span>
                                @endif
                            </td>
                            <td>
                                    @if($item->recording_file)
                                        <div class="d-flex align-items-center">
                                            <i class="ki-duotone ki-video fs-2x text-primary me-3">
                                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                            </i>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('student.learning-material.download', [$item->id, 'recording']) }}"
                                                target="_blank"
                                                class="text-primary fw-bold fs-6 text-hover-primary">
                                                    Class Recording: {{ $item->week ?? '' }}
                                                </a>
                                                <span class="text-muted fs-8">Watch the recorded session.</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted fs-7 italic">No recording available</span>
                                    @endif
                            </td>

                            <td >
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('quiz.create', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Quiz">
                                        <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                    <a href="{{ route('learning-material.edit', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit">
                                        <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                    <a href="{{ route('learning-material.destroy', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-button" title="Delete">
                                        <i class="ki-duotone ki-trash fs-2">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                        </i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    {{-- @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-muted italic">No materials found.</td>
                        </tr> --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
