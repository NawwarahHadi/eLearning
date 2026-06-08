@extends('layouts.app')

@section('title', 'Announcement')

@section('page-header', 'Announcement')

{{-- Update the breadcrumb if you have the English version defined --}}
{{-- @section('breadcrumbs', Breadcrumbs::render('announcement')) --}}

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).on('click', '.delete-data', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Warning!',
                text: 'Click Continue to delete this announcement.',
                icon: 'warning',
                confirmButtonText: 'Continue',
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
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Announcement List</h3>
            <div class="card-toolbar">
                {{-- Only Admin and Tutor can see the Add button --}}
                @if(Auth::user()->role != 'student')
                    <a href="{{ route('announcement.create') }}" class="btn btn-sm btn-primary">
                        <i class="ki-duotone ki-plus-square">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Add Announcement
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Title</th>
                        <th>Description</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach ($announcementList as $announcement)
                        <tr>
                            <td style="vertical-align: middle;">{{ $announcement->title }}</td>
                            <td style="vertical-align: middle;">{{ $announcement->description }}</td>
                            <td class="text-end">
                                {{-- Actions only for Admin and Tutor --}}
                                @if(Auth::user()->role != 'student')
                                    <a href="{{ route('announcement.edit', $announcement->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ki-duotone text-warning ki-notepad-edit fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <a href="{{ route('announcement.destroy', $announcement->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm delete-data" data-bs-toggle="tooltip" title="Delete">
                                        <i class="ki-duotone text-danger ki-trash fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </a>
                                @else
                                    <span class="badge badge-light-info">View Only</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
