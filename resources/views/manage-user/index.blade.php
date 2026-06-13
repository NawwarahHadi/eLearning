@extends('layouts.app')

@section('title', 'User Management')
@section('page-header', 'User Management')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).on('click', '.set-kata-laluan', function(e) {
            e.preventDefault();
            const url = $(this).attr("href");

            Swal.fire({
                title: 'Reset Password?',
                text: 'Click Continue to set a new temporary password.',
                icon: 'warning',
                confirmButtonText: 'Continue',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-danger",
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

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-tick fs-2 me-3 text-success">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('success') }}</div>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-cross fs-2 me-3 text-danger">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row g-5 mb-6">
        <div class="col-sm-4">
            <div class="card card-flush">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-people fs-2x text-primary">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Total Students</span>
                            <span class="text-gray-800 fw-bolder fs-3">{{ $students->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-flush">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-teacher fs-2x text-success">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Total Tutors</span>
                            <span class="text-gray-800 fw-bolder fs-3">{{ $tutors->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-flush">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-shield-tick fs-2x text-warning">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Total Admins</span>
                            <span class="text-gray-800 fw-bolder fs-3">{{ $admins->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card card-flush">
        <div class="card-header pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">All Users</h3>
            </div>
            {{-- <div class="card-toolbar">
                <a href="{{ route('user-management.tambah') }}" class="btn btn-primary fw-bold">
                    <i class="ki-duotone ki-plus fs-2 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Add New User
                </a>
            </div> --}}
        </div>

        <div class="card-body pt-4">

            {{-- Tabs --}}
            <ul class="nav nav-tabs nav-line-tabs mb-6 fs-6">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" data-bs-toggle="tab" href="#tab_students">
                        {{-- <i class="ki-duotone ki-people fs-4 me-2">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                        </i> --}}
                        Students
                        {{-- <span class="badge badge-light-primary ms-2">{{ $students->count() }}</span> --}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" data-bs-toggle="tab" href="#tab_tutors">
                        {{-- <i class="ki-duotone ki-teacher fs-4 me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i> --}}
                        Tutors
                        {{-- <span class="badge badge-light-success ms-2">{{ $tutors->count() }}</span> --}}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" data-bs-toggle="tab" href="#tab_admins">
                        {{-- <i class="ki-duotone ki-shield-tick fs-4 me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i> --}}
                        Admins
                        {{-- <span class="badge badge-light-warning ms-2">{{ $admins->count() }}</span> --}}
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                {{-- ── STUDENTS TAB ── --}}
                <div class="tab-pane fade show active" id="tab_students">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>Student</th>
                                <th>Phone</th>
                                <th>Form Level</th>
                                <th>Age</th>
                                <th>Learning Style</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($students as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            @if($user->studentProfile?->profile_photo)
                                                <img src="{{ asset('storage/' . $user->studentProfile->profile_photo) }}"
                                                     class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                <div class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">{{ $user->name }}</span>
                                            <span class="text-muted fs-8">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-7">
                                        {{ $user->phone_number ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-7">
                                        {{ strtoupper($user->studentProfile?->category ?? '—') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-7">
                                        {{ $user->studentProfile?->age ?? '—' }}{{ $user->studentProfile?->age ? ' yrs' : '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-8" style="max-width:180px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $user->studentProfile?->student_style_description ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('user-management.login-as', $user->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Log As">
                                        <i class="ki-duotone text-info ki-fingerprint-scanning fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </a>
                                    <a href="{{ route('user-management.edit', $user->id) }}"  type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ki-duotone text-warning ki-notepad-edit fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <a href="{{ route('user-management.set-password', $user->id) }}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 set-kata-laluan" data-bs-toggle="tooltip"  title="Password">
                                        <i class="ki-duotone text-danger ki-password-check fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-8">
                                    <i class="ki-duotone ki-people fs-3x text-muted mb-3 d-block">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                    No students found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── TUTORS TAB ── --}}
                <div class="tab-pane fade" id="tab_tutors">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>Tutor</th>
                                <th>Phone</th>
                                <th>Experience</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($tutors as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            @if($user->tutorProfile?->profile_photo)
                                                <img src="{{ asset('storage/' . $user->tutorProfile->profile_photo) }}"
                                                     class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                <div class="symbol-label bg-light-success text-success fw-bold fs-6">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">{{ $user->name }}</span>
                                            <span class="text-muted fs-8">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-7">
                                        {{ $user->phone_number ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-7">
                                        {{ $user->tutorProfile?->experience ?? 0 }} year(s)
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('user-management.login-as', $user->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Log As">
                                        <i class="ki-duotone text-info ki-fingerprint-scanning fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </a>
                                    <a href="{{ route('user-management.edit', $user->id) }}"  type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ki-duotone text-warning ki-notepad-edit fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <a href="{{ route('user-management.set-password', $user->id) }}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 set-kata-laluan" data-bs-toggle="tooltip"  title="Password">
                                        <i class="ki-duotone text-danger ki-password-check fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-8">
                                    <i class="ki-duotone ki-teacher fs-3x text-muted mb-3 d-block">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    No tutors found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ── ADMINS TAB ── --}}
                <div class="tab-pane fade" id="tab_admins">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>Admin</th>
                                <th>Phone</th>
                                <th>Joined</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($admins as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label bg-light-warning text-warning fw-bold fs-6">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 fw-bold fs-6">{{ $user->name }}</span>
                                            <span class="text-muted fs-8">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-7">
                                        {{ $user->phone_number ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted fs-8">
                                        {{ $user->created_at->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('user-management.login-as', $user->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Log As">
                                        <i class="ki-duotone text-info ki-fingerprint-scanning fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </a>
                                    <a href="{{ route('user-management.edit', $user->id) }}"  type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ki-duotone text-warning ki-notepad-edit fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    <a href="{{ route('user-management.set-password', $user->id) }}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 set-kata-laluan" data-bs-toggle="tooltip"  title="Password">
                                        <i class="ki-duotone text-danger ki-password-check fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-8">No admins found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
