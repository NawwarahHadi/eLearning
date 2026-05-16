@extends('layouts.app')

@section('title', 'Create Class')

@section('page-header', 'Create Class')

{{-- @section('breadcrumbs', Breadcrumbs::render('permohonan-peserta')) --}}

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/js/button_loading.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

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
                <h3 class="fw-bold m-0">Class </h3>
            </div>
        </div>
        <form method="post" action="{{route ('class.store')}}">
            @csrf
            @include('class-management._form')
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
                        please wait <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
