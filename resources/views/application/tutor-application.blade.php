@extends('layouts.app')

@section('title', 'Tutor Application Review')
@section('page-header', 'Tutor Application Review')

@section('content')
<div id="kt_content_container" class="container-xxl">

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-tick fs-2x me-3 text-success">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @php
        // ── Enhanced Deep JSON decoder (strips single/double quotes & slashes gracefully) ──
        function decodeDeep($value) {
            if (is_array($value)) return $value;

            $max = 5;
            while (is_string($value) && $max-- > 0) {
                $value = trim($value);

                // If it's stringified with outer escaped quotes, strip them cleanly
                if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                    $value = substr($value, 1, -1);
                    $value = stripslashes($value);
                }

                $decoded = json_decode($value, true);
                if ($decoded === null) break;
                $value = $decoded;
            }
            return is_array($value) ? $value : [];
        }

        $profile = $tutor->tutorProfile;

        $score = $profile->qualification_score ?? 0;
        $rec   = $profile->recommendation_status ?? 'Standard';
        $scoreColor = $score >= 80 ? 'success' : ($score >= 60 ? 'primary' : 'danger');

        $suggestedSubjects = decodeDeep($profile->suggested_subjects ?? null);
        $experienceTitles  = decodeDeep($profile->experience_titles ?? null);
    @endphp

    <div class="row g-6">

        {{-- LEFT: Applicant Details --}}
        <div class="col-lg-8">

            {{-- Profile Card --}}
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-65px me-4">
                                @if($profile && $profile->profile_photo)
                                    <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                         class="rounded-circle" style="width:65px;height:65px;object-fit:cover;">
                                @else
                                    <div class="symbol-label bg-light-success text-success fw-bold fs-2">
                                        {{ substr($tutor->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="fw-bold text-gray-800 mb-1">{{ $tutor->name }}</h3>
                                <span class="text-muted fs-7 d-block">{{ $tutor->email }}</span>
                                <span class="text-muted fs-8">{{ $tutor->phone_number ?? 'No phone' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-toolbar">
                        @if($tutor->status === 'approved')
                            <span class="badge badge-light-success fs-7">Approved</span>
                        @elseif($tutor->status === 'pending')
                            <span class="badge badge-light-warning fs-7">Pending Review</span>
                        @else
                            <span class="badge badge-light-danger fs-7">Rejected</span>
                        @endif
                    </div>
                </div>

                <div class="card-body pt-4">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <table class="table fs-7 fw-semibold gs-0 gy-3 gx-2 m-0">
                                <tr>
                                    <td class="text-gray-400 min-w-150px">University:</td>
                                    <td class="text-gray-800">{{ $profile->university ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Course:</td>
                                    <td class="text-gray-800">{{ $profile->course ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">CGPA:</td>
                                    <td class="text-gray-800">{{ $profile->cgpa ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Education Level:</td>
                                    <td class="text-gray-800">{{ $profile->educationLevel->name ?? '—' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table fs-7 fw-semibold gs-0 gy-3 gx-2 m-0">
                                <tr>
                                    <td class="text-gray-400 min-w-150px">Experience:</td>
                                    <td class="text-gray-800">{{ $profile->experience ?? 0 }} year(s)</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Age:</td>
                                    <td class="text-gray-800">{{ $profile->age ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Address:</td>
                                    <td class="text-gray-800">{{ $profile->address ?? '—' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="separator my-5"></div>

                    <h6 class="fw-bold text-gray-700 mb-2">Teaching Style</h6>
                    <p class="text-gray-600 fs-7">{{ $profile->tutor_style_description ?? '—' }}</p>
                </div>
            </div>

            {{-- AI Summary Card --}}
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-35px me-3">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-abstract-26 fs-3 text-primary">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                            <div>
                                <h5 class="fw-bold text-gray-700 mb-0">AI-Generated Summary</h5>
                                <span class="text-muted fs-8">Auto-generated from resume analysis</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    @if($profile && $profile->ai_summary)
                        <div class="notice d-flex bg-light-primary rounded border border-primary border-dashed p-5">
                            <i class="ki-duotone ki-information fs-2x text-primary me-4 mt-1">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <div class="text-gray-700 fs-6 lh-lg">
                                {{ $profile->ai_summary }}
                            </div>
                        </div>
                    @else
                        <div class="text-muted fs-7 text-center py-6">
                            No AI summary available for this applicant.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Documents Card --}}
            <div class="card card-flush">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-700 mb-0">Submitted Documents</h5>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            @if($profile && $profile->resume)
                            <a href="{{ asset('storage/' . $profile->resume) }}" target="_blank"
                               class="d-flex align-items-center p-4 bg-light rounded text-decoration-none">
                                <i class="ki-duotone ki-document fs-2x text-danger me-3">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-7 d-block">Resume</span>
                                    <span class="text-muted fs-8">Click to view PDF</span>
                                </div>
                            </a>
                            @else
                            <div class="text-muted fs-7">No resume uploaded</div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($profile && $profile->tutor_cert)
                            <a href="{{ asset('storage/' . $profile->tutor_cert) }}" target="_blank"
                               class="d-flex align-items-center p-4 bg-light rounded text-decoration-none">
                                <i class="ki-duotone ki-medal-star fs-2x text-warning me-3">
                                    <span class="path1"></span><span class="path2"></span>
                                    <span class="path3"></span><span class="path4"></span>
                                </i>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-7 d-block">Certificate</span>
                                    <span class="text-muted fs-8">Click to view</span>
                                </div>
                            </a>
                            @else
                            <div class="text-muted fs-7">No certificate uploaded</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Score & Actions --}}
        <div class="col-lg-4">

            {{-- Score Card --}}
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-800">Qualification Score</h5>
                    </div>
                </div>
                <div class="card-body pt-4 text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <svg width="160" height="160" viewBox="0 0 160 160">
                            <circle cx="80" cy="80" r="70" fill="none"
                                    stroke="var(--bs-gray-200)" stroke-width="12"/>
                            <circle cx="80" cy="80" r="70" fill="none"
                                    stroke="var(--bs-{{ $scoreColor }})" stroke-width="12"
                                    stroke-dasharray="{{ 2 * 3.14159 * 70 }}"
                                    stroke-dashoffset="{{ 2 * 3.14159 * 70 * (1 - $score/100) }}"
                                    stroke-linecap="round"
                                    transform="rotate(-90 80 80)"/>
                            <text x="80" y="75" text-anchor="middle"
                                  class="fw-bolder" font-size="36"
                                  fill="var(--bs-{{ $scoreColor }})">{{ $score }}</text>
                            <text x="80" y="98" text-anchor="middle"
                                  font-size="13" fill="var(--bs-gray-500)">out of 100</text>
                        </svg>
                    </div>

                    <div>
                        @if($rec === 'Highly Recommended')
                            <span class="badge badge-light-success fs-6 px-4 py-2">⭐ Highly Recommended</span>
                        @elseif($rec === 'Recommended')
                            <span class="badge badge-light-primary fs-6 px-4 py-2">✓ Recommended</span>
                        @else
                            <span class="badge badge-light-warning fs-6 px-4 py-2">Standard</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Subject Expertise (from registration) --}}
            @if($tutor->subjects && $tutor->subjects->count() > 0)
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-800">Subject Expertise</h5>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($tutor->subjects as $subject)
                            <span class="badge badge-light-primary fs-8 px-3 py-2">{{ $subject->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Recommended Teaching Subjects (AI) --}}
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-800">Recommended Teaching Subjects</h5>
                    </div>
                </div>
                <div class="card-body pt-4">
                    @if(!empty($suggestedSubjects))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($suggestedSubjects as $subject)
                                <span class="badge badge-light-info fs-8 px-3 py-2">{{ $subject }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted fs-7">No recommendations available.</span>
                    @endif
                </div>
            </div>

            {{-- Working Experience History (AI) --}}
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-800">Working Experience History</h5>
                    </div>
                </div>
                <div class="card-body pt-4">
                    @if(!empty($experienceTitles))
                        @foreach($experienceTitles as $title)
                            <div class="d-flex align-items-center mb-2">
                                <i class="ki-duotone ki-briefcase fs-5 text-success me-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <span class="text-gray-700 fs-7">{{ $title }}</span>
                            </div>
                        @endforeach
                    @else
                        <span class="text-muted fs-7">No experience history extracted.</span>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            @if($tutor->status === 'pending')
            <div class="card card-flush mb-4">
                <div class="card-body p-6">
                    <h6 class="fw-bold text-gray-700 mb-4">Review Decision</h6>

                    <a href="{{ route('application.tutor.approve', $tutor->id) }}"
                       class="btn btn-success w-100 fw-bold mb-3"
                       onclick="return confirm('Approve {{ $tutor->name }} as a tutor?')">
                        <i class="ki-duotone ki-check fs-2x me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Approve Application
                    </a>

                    <a href="{{ route('application.tutor.reject', $tutor->id) }}"
                       class="btn btn-light-danger w-100 fw-bold"
                       onclick="return confirm('Reject this application?')">
                        <i class="ki-duotone ki-cross fs-2x me-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Reject Application
                    </a>
                </div>
            </div>
            @endif

            <a href="{{ route('application.indexTutor') }}" class="btn btn-light w-100 fw-bold">
                <i class="ki-duotone ki-arrow-left fs-2x me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Back to Applications
            </a>

        </div>
    </div>
</div>
@endsection
