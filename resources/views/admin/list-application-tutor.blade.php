@extends('layouts.app')

@section('title', 'Application')

@section('page-header', 'Tutor Application')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .nav-line-tabs .nav-link {
            padding: 0.75rem 1.25rem;
        }

        .table th {
            padding: 0.85rem 1rem;
            letter-spacing: 0.04em;
        }

        .table td {
            padding: 1rem 1rem;
            vertical-align: middle;
        }

        /* .tutor-name {
            font-weight: 600;
            font-size: 0.925rem;
            color: #1e1e2d;
            line-height: 1.3;
        }

        .tutor-email {
            font-size: 0.8rem;
            color: #a1a5b7;
        } */

        .score-label {
            font-size: 0.75rem;
            color: #a1a5b7;
            margin-top: 4px;
        }

        /* .symbol-50px img {
            object-fit: cover;
            border-radius: 50%;
        } */

        .action-btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 6px;
            align-items: center;
        }

        .status-chip {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .status-chip.approved {
            background-color: #e8fff3;
            color: #50cd89;
        }

        .status-chip.rejected {
            background-color: #fff5f8;
            color: #f1416c;
        }
    </style>
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).on('click', '.batal-button', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Reject Application?',
                text: 'This action will reject the tutor application.',
                icon: 'warning',
                confirmButtonText: 'Yes, Reject',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-light',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">

        {{-- Card Header with Tabs --}}
        <div class="card-header card-header-stretch border-bottom">
            <div class="card-title d-flex align-items-center">
                <h3 class="fw-bold m-0 text-gray-800">Tutor Applications</h3>
            </div>
            <div class="card-toolbar m-0">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold">
                    <li class="nav-item">
                        <a id="new_tab"
                           class="nav-link {{ request('tab') == null || request('tab') == 'new' ? 'active' : '' }}"
                           href="{{ route('application.indexTutor', ['tab' => 'new']) }}">
                            <span class="nav-text">New</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="Approve_tab"
                           class="nav-link {{ request('tab') == 'Approve' ? 'active' : '' }}"
                           href="{{ route('application.indexTutor', ['tab' => 'Approve']) }}">
                            <span class="nav-text">Approved</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="reject_tab"
                           class="nav-link {{ request('tab') == 'reject' ? 'active' : '' }}"
                           href="{{ route('application.indexTutor', ['tab' => 'reject']) }}">
                            <span class="nav-text">Rejected</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card-body pt-6">
            <div class="tab-content">
                <div id="new"
                     class="tab-pane fade {{ request('tab') == null || request('tab') == 'new' ? 'show active' : '' }}"
                     role="tabpanel">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-4">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th >Applicant</th>
                                <th style="min-width: 120px;">Score & Ranking</th>
                                {{-- <th style="min-width: 120px;">Experience</th> --}}
                                <th class="text-end" style="min-width: 120px;">View Profile</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($pendingTutors as $tutor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="symbol symbol-circle symbol-45px overflow-hidden flex-shrink-0">
                                                <div class="symbol-label">
                                                    @if ($tutor->tutorProfile && $tutor->tutorProfile->profile_photo)
                                                        <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}"
                                                             alt="{{ $tutor->name }}" class="w-100" />
                                                    @else
                                                        <img src="{{ asset('metronic/assets/media/avatars/blank.png') }}"
                                                             alt="Blank" class="w-100" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <div class="tutor-name">{{ $tutor->name }}</div>
                                                <div class="tutor-email">{{ $tutor->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if ($tutor->tutorProfile)
                                            @php
                                                $score = $tutor->tutorProfile->qualification_score;
                                                $rank  = $tutor->tutorProfile->recommendation_status;

                                                // Badge styling
                                                $badgeClass = 'badge-light-info';
                                                if ($rank == 'Highly Recommended') $badgeClass = 'badge-light-success';
                                                if ($rank == 'Recommended')        $badgeClass = 'badge-light-primary';

                                                // Progress bar colour
                                                $barColor = 'bg-info';
                                                if ($score >= 80)      $barColor = 'bg-success';
                                                elseif ($score >= 60)  $barColor = 'bg-primary';
                                                elseif ($score < 40)   $barColor = 'bg-danger';
                                            @endphp

                                            <div class="d-flex flex-column" style="min-width: 150px;">
                                                {{-- Rank badge + score value on same row --}}
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="badge {{ $badgeClass }} fw-bold fs-8">{{ $rank }}</span>
                                                    <span class="fw-bold fs-7 text-gray-700">{{ $score }}%</span>
                                                </div>

                                                {{-- Progress bar --}}
                                                <div class="progress h-6px w-100 bg-light">
                                                    <div class="progress-bar {{ $barColor }} rounded"
                                                         role="progressbar"
                                                         style="width: {{ $score }}%; transition: width 0.6s ease;"
                                                         aria-valuenow="{{ $score }}"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic fs-7">Profile Missing</span>
                                        @endif
                                    </td>

                                    </td>

                                    {{-- <td>
                                        {{ $tutor->tutorProfile?->experience ?? 0 }} Years
                                    </td> --}}

                                    <td class="text-end" >
                                        <a href="{{ route('application.show', $tutor->id) }}"class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" title="Details">
                                            <i class="ki-duotone ki-dots-horizontal fs-2x">
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

                <div id="Approve" class="tab-pane fade {{ request('tab') == 'Approve' ? 'show active' : '' }}" role="tabpanel">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-4">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th style="min-width: 200px;">Applicant</th>
                                <th style="min-width: 180px;">Subject Teaching</th>
                                <th style="min-width: 120px;">Experience</th>
                                <th style="min-width: 120px;">Status</th>
                                <th class="text-end" style="min-width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($approvedTutors as $tutor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="symbol symbol-circle symbol-45px overflow-hidden flex-shrink-0">
                                                <div class="symbol-label">
                                                    @if ($tutor->tutorProfile && $tutor->tutorProfile->profile_photo)
                                                        <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}"
                                                             alt="{{ $tutor->name }}" class="w-100" />
                                                    @else
                                                        <img src="{{ asset('metronic/assets/media/avatars/blank.png') }}"
                                                             alt="Blank" class="w-100" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <div class="tutor-name">{{ $tutor->name }}</div>
                                                <div class="tutor-email">{{ $tutor->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse($tutor->subjects as $subject)
                                                <span class="badge badge-light-dark fw-bold fs-8 px-2 py-1">
                                                    {{ $subject->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted fs-8 italic">No subjects assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        {{ $tutor->tutorProfile?->experience ?? 0 }} Years
                                    </td>
                                    <td>
                                        <span class="status-chip approved">Approved</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('application.tutor.reject', $tutor->id)}}" type="submit" class="btn btn-icon batal-btn  btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Reject">
                                            <i class="ki-duotone text-danger ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </a>

                                        <a href="{{route ('tutor-profile', $tutor ->id)}}"class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" title="Profile">
                                            <i class="ki-duotone ki-dots-horizontal fs-2x">
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

                <div id="reject" class="tab-pane fade {{ request('tab') == 'reject' ? 'show active' : '' }}" role="tabpanel">
                    <table class="m-datatable table align-middle table-row-dashed fs-6 gy-4">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th style="min-width: 200px;">Applicant</th>
                                <th style="min-width: 180px;">Date Rejected</th>
                                <th style="min-width: 120px;">Status</th>
                                <th class="text-end" style="min-width: 120px;">Action</th>

                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @foreach ($rejectedTutors as $tutor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="symbol symbol-circle symbol-45px overflow-hidden flex-shrink-0">
                                                <div class="symbol-label">
                                                    @if ($tutor->tutorProfile && $tutor->tutorProfile->profile_photo)
                                                        <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}"
                                                             alt="{{ $tutor->name }}" class="w-100" />
                                                    @else
                                                        <img src="{{ asset('metronic/assets/media/avatars/blank.png') }}"
                                                             alt="Blank" class="w-100" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <div class="tutor-name">{{ $tutor->name }}</div>
                                                <div class="tutor-email">{{ $tutor->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($tutor->rejected_at)
                                            <div class="text-dark fw-bold">{{ \Carbon\Carbon::parse($tutor->rejected_at)->format('d M Y') }}</div>
                                            {{-- <div class="text-muted fs-7">{{ \Carbon\Carbon::parse($tutor->rejected_at)->format('h:i A') }}</div> --}}
                                        @else
                                            <div class="text-dark fw-bold">{{ $tutor->updated_at->format('d M Y') }}</div>
                                            {{-- <div class="text-muted fs-7">{{ $tutor->updated_at->format('h:i A') }}</div> --}}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-chip rejected">Rejected</span>
                                    </td>
                                    <td class="text-end" >
                                        <a href="{{ route('application.tutor.approve', $tutor->id)}}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Approve">
                                            <i class="ki-duotone text-success ki-check-circle fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>

                                        <a href="{{ route('application.show', $tutor->id) }}"class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" title="Details">
                                            <i class="ki-duotone ki-dots-horizontal fs-2x">
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

            </div>{{-- end .tab-content --}}
        </div>{{-- end .card-body --}}
    </div>{{-- end .card --}}
</div>
@endsection
