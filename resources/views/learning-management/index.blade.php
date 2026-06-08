@extends('layouts.app')

@section('title', 'Learning Materials')
@section('page-header', 'Learning Materials')

@section('js_after')
<script>
    $(document).on('click', '.delete-button', function(e) {
        e.preventDefault();
        let url = $(this).attr("href");
        Swal.fire({
            title: 'Are you sure?',
            text: 'File will be deleted',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-light" }
        }).then((result) => {
            if (result.isConfirmed) window.location.href = url;
        });
    });
</script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h3 class="fw-bold text-gray-800 mb-0">Learning Materials</h3>
        <a href="{{ route('learning-material.create', $class_id) }}" class="btn btn-sm btn-primary">
            <i class="ki-duotone ki-plus-square fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
            Add Material
        </a>
    </div>

    @forelse($materialsByWeek as $week => $items)
    <div class="card shadow-sm mb-5">
        {{-- Week header (clean, no blue, no icon) --}}
        <div class="card-header border-0 pt-5">
            <h3 class="card-title fw-bold text-gray-900 mb-0">Week {{ $week }}</h3>
            <div class="card-toolbar">
                {{-- <span class="badge badge-light fs-8 me-2">{{ $items->count() }} session(s)</span> --}}
                <a href="{{ route('learning-material.create', $class_id) }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span class="path2"></span></i>
                    {{-- Add to {{ $week }} --}}
                </a>
            </div>
        </div>

        <div class="card-body pt-4">
            @foreach($items as $item)
            <div class="border border-gray-300 border-dashed rounded p-4 mb-4">

                {{-- Top row: topic + session + actions --}}
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
                            @if($item->webex_link)
                                <a href="{{ $item->webex_link }}" target="_blank" class="badge badge-light-primary fs-8">
                                    <i class="ki-duotone ki-video fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>Zoom
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="d-flex gap-1">
                        <a href="{{ route('quiz.create', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Add Quiz">
                            <i class="ki-duotone ki-questionnaire-tablet text-info fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                        <a href="{{ route('learning-material.edit', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Edit">
                            <i class="ki-duotone ki-pencil text-success fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                        {{-- <a href="{{ route('learning-material.destroy', $item->id) }}" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm delete-button" title="Delete">
                            <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </a> --}}
                    </div>
                </div>

                {{-- Resources row --}}
                <div class="row g-3">
                    {{-- Lecture note --}}
                    <div class="col-md-3">
                        @if($item->lecture_note)
                            <a href="{{ route('learning-material.download', [$item->id, 'note']) }}" target="_blank" class="d-flex align-items-center text-hover-primary">
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
                            <a href="{{ route('learning-material.download', [$item->id, 'exercise']) }}" target="_blank" class="d-flex align-items-center text-hover-success">
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
                            <a href="{{ route('learning-material.download', [$item->id, 'recording']) }}" target="_blank" class="d-flex align-items-center text-hover-primary">
                                <i class="ki-duotone ki-video fs-2x text-info me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <span class="fw-semibold fs-7">Recording</span>
                            </a>
                        @else
                            <span class="text-muted fs-8"><i class="ki-duotone ki-video fs-2x me-2 text-muted"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>No recording</span>
                        @endif
                    </div>
                    {{-- Quiz status --}}
                    <div class="col-md-3">
                        @if($item->quiz ?? false)
                            <span class="d-flex align-items-center text-warning">
                                <i class="ki-duotone ki-some-files fs-2x text-warning me-2"><span class="path1"></span><span class="path2"></span></i>
                                <span class="fw-semibold fs-7">Quiz added</span>
                            </span>
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
            <p class="text-muted mb-4">Start by adding your first learning material for this class.</p>
            <a href="{{ route('learning-material.create', $class_id) }}" class="btn btn-primary">
                <i class="ki-duotone ki-plus-square fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Add Material
            </a>
        </div>
    </div>
    @endforelse

</div>
@endsection
