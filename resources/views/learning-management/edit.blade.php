@extends('layouts.app')

@section('title', 'Edit Learning Material')
@section('page-header', 'Learning Management')

{{-- @section('breadcrumbs', Breadcrumbs::render('learning-material-edit', $learningMaterial->id)) --}}

@section('css_after')
    {{-- FilePond CSS --}}
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/js/button_loading.js') }}"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

    {{-- SweetAlert for deleting physical files --}}
    <script>
        $(document).on('click', '.hapus-data', function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Warning!',
                text: 'Are you sure you want to remove this file permanently?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it',
                customClass: { confirmButton: "btn btn-primary", cancelButton: "btn btn-danger" }
            }).then((result) => {
                if (result.value) { window.location.href = $(this).attr("href"); }
            });
        });
    </script>

    {{-- FilePond Setup --}}
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        const allInputs = document.querySelectorAll('.upload-pond');
        allInputs.forEach(input => {
            FilePond.create(input, {
                storeAsFile: true,
                maxFileSize: '100MB',
                server: {
                    process: '{{ route('learning-material.upload') }}',
                    revert: '{{ route('learning-material.revert') }}',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="row g-5 g-xl-10">

        <div class="col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" aria-expanded="true">
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Learning Material Details</h3>
                    </div>
                </div>

                <div class="card-body border-top p-9">
                    <form method="post" action="{{ route('learning-material.update', $learningMaterial->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class_id }}">

                        <div class="d-flex flex-column mb-6">
                            <label class="fs-5 fw-semibold mb-2 required">Topic</label>
                            <input type="text" name="topic" class="form-control form-control-lg form-control-solid @error('topic') is-invalid @enderror" value="{{ old('topic', $learningMaterial->topic) }}">
                            @error('topic') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-6">
                                <label class="fs-5 fw-semibold mb-2 required">Week</label>
                                <input type="number" name="week" class="form-control form-control-lg form-control-solid @error('week') is-invalid @enderror" value="{{ old('week', $learningMaterial->week) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="fs-5 fw-semibold mb-2 required">Class Date</label>
                                <input type="date" name="class_date" class="form-control form-control-lg form-control-solid @error('class_date') is-invalid @enderror" value="{{ old('class_date', $learningMaterial->class_date) }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="fs-5 fw-semibold mb-2">Class Recording Video</label>
                            <input type="file" name="recording_file" class="upload-pond" accept="video/*">
                        </div>
                        <div class="separator separator-dashed my-6"></div>

                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold mb-2">Lecture Note (Replace)</label>
                                <input type="file" name="lecture_note" class="upload-pond" accept=".pdf,.ppt,.pptx">
                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold mb-2">Exercise (Replace)</label>
                                <input type="file" name="exercise" class="upload-pond" accept=".pdf,.doc,.docx">
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end py-6 px-0 mt-5">
                            <button type="submit" class="btn btn-success button-loading">
                                <i class="ki-duotone ki-send"><span class="path1"></span><span class="path2"></span></i>
                                <span class="indicator-label">Update All</span>
                                <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow-sm mb-5">
                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Stored Attachments</h3>
                    </div>
                </div>
                <div class="card-body py-3">
                    @if($learningMaterial->recording_file || $learningMaterial->lecture_note || $learningMaterial->exercise)
                        <div class="table-responsive">
                            <table class="table align-middle gs-0 gy-5">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th style="width: 70%;">Type</th>
                                        <th class="text-end" style="width: 30%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">


                                    @if($learningMaterial->recording_file)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            <span class="badge badge-light-primary fs-7" data-bs-toggle="tooltip" title="{{ basename($learningMaterial->recording_file) }}">
                                                Recording Video
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ asset('storage/'.$learningMaterial->recording_file) }}" target="_blank" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1" data-bs-toggle="tooltip" title="Watch Video">
                                                <i class="ki-duotone text-info ki-video fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </a>
                                            {{-- Optional: Route to delete only this file --}}
                                            <a href="{{ route('learning-material.file-delete', [$learningMaterial->id, 'note']) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm hapus-data" data-bs-toggle="tooltip" title="Remove Video">
                                                <i class="ki-duotone text-danger ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif

                                    {{-- 2. Lecture Note Row --}}
                                    @if($learningMaterial->lecture_note)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            <span class="badge badge-light-success fs-7" data-bs-toggle="tooltip" title="{{ basename($learningMaterial->lecture_note) }}">
                                                Lecture Note
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('learning-material.open-file', [$learningMaterial->id, 'note']) }}" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1" data-bs-toggle="tooltip" title="Open Note">
                                                <i class="ki-duotone text-info ki-search-list fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </a>
                                            <a href="{{ route('learning-material.file-delete', [$learningMaterial->id, 'note']) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm hapus-data" data-bs-toggle="tooltip" title="Remove Note">
                                                <i class="ki-duotone text-danger ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif

                                    {{-- 3. Exercise Row --}}
                                    @if($learningMaterial->exercise)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            <span class="badge badge-light-warning fs-7" data-bs-toggle="tooltip" title="{{ basename($learningMaterial->exercise) }}">
                                                Exercise
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('learning-material.open-file', [$learningMaterial->id, 'exercise']) }}" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1" data-bs-toggle="tooltip" title="Open Exercise">
                                                <i class="ki-duotone text-info ki-search-list fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </a>
                                            <a href="{{ route('learning-material.file-delete', [$learningMaterial->id, 'note']) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm hapus-data" data-bs-toggle="tooltip" title="Remove Exercise">
                                                <i class="ki-duotone text-danger ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    @else
                        <label class="fs-5 text-muted fw-semibold mb-2 p-4">No documents attached.</label>
                    @endif
                </div>
            </div>

            <div class="card bg-light-danger border-danger border border-dashed">
                <div class="card-body p-6 text-center">
                    <h4 class="text-danger fw-bold">Danger Zone</h4>
                    <p class="fs-7 text-gray-600">Permanently remove this entire lesson and all files.</p>
                    <a href="{{ route('learning-material.destroy', $learningMaterial->id) }}" class="btn btn-danger btn-sm hapus-data w-100">Delete All Material</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
