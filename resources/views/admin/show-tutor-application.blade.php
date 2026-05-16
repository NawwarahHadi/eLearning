@extends('layouts.app')

@section('title', 'Application Profile')

@section('page-header', 'Application Profile')

@section('js_after')
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    {{-- <script src="{{ asset('metronic/js/button_loading.js') }}"></script> --}}
    <script>
        $(document).on('click', '.batal-button', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Warning!',
                text: 'Click to proceed reject the request.',
                icon: 'warning',
                confirmButtonText: 'Proceed',
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
    <div class="d-flex justify-content-between align-items-center mb-7">
        <a href="{{ route('application.indexTutor') }}" class="btn btn-sm btn-dark fw-bold">Back</a>
        <div class="d-flex gap-3">
            @if($tutor->status !== 'rejected')
                <a href="{{ route('application.tutor.reject', $tutor->id) }}"
                class="btn btn-sm batal-button btn-outline btn-outline-dark">REJECT</a>
            @else
                <button class="btn btn-sm btn-secondary disabled" disabled>REJECTED</button>
            @endif

            @if($tutor->status !== 'approved')
                <a href="{{ route('application.tutor.approve', $tutor->id) }}"
                class="btn btn-sm btn-outline btn-outline-dark">APPROVE</a>
            @else
                <button class="btn btn-sm btn-secondary disabled" disabled>
                    <i class="ki-duotone ki-check-circle fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> APPROVED
                </button>
            @endif
        </div>
    </div>

    <div class="card mb-5 border-0 shadow-sm">
        <div class="card-body pt-9 pb-5" style="background: linear-gradient(to bottom, #000 140px, #fff 140px); border-radius: 12px;">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <div class="me-7">
                    <div class="symbol symbol-160px"><img src="{{ asset('storage/'.$tutor->tutorProfile->profile_photo) }}" style="border: 4px solid white; border-radius:12px;"></div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex flex-column">
                            <h1 class="text-white fw-bold fs-2hx mb-1">{{ $tutor->name }}</h1>
                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                        <span class="text-white opacity-75 me-5">
                            <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> {{ $tutor->email }}
                            </span>
                        <span class="text-white opacity-75">
                                <i class="ki-duotone ki-calendar fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Applied: {{ $tutor->created_at->format('Y-m-d') }}
                        </span>
                    </div>
                    <div class="d-flex flex-column w-250px mt-15">
                            <span class="text-black fs-8 fw-bold">QUALIFICATION SCORE: {{ $tutor->tutorProfile->qualification_score }}%</span>
                            <div class="progress h-8px bg-light-dark"><div class="progress-bar bg-dark" style="width: {{ $tutor->tutorProfile->qualification_score }}%"></div></div>
                            <span class="badge badge-dark fw-bold mt-3 align-self-start">{{ strtoupper($tutor->tutorProfile->recommendation_status) }}</span>
                    </div>
                </div>
                {{-- <div class="d-flex flex-stack flex-wrap mt-auto">
                    <div class="d-flex flex-column w-250px mt-5">
                        <div class="d-flex flex-stack mb-2">
                            <span class="text-white opacity-75 fs-8 fw-bold">Qualification Score</span>
                            <span class="text-white fw-bold fs-7">{{ $tutor->tutorProfile->qualification_score }}%</span>
                        </div>
                        <div class="progress h-10px w-100 bg-white bg-opacity-10">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tutor->tutorProfile->qualification_score }}%" aria-valuenow="{{ $tutor->tutorProfile->qualification_score }}" aria-valuemin="0" aria-valuemax="100"> </div>
                    </div>
                    <span class="badge badge-light-success fw-bold mt-2 align-self-start">{{ $tutor->tutorProfile->recommendation_status }}</span>
                </div> --}}

                {{-- <div class="flex-grow-1">
                    <h1 class="text-white fw-bold fs-2hx mb-1">{{ $tutor->name }}</h1>
                    <p class="text-white opacity-75">{{ $tutor->email }} | Applied: {{ $tutor->created_at->format('d M Y') }}</p>
                    <div class="d-flex flex-column w-250px mt-10">
                        <span class="text-muted fs-8 fw-bold">QUALIFICATION SCORE: {{ $tutor->tutorProfile->qualification_score }}%</span>
                        <div class="progress h-8px bg-light-dark"><div class="progress-bar bg-dark" style="width: {{ $tutor->tutorProfile->qualification_score }}%"></div></div>
                        <span class="badge badge-dark fw-bold mt-3 align-self-start">{{ strtoupper($tutor->tutorProfile->recommendation_status) }}</span>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-light-secondary border border-gray-300 border-dashed rounded p-8">
                    <h4 class="fw-bold text-dark mb-4">About Candidate</h4>
                    <p class="fs-6 text-gray-800 m-0 italic">"{{ $tutor->tutorProfile->ai_summary }}"</p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h4 class="text-dark fw-bold mb-5 border-bottom pb-3">Subject Expertise</h4>
                    <div class="d-flex flex-wrap gap-2 mb-8">
                        @if($tutor->subjects && $tutor->subjects->count() > 0)
                            @foreach($tutor->subjects as $subject)
                                <span class="badge badge-outline badge-dark fw-bold px-4 py-3 fs-7">{{ $subject->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted italic fs-7">No subjects selected.</span>
                        @endif
                    </div>

                    <h4 class="text-dark fw-bold mb-5 border-bottom pb-3">Recommended Teaching Subject</h4>
                    <div class="d-flex flex-wrap gap-2">
                        @if(!empty($tutor->tutorProfile->suggested_subjects) && is_array($tutor->tutorProfile->suggested_subjects))
                            @foreach($tutor->tutorProfile->suggested_subjects as $subject)
                                <span class="badge badge-dark px-4 py-3">{{ $subject }}</span>
                            @endforeach
                        @else
                            <span class="text-muted italic fs-7">No recommendations available.</span>
                        @endif
                    </div>

                    <h4 class="text-dark fw-bold mt-10 mb-5 border-bottom pb-3">Working Experience History</h4>
                        @if(!empty($tutor->tutorProfile->experience_titles) && is_array($tutor->tutorProfile->experience_titles))
                            @foreach($tutor->tutorProfile->experience_titles as $title)
                                <div class="p-3 mb-2 rounded bg-light-secondary border-left-dark fw-bold text-gray-800">
                                    {{ $title }}
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 mb-2 rounded bg-light text-muted fs-7">No experience history extracted.</div>
                        @endif
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h4 class="text-dark fw-bold mb-5 border-bottom pb-3">Academic Background</h4>
                    <table class="table table-row-dashed gy-5">
                        <tbody class="fs-6">
                            <tr><td class="text-muted">University</td><td class="text-dark fw-bold text-end">{{ $tutor->tutorProfile->university }}</td></tr>
                            <tr><td class="text-muted">Course</td><td class="text-dark fw-bold text-end">{{ $tutor->tutorProfile->course }}</td></tr>
                            <tr><td class="text-muted">CGPA</td><td class="text-dark fw-bold text-end">{{ number_format($tutor->tutorProfile->cgpa, 2) }}</td></tr>
                            <tr><td class="text-muted">Address</td><td class="text-dark fw-bold text-end">{{ $tutor->tutorProfile->address }}</td></tr>
                            <tr><td class="text-muted">Teaching style</td><td class="text-dark fw-bold text-end">{{ $tutor->tutorProfile->tutor_style_description }}</td></tr>
                        </tbody>
                    </table>
                    {{-- <div class="mt-8 p-5 rounded bg-dark text-white">
                        <h5 class="text-white mb-2 fs-6">Candidate Message:</h5>
                        <p class="fs-7 m-0 opacity-75">"{{ $tutor->tutorProfile->tutor_style_description }}"</p>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-10">
            <div class="card-body">
                <h4 class="text-dark fw-bold mb-5">Supporting Documents</h4>
                <div class="row g-5">
                    <div class="col-md-6">
                        <a href="{{ asset('storage/'.$tutor->tutorProfile->resume) }}" target="_blank"
                        class="btn btn-outline btn-outline-dark p-6 d-flex align-items-center w-100">
                            <i class="ki-duotone ki-file-up fs-2hx me-4"><span class="path1"></span><span class="path2"></span></i>
                            <div class="text-start">
                                <div class="fw-bold fs-6 text-dark">Curriculum Vitae</div>
                                <div class="fs-7 text-muted">Click to open PDF</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ asset('storage/'.$tutor->tutorProfile->tutor_cert) }}" target="_blank"
                        class="btn btn-outline btn-outline-dark p-6 d-flex align-items-center w-100">
                            <i class="ki-duotone ki-briefcase fs-2hx me-4"><span class="path1"></span><span class="path2"></span></i>
                            <div class="text-start">
                                <div class="fw-bold fs-6 text-dark">Academic Transcript</div>
                                <div class="fs-7 text-muted">Click to open PDF</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-secondary { background-color: #f8f9fa !important; }
    .border-left-dark { border-left: 4px solid #000 !important; }
</style>
@endsection
