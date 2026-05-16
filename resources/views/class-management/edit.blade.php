@extends('layouts.app')

@section('title', 'Edit Class')
@section('page-header', 'Class Management')

@section('js_after')
    <script src="{{ asset('metronic/js/button_loading.js') }}"></script>
    <script>
        // Logic to dynamic add/remove schedules could go here if needed
        $(document).on('click', '.hapus-data', function(e) {
            e.preventDefault();
            let url = $(this).attr("href");
            Swal.fire({
                title: 'Warning!',
                text: 'Are you sure you want to delete this class?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                customClass: { confirmButton: "btn btn-primary", cancelButton: "btn btn-danger" }
            }).then((result) => { if (result.value) { window.location.href = url; } });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header border-0">
            <div class="card-title">
                <h3 class="fw-bold m-0">Edit Class Details</h3>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('class.update', $class->id) }}">
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

                {{-- <div class="card-footer d-flex justify-content-end py-6">
                    <a href="{{ route('class.index') }}" class="btn btn-light me-3">Cancel</a>
                    <button type="submit" class="btn btn-success button-loading">
                        <span class="indicator-label">Update Class</span>
                        <span class="indicator-progress">Processing... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div> --}}
            </form>
        </div>
    </div>
</div>
@endsection
