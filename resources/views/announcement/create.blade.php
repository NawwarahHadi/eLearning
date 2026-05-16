@extends('layouts.app')

@section('title', 'Create Announcement')

@section('page-header', 'Announcement')

{{-- Update breadcrumb link if necessary
@section('breadcrumbs', Breadcrumbs::render('announcement-create')) --}}

@section('js_after')
    <script src="{{ asset('metronic/js/button_loading.js') }}"></script>
@endsection

@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" aria-expanded="true">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Create New Announcement</h3>
                </div>
            </div>
            {{-- Updated route to announcement.store --}}
            <form method="post" action="{{ route('announcement.store') }}">
                @csrf
                @include('announcement._form')

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-success button-loading">
                        <i class="ki-duotone ki-send">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <span class="indicator-label">
                            Submit
                        </span>
                        <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
