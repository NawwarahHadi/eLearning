@extends('layouts.app')

@section('title', 'List of Class')

@section('page-header', 'My Enrolled Classes')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>

    <script>
        // Populate modal when opened from any reschedule button
        const rescheduleModal = document.getElementById('rescheduleModal');
        if (rescheduleModal) {
            rescheduleModal.addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                document.getElementById('rs-tutor-id').value   = btn.getAttribute('data-tutor-id');
                document.getElementById('rs-tutor-name').value = btn.getAttribute('data-tutor-name');
                document.getElementById('rs-class-id').value   = btn.getAttribute('data-class-id');
                document.getElementById('rs-class-name').value = btn.getAttribute('data-class-name');
                document.getElementById('rs-time').value = '';
                document.getElementById('rs-reason').value = '';
                document.getElementById('rescheduleModalLabel').textContent =
                    '📅 Reschedule with ' + btn.getAttribute('data-tutor-name');
            });
        }

        function sendRescheduleAndRedirect() {
            const tutorId   = document.getElementById('rs-tutor-id').value;
            const classId   = document.getElementById('rs-class-id').value;
            const className = document.getElementById('rs-class-name').value;
            const time      = document.getElementById('rs-time').value;
            const reason    = document.getElementById('rs-reason').value;

            if (!time || !reason) {
                alert("Please complete all fields.");
                return;
            }

            const payload = "[RESCHEDULE_REQUEST]" + JSON.stringify({
                class_id: classId,
                class_name: className,
                time: time.replace('T', ' '),
                reason: reason
            });

            const submitBtn = document.getElementById('rs-submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            axios.post('/chat/send', { receiver_id: tutorId, message: payload })
                .then(() => {
                    // redirect into the chat with this tutor
                    window.location.href = `/chat/${tutorId}`;
                })
                .catch(err => {
                    console.error(err);
                    alert("Could not send request.");
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send & Open Chat';
                });
        }
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">My Classes</h3>
            </div>
        </div>

        <div class="card-body py-4">
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-150px">Class</th>
                        <th class="min-w-150px"> Tutor</th>
                        <th class="min-w-200px">Schedule</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-150px">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach($enrolledClasses as $classId => $group)
                        @php
                            $firstItem = $group->first();
                        @endphp
                        <tr>
                            {{-- CLASS NAME --}}
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold fs-6">{{ $firstItem->class->subject->name }}</span>
                                    <span class="text-muted fs-7">{{ $firstItem->class->category_code }}</span>
                                </div>
                            </td>

                            {{-- TUTOR NAME --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-7">{{ $firstItem->tutor->name }}</span>
                                        <span class="text-muted fs-8">{{ $firstItem->tutor->email }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- SELECTED SCHEDULES --}}
                            <td>
                                @foreach($group as $enrollment)
                                    @if($enrollment->schedule)
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="ki-duotone ki-time fs-2 text-primary me-2">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            <span class="fs-7">
                                                <strong class="text-gray-800">{{ $enrollment->schedule->day }}:</strong>
                                                {{ \Carbon\Carbon::parse($enrollment->schedule->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($enrollment->schedule->end_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge badge-light-success fs-7 fw-bold">Enrolled</span>
                            </td>

                            {{-- ACTIONS --}}
                            <td>
                                <a href="{{ route('change-tutor.index', $firstItem->class_id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Change Tutor">
                                    <i class="ki-duotone ki-update-file text-info fs-1">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                    </i>
                                </a>

                                <a href="{{ route('student.class.materials', $firstItem->class_id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Learning Material">
                                    <i class="ki-duotone ki-some-files text-success fs-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </a>

                                <a href="{{ route('feedback.index', $firstItem->class_id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Rate">
                                    <i class="ki-duotone ki-dots-circle text-warning fs-1">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                    </i>
                                </a>

                                {{-- RESCHEDULE: open modal, send request, redirect to chat --}}
                                <button type="button"
                                        class="btn btn-icon btn-bg-light btn-active-color-success btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rescheduleModal"
                                        data-tutor-id="{{ $firstItem->tutor_id }}"
                                        data-tutor-name="{{ $firstItem->tutor->name }}"
                                        data-class-id="{{ $firstItem->class_id }}"
                                        data-class-name="{{ $firstItem->class->subject->name }}"
                                        title="Reschedule with {{ $firstItem->tutor->name }}">
                                    <i class="ki-duotone ki-message-text-2 fs-1">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                    </i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Shared Reschedule Modal (single instance for the whole table) --}}
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="rescheduleModalLabel">📅 Propose Reschedule Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rs-tutor-id">
                <input type="hidden" id="rs-tutor-name">
                <input type="hidden" id="rs-class-id">
                <input type="hidden" id="rs-class-name">

                <div class="mb-3">
                    <label class="form-label fw-bold">Proposed New Date & Time</label>
                    <input type="datetime-local" class="form-control" id="rs-time" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Reason for Absence</label>
                    <textarea class="form-control" id="rs-reason" rows="3" placeholder="State your conflict details..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning fw-bold text-dark" id="rs-submit" onclick="sendRescheduleAndRedirect()">
                    Send &amp; Open Chat
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- <a href="{{ route('chat.show', $firstItem->tutor_id) }}"
        class="btn btn-icon btn-bg-light btn-active-color-success btn-sm"
        data-bs-toggle="tooltip"
        title="Chat with {{ $firstItem->tutor->name }}">
            <i class="ki-duotone ki-message-text-2 fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
    </a> --}}
