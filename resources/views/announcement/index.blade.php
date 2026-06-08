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

        {{-- HARDCODED DEVELOPMENT CONTROLS: Change 'admin' to 'student' to test both UI states --}}
        @php
            $currentUserRole = 'admin';

            $announcementList = [
                (object)[ 'id' => 1, 'title' => 'Midterm Examination Schedule', 'description' => 'The midterm examinations for Q2 are scheduled from June 15th to June 20th. Please check your respective course modules for specific time slots.' ],
                (object)[ 'id' => 2, 'title' => 'System Maintenance Notification', 'description' => 'The student portal will undergo routine infrastructure upgrades this upcoming Saturday from 12:00 AM to 04:00 AM EST. Services may be intermittent.' ],
                (object)[ 'id' => 3, 'title' => 'New Library Operating Hours', 'description' => 'Starting next week, the campus main library will extend its operating hours until 11:00 PM on weekdays to accommodate final project preparations.' ],
                (object)[ 'id' => 4, 'title' => 'Guest Lecture: AI Trends in 2026', 'description' => 'Join us in Auditorium Technology Hall B for an engaging session with industry experts breaking down the latest advancements in autonomous transformer models.' ],
                (object)[ 'id' => 5, 'title' => 'Scholarship Application Deadline', 'description' => 'All submissions for the academic merit scholarship fund must be finalized and uploaded to the financial aid portal before June 30th at midnight.' ],
                (object)[ 'id' => 6, 'title' => 'Python Bootcamp Registration Open', 'description' => 'The Computer Science department is hosting a weekend crash course covering foundational Python frameworks. Seats are limited to 50 applicants.' ],
                (object)[ 'id' => 7, 'title' => 'Campus Career Fair Up Next', 'description' => 'Over 40 engineering, finance, and creative tech corporations will be recruiting on-campus next Thursday. Update your resumes and bring physical copies.' ],
                (object)[ 'id' => 8, 'title' => 'Annual Research Symposium Call for Papers', 'description' => 'Undergraduate and postgraduate students are invited to submit abstract papers for the annual multidisciplinary innovations panel.' ],
                (object)[ 'id' => 9, 'title' => 'Student Health Insurance Updates', 'description' => 'Revised medical coverage policies and clinic matching directories for the upcoming academic cycle are now accessible via the documentation drive.' ],
                (object)[ 'id' => 10, 'title' => 'Holiday Campus Closure', 'description' => 'Please note that all administrative offices, student lounges, and campus athletic facilities will be closed observing the national bank holiday.' ]
            ];
        @endphp

        <div class="card-header">
            <h3 class="card-title">Announcement List</h3>
            <div class="card-toolbar">
                {{-- Only Admin and Tutor can see the Add button --}}
                @if($currentUserRole != 'student')
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
                                @if($currentUserRole != 'student')
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
