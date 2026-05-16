@extends('layouts.app')

@section('page-header', ' Feedback')

@section('css_after')
    <link href="{{ asset ('metronic/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset ('metronic/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{ asset ('metronic/js/datatable.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var table = $('#feedbackTable').DataTable();
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var selected = $('#ratingFilter').val();
                    var rating = $(table.row(dataIndex).node()).find('td[data-rating]').data('rating');

                    if (selected === "all" || selected == rating) {
                        return true;
                    }
                    return false;
                }
            );
            $('#ratingFilter').on('change', function () {
                table.draw();
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">Learning Materials List</h3>
                {{-- <span class="text-muted mt-1 fw-semibold fs-7">List of all student ratings and comments</span> --}}
            </div>
        </div>

        <div class="card-body py-4">
            <table class="m-datatable  align-middle table-row-dashed fs-6 gy-5" id="kt_feedback_table">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Student</th>
                        <th class="min-w-150px">Class Name</th>
                        <th class="min-w-125px">Rating</th>
                        <th class="min-w-250px">Comment</th>
                        <th class="min-w-100px text-end">Date</th>
                    </tr>
                </thead>
                {{-- <tbody class="text-gray-600 fw-semibold">
                    @foreach($feedbacks as $fb)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle me-3">
                                    <span class="symbol-label bg-light-primary text-primary fw-bold">
                                        {{ substr($fb->student->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $fb->student->name }}</span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge badge-light-dark fw-bold px-4 py-3">
                                {{ $fb->class->class_name ?? 'General Feedback' }}
                            </span>
                        </td>

                        <td>
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

                        <td class="text-end">
                            {{ $fb->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody> --}}

                <tbody class="text-gray-600 fw-semibold">
                    @php
                        // Simulated hardcoded data for presentation
                        $hardcodedFeedbacks = [
                            ['name' => 'Siti Aminah', 'class' => 'Mathematics Form 5', 'rating' => 5, 'comment' => 'Tutor explains very clearly, I finally understand Calculus!', 'date' => '15 May 2026'],
                            ['name' => 'Tan Wei Meng', 'class' => 'Physics Year 1', 'rating' => 4, 'comment' => 'Good materials provided, very helpful for revision.', 'date' => '14 May 2026'],
                            ['name' => 'Musa Ibrahim', 'class' => 'Biology Form 4', 'rating' => 2, 'comment' => 'The teaching speed is a bit too fast for me.', 'date' => '12 May 2026'],
                            ['name' => 'Nurul Izzah', 'class' => 'Chemistry Form 5', 'rating' => 5, 'comment' => 'Best tutor ever! The experiments are fun.', 'date' => '10 May 2026'],
                            ['name' => 'Karthik Raja', 'class' => 'Additional Mathematics', 'rating' => 3, 'comment' => 'I hope we can have more practice questions.', 'date' => '08 May 2026'],
                            ['name' => 'Alya Nadhirah', 'class' => 'English Proficiency', 'rating' => 5, 'comment' => 'Very interactive session. My speaking skills improved.', 'date' => '05 May 2026'],
                            ['name' => 'Chong Kah Mun', 'class' => 'Mathematics Form 5', 'rating' => 4, 'comment' => 'Overall good, but the classroom was a bit noisy.', 'date' => '03 May 2026'],
                            ['name' => 'Syarifuddin Ali', 'class' => 'History Form 3', 'rating' => 1, 'comment' => 'The tutor was late for 15 minutes.', 'date' => '01 May 2026'],
                            ['name' => 'Fatin Nabilla', 'class' => 'Physics Year 1', 'rating' => 5, 'comment' => 'Highly recommended for anyone struggling with mechanics.', 'date' => '28 Apr 2026'],
                            ['name' => 'Lim Jun Jie', 'class' => 'Chemistry Form 5', 'rating' => 4, 'comment' => 'Satisfied with the delivery method.', 'date' => '25 Apr 2026'],
                        ];
                    @endphp

                    @foreach($hardcodedFeedbacks as $data)
                    <tr>
                        {{-- Student Column --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle me-3">
                                    <span class="symbol-label bg-light-primary text-primary fw-bold">
                                        {{ substr($data['name'], 0, 1) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $data['name'] }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Class Column --}}
                        <td>
                            <span class="badge badge-light-dark fw-bold px-4 py-3">
                                {{ $data['class'] }}
                            </span>
                        </td>

                        {{-- Rating Column (Duotone Stars) --}}
                        <td>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ki-duotone ki-star fs-2 {{ $i <= $data['rating'] ? 'text-warning' : 'text-gray-300' }}">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                @endfor
                            </div>
                        </td>

                        {{-- Comment Column --}}
                        <td>
                            <div class="text-gray-800">{{ $data['comment'] }}</div>
                        </td>

                        {{-- Date Column --}}
                        <td class="text-end">
                            {{ $data['date'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
