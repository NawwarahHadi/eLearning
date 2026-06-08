@extends('layouts.app')

@section('title', 'Admin - Feedback Management')
@section('page-header', 'Feedback ')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card card-flush">

        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3 class="fw-bold m-0 fs-2">Feedback</h3>
            </div>
        </div>

        <div class="card-body pt-0">
            @if($feedbacks->isEmpty())
                <div class="text-center py-10">
                    <p class="text-muted fs-4">No feedback entries found matching this cycle.</p>
                </div>
            @else
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_feedback_table">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th >Student</th>
                            {{-- <th class="min-w-125px">Class</th> --}}
                            <th >Tutor</th>
                            <th >Rating</th>
                            <th >Comment</th>
                            {{-- <th class="min-w-125px text-end">Submitted On</th> --}}
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($feedbacks as $feedback)
                            <tr>
                                <td>
                                    <span class="text-gray-800 text-hover-primary fw-bold">
                                        {{ $feedback->student->name ?? 'Unknown Student' }}
                                    </span>
                                </td>

                                {{-- <td>
                                    <span class="badge badge-light-primary fs-7">
                                        {{ $feedback->class->class_name ?? 'N/A' }}
                                    </span>
                                </td> --}}

                                <td>
                                    <span class="text-gray-800 fw-bold">
                                        {{ $feedback->tutor->name ?? 'Unknown Tutor' }}
                                    </span>
                                </td>

                                <td>
                                    <div >
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-gray-300' }} fs-6"></i>
                                        @endfor
                                    </div>
                                    {{-- <span class="text-muted d-block fs-8 mt-1">({{ $feedback->rating }} / 5)</span> --}}
                                </td>

                                <td>
                                    <div class="text-gray-800 max-w-250px text-truncate" title="{{ $feedback->comment }}">
                                        {{ Str::limit($feedback->comment, 60, '...') }}
                                    </div>
                                </td>

                                {{-- <td class="text-end">
                                    <span class="text-gray-600 fw-bold fs-7">
                                        {{ $feedback->created_at->format('Y-m-d H:i') }}
                                    </span>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if(document.getElementById('kt_feedback_table')) {
                $('#kt_feedback_table').DataTable({
                    "info": false,
                    'order': [],
                    'pageLength': 10,
                });
            }
        });
    </script>
@endsection
