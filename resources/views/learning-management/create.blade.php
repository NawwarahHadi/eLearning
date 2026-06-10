@extends('layouts.app')

@section('title', 'Upload Learning Materials')

@section('page-header', 'Upload Learning Materials')

{{-- @section('breadcrumbs', Breadcrumbs::render('permohonan-peserta')) --}}

@section('css_after')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"rel="stylesheet" />

@endsection

@section('js_after')
    <script src="{{ asset('metronic/js/button_loading.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileRename);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        // Get all file inputs with class "lampiran"
        const allInputs = document.querySelectorAll('.lampiran');

        allInputs.forEach(input => {
            FilePond.create(input, {
                storeAsFile: true,
                server: {
                    process: '{{ route('learning-material.upload') }}',
                    revert: '{{ route('learning-material.revert') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });
        });
    </script>
   <script>
        $(document).ready(function () {
            // Initialize timepicker for the very first row
            $(".kt_timepicker").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });

            // Initialize the Repeater
            $('#kt_repeater_schedule').repeater({
                initEmpty: false,

                show: function () {
                    $(this).slideDown();

                    // Re-init timepicker for the NEW row specifically
                    $(this).find('.kt_timepicker').flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true
                    });
                },

                hide: function (deleteElement) {
                    if(confirm('Are you sure you want to remove this time slot?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });
        });
    </script>

@endsection
@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" aria-expanded="true">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Learning Materials </h3>
            </div>
        </div>
        <form method="post" action="{{route ('learning-material.store')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class_id }}">
            @include('learning-management._form')
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="submit" class="btn btn-success button-loading">
                    <i class="ki-duotone ki-send">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <span class="indicator-label">
                       Save
                    </span>
                    <span class="indicator-progress">
                        please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
