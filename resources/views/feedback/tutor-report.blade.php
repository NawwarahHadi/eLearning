@extends('layouts.app')

@section('page-header', 'Tutor Feedback Report')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">

        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">Student Feedback Overview</h3>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="me-3 fw-bold text-gray-600">Filter Rating:</span>
                    <select id="ratingFilter" class="form-select form-select-solid form-select-sm w-125px">
                        <option value="all">All Ratings</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body py-4">
            {{-- Hardcoded 10 feedback records for UI rendering --}}
            @php
                $feedbacks = [
                    (object)[ 'student' => (object)['name' => 'Amelia Earhart'], 'rating' => 5, 'comment' => 'Outstanding structure! The lesson map was clear and easy to follow.', 'created_at' => now()->subDays(1) ],
                    (object)[ 'student' => (object)['name' => 'Brad Pitt'], 'rating' => 4, 'comment' => 'Very insightful session. Clear presentation, just a bit fast on the equations.', 'created_at' => now()->subDays(2) ],
                    (object)[ 'student' => (object)['name' => 'Charlie Chaplin'], 'rating' => 3, 'comment' => 'The topic breakdown was good, but the audio connection dropped out several times.', 'created_at' => now()->subDays(3) ],
                    (object)[ 'student' => (object)['name' => 'Diana Prince'], 'rating' => 5, 'comment' => 'Excellent examples used throughout. Highly recommend this tutor for algorithm prep!', 'created_at' => now()->subDays(4) ],
                    (object)[ 'student' => (object)['name' => 'Evan Wright'], 'rating' => 2, 'comment' => 'The tutor seemed distracted and the session started 10 minutes late.', 'created_at' => now()->subDays(5) ],
                    (object)[ 'student' => (object)['name' => 'Fiona Gallagher'], 'rating' => 4, 'comment' => 'Very useful code review session. Appreciate the follow-up resources provided.', 'created_at' => now()->subDays(6) ],
                    (object)[ 'student' => (object)['name' => 'George Clooney'], 'rating' => 5, 'comment' => 'Brilliant teaching strategy. Complex database normalization concepts were simplified perfectly.', 'created_at' => now()->subDays(7) ],
                    (object)[ 'student' => (object)['name' => 'Hannah Baker'], 'rating' => 1, 'comment' => 'Tutor did not show up for the scheduled time and missed my message queries.', 'created_at' => now()->subDays(8) ],
                    (object)[ 'student' => (object)['name' => 'Ian Malcolm'], 'rating' => 4, 'comment' => 'Great pacing and comprehensive answers to all my edge-case theoretical questions.', 'created_at' => now()->subDays(9) ],
                    (object)[ 'student' => (object)['name' => 'Julia Roberts'], 'rating' => 5, 'comment' => 'Fantastic exam revision review! I feel way more confident going into finals tomorrow.', 'created_at' => now()->subDays(10) ]
                ];
            @endphp

            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_feedback_table">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Student</th>
                        <th class="min-w-125px">Rating</th>
                        <th class="min-w-250px">Comment</th>
                        <th class="min-w-100px ">Date</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($feedbacks as $fb)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle me-3">
                                    <span class="symbol-label bg-light-info text-info fw-bold">
                                        {{ substr($fb->student->name ?? 'S', 0, 1) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $fb->student->name ?? 'Unknown Student' }}</span>
                                </div>
                            </div>
                        </td>

                        <td data-rating="{{ $fb->rating }}">
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ki-duotone ki-star fs-2 {{ $i <= $fb->rating ? 'text-warning' : 'text-gray-300' }}">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                @endfor
                            </div>
                        </td>

                        <td>
                            <div class="text-gray-800">{{ $fb->comment ?? 'No comment provided.' }}</div>
                        </td>

                        <td>
                            {{ $fb->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-10">
                            No feedback records mapped to your profile yet.
                        </td>
                    </tr>
                    @endforelse
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
            // Target the unified table instantiation variable correctly
            var table = $('#kt_feedback_table').DataTable({
                "pageLength": 10,
                "order": []
            });

            // DataTables custom internal cell calculation search engine rules
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var selected = $('#ratingFilter').val();
                    // Looks safely into our assigned explicit column data property index
                    var rating = $(table.row(dataIndex).node()).find('td[data-rating]').data('rating');

                    if (selected === "all" || selected == rating) {
                        return true;
                    }
                    return false;
                }
            );

            // Redraw operational view upon interacting with the select collection box
            $('#ratingFilter').on('change', function () {
                table.draw();
            });
        });
    </script>
@endsection
