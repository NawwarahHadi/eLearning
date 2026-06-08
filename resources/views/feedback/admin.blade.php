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
            {{-- Hardcoded 10 entries for testing layouts --}}
            @php
                $mockFeedbacks = [
                    (object)[ 'student' => (object)['name' => 'Alice Johnson'], 'tutor' => (object)['name' => 'Dr. Smith'], 'rating' => 5, 'comment' => 'The tutor was incredibly patient and broke down complex calculus concepts seamlessly.' ],
                    (object)[ 'student' => (object)['name' => 'Michael Chang'], 'tutor' => (object)['name' => 'Prof. Davis'], 'rating' => 4, 'comment' => 'Great session on organic chemistry, though we ran out of time for the last lab assignment question.' ],
                    (object)[ 'student' => (object)['name' => 'Emma Watson'], 'tutor' => (object)['name' => 'Sarah Jenkins'], 'rating' => 5, 'comment' => 'Excellent feedback on my essay structure. My academic writing has improved significantly.' ],
                    (object)[ 'student' => (object)['name' => 'Liam O\'Connor'], 'tutor' => (object)['name' => 'Dr. Smith'], 'rating' => 3, 'comment' => 'The explanation was okay, but the microphone quality made it a bit difficult to follow along clearly.' ],
                    (object)[ 'student' => (object)['name' => 'Sophia Rodriguez'], 'tutor' => (object)['name' => 'Robert Chen'], 'rating' => 5, 'comment' => 'Phenomenal Python tutor! Helped me debug my loop logic in minutes and explained the why behind it.' ],
                    (object)[ 'student' => (object)['name' => 'James Wilson'], 'tutor' => (object)['name' => 'Prof. Davis'], 'rating' => 2, 'comment' => 'Tutor arrived 15 minutes late and didn\'t seem prepared for the specific topic I requested beforehand.' ],
                    (object)[ 'student' => (object)['name' => 'Olivia Martinez'], 'tutor' => (object)['name' => 'Sarah Jenkins'], 'rating' => 4, 'comment' => 'Very clear explanation of macroeconomics. A few more real-world examples would make it a 5-star session.' ],
                    (object)[ 'student' => (object)['name' => 'William Taylor'], 'tutor' => (object)['name' => 'Robert Chen'], 'rating' => 5, 'comment' => 'Database normalization finally clicked for me after this lesson. Absolute lifesaver!' ],
                    (object)[ 'student' => (object)['name' => 'Zoe Tanaka'], 'tutor' => (object)['name' => 'Dr. Smith'], 'rating' => 4, 'comment' => 'Solid physics review session. Friendly, structured, and comprehensive.' ],
                    (object)[ 'student' => (object)['name' => 'Ethan Hunt'], 'tutor' => (object)['name' => 'Robert Chen'], 'rating' => 1, 'comment' => 'The session was canceled at the last minute with no explanation given. Very disappointed.' ]
                ];
            @endphp

            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_feedback_table">
                <thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th>Student</th>
                        <th>Tutor</th>
                        <th>Rating</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach($mockFeedbacks as $feedback)
                        <tr>
                            <td>
                                <span class="text-gray-800 text-hover-primary fw-bold">
                                    {{ $feedback->student->name }}
                                </span>
                            </td>

                            <td>
                                <span class="text-gray-800 fw-bold">
                                    {{ $feedback->tutor->name }}
                                </span>
                            </td>

                            <td>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-gray-300' }} fs-6"></i>
                                    @endfor
                                </div>
                            </td>

                            <td>
                                <div class="text-gray-800 max-w-250px text-truncate" title="{{ $feedback->comment }}">
                                    {{ Str::limit($feedback->comment, 60, '...') }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
