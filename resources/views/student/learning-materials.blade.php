@extends('layouts.app')

@section('title', 'Learning Materials')

@section('page-header', 'Learning Materials')

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
                <h3 class="fw-bold text-gray-800">Learning Materials List</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('student.class.index') }}" class="btn btn-sm btn-secondary">
                    <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                    Back to Classes
                </a>
            </div>
        </div>

        <div class="card-body py-4">
            {{-- Table ini mempunyai tepat 5 kolum untuk dipadankan dengan thead --}}
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-70px">Week</th>
                        <th class="min-w-100px">Date</th>
                        <th class="min-w-120px">Online Class</th>
                        <th class="min-w-120px">Lecture Notes</th>
                        <th class="min-w-120px">Exercises</th>
                        <th class="min-w-250px">Recording</th>
                        <th class="min-w-120px">QUiz</th>

                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($materials as $item)
                        <tr>
                            {{-- WEEK --}}
                            <td>
                                <span class="badge badge-light-dark fs-7 fw-bold">{{ $item->week }}</span>
                            </td>

                            {{-- DATE --}}
                            <td>{{ \Carbon\Carbon::parse($item->class_date)->format('d/m/Y') }}</td>


                            {{-- ONLINE CLASS COLUMN --}}
                            <td>
                                @if($enrollment && $enrollment->schedule)
                                    <div class="d-flex flex-column">
                                        {{-- This link is now specific to the slot the student picked --}}
                                        <a href="{{ $enrollment->schedule->meeting_link ?? $item->webex_link }}"
                                        target="_blank"
                                        class="text-primary fw-bold text-hover-primary mb-1">
                                            <i class="ki-duotone ki-video fs-4 me-1 text-primary">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            Join {{ $enrollment->schedule->day }} Session
                                        </a>

                                        <span class="text-muted fs-8">
                                            Time: {{ \Carbon\Carbon::parse($enrollment->schedule->start_time)->format('h:i A') }}
                                        </span>

                                        @if($item->webex_meeting_code)
                                            <span class="text-muted fs-8">Code: {{ $item->webex_meeting_code }}</span>
                                        @endif
                                    </div>
                                @else
                                    {{-- Fallback if no specific enrollment schedule is found --}}
                                    <span class="badge badge-light-secondary fs-8">Physical / No Link</span>
                                @endif
                            </td>

                            {{-- LECTURE NOTES (With Icons) --}}
                            <td>
                                @if($item->lecture_note)
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-file-up fs-2x text-primary me-3">
                                            <span class="path1"></span><span class="path2"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            {{-- Gunakan route download anda --}}
                                            <a href="{{ route('student.learning-material.download', [$item->id, 'note']) }}" class="text-primary fw-bold fs-6 text-hover-primary">
                                                Topic: {{ $item->topic ?? 'Lecture Note' }}
                                            </a>
                                            <span class="text-muted fs-8">Covered: {{ \Carbon\Carbon::parse($item->class_date)->format('jS M Y') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted fs-7 italic">Not uploaded</span>
                                @endif
                            </td>

                            {{-- EXERCISES (With Icons) --}}
                            <td>
                                @if($item->exercise)
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-notepad fs-2x text-success me-3">
                                            <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('student.learning-material.download', [$item->id, 'exercise']) }}" class="text-success fw-bold fs-6 text-hover-success">
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
                            <td>
                                @if($item->quiz)
                                    {{-- Only shows if the tutor has created a quiz for this specific material --}}
                                    <a href="{{ route('quiz.play', $item->quiz->id) }}"
                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                    data-bs-toggle="tooltip"
                                    title="Start Quiz">
                                        <i class="ki-duotone ki-some-files text-success fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                @else
                                    {{-- Shows a simple dash or empty state if no quiz exists --}}
                                    <span class="text-muted fs-7">-</span>
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
