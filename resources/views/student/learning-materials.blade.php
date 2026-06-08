@extends('layouts.app')

@section('title', 'Learning Materials')
@section('page-header', 'Learning Materials')

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h3 class="fw-bold text-gray-800 mb-0">Learning Materials</h3>
        <a href="{{ route('student.class.index') }}" class="btn btn-sm btn-secondary">
            <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
            Back to Classes
        </a>
    </div>

    @forelse($materialsByWeek as $week => $items)
    <div class="card shadow-sm mb-5">
        {{-- Week header --}}
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold text-gray-900 mb-0"> Week {{ $week }}</h3>
            <div class="card-toolbar">
                {{-- <span class="badge badge-light fs-8">{{ $items->count() }} session(s)</span> --}}
            </div>
        </div>

        <div class="card-body pt-4">
            @foreach($items as $item)
            <div class="border border-gray-300 border-dashed rounded p-4 mb-4">

                {{-- Top row: topic + session + Join --}}
                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
                    <div>
                        <h4 class="fw-bold text-gray-900 mb-1">{{ $item->topic ?? 'Session' }}</h4>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($item->schedule)
                                <span class="badge badge-light-info fs-8">
                                    {{ $item->schedule->day }}
                                    {{ \Carbon\Carbon::parse($item->schedule->start_time)->format('h:i A') }}
                                </span>
                            @endif
                            <span class="badge badge-light fs-8">{{ \Carbon\Carbon::parse($item->class_date)->format('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- Join Zoom --}}
                    @if($item->webex_link)
                        <a href="{{ $item->webex_link }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="ki-duotone ki-video fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Join Class
                        </a>
                    @else
                        <span class="badge badge-light-secondary fs-8 align-self-center">Physical / No Link</span>
                    @endif
                </div>

                {{-- Resources row --}}
                <div class="row g-3">
                    {{-- Lecture note --}}
                    <div class="col-md-3">
                        @if($item->lecture_note)
                            <a href="{{ route('student.learning-material.download', [$item->id, 'note']) }}" target="_blank" class="d-flex align-items-center text-hover-primary">
                                <i class="ki-duotone ki-file-up fs-2x text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                <span class="fw-semibold fs-7">Lecture Notes</span>
                            </a>
                        @else
                            <span class="text-muted fs-8"><i class="ki-duotone ki-file fs-2x me-2 text-muted"><span class="path1"></span><span class="path2"></span></i>No notes</span>
                        @endif
                    </div>
                    {{-- Exercise --}}
                    <div class="col-md-3">
                        @if($item->exercise)
                            <a href="{{ route('student.learning-material.download', [$item->id, 'exercise']) }}" target="_blank" class="d-flex align-items-center text-hover-success">
                                <i class="ki-duotone ki-notepad fs-2x text-success me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <span class="fw-semibold fs-7">Exercise</span>
                            </a>
                        @else
                            <span class="text-muted fs-8"><i class="ki-duotone ki-notepad fs-2x me-2 text-muted"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>No exercise</span>
                        @endif
                    </div>
                    {{-- Recording --}}
                    <div class="col-md-3">
                        @if($item->recording_file)
                            <a href="{{ route('student.learning-material.download', [$item->id, 'recording']) }}" target="_blank" class="d-flex align-items-center text-hover-primary">
                                <i class="ki-duotone ki-video fs-2x text-info me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <span class="fw-semibold fs-7">Recording</span>
                            </a>
                        @else
                            <span class="text-muted fs-8"><i class="ki-duotone ki-video fs-2x me-2 text-muted"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>No recording</span>
                        @endif
                    </div>
                    {{-- Quiz --}}
                    <div class="col-md-3">
                        @if($item->quiz)
                            <a href="{{ route('quiz.map', $item->class_id) }}" class="d-flex align-items-center text-hover-warning">
                                <i class="ki-duotone ki-some-files fs-2x text-warning me-2"><span class="path1"></span><span class="path2"></span></i>
                                <span class="fw-semibold fs-7">Take Quiz</span>
                            </a>
                        @else
                            <span class="text-muted fs-8"><i class="ki-duotone ki-some-files fs-2x me-2 text-muted"><span class="path1"></span><span class="path2"></span></i>No quiz</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="card shadow-sm">
        <div class="card-body text-center py-15">
            <h3 class="text-gray-700">No materials yet</h3>
            <p class="text-muted">Your tutor hasn't uploaded any materials for this class.</p>
        </div>
    </div>
    @endforelse

</div>
@endsection
