@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        {{-- 1. Header Display Panel --}}
        <div class="card-header bg-success d-flex justify-content-between align-items-center">
            <h3 class="card-title text-white mb-0">Chatting with {{ $receiver->name }}</h3>

            {{-- Render Reschedule Request Button ONLY if the logged-in user is a Student --}}
            @if(Auth::user()->role === 'student')
                <button type="button" class="btn btn-warning btn-sm font-weight-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#rescheduleModal">
                    📅 Request Reschedule
                </button>
            @endif
        </div>

        {{-- 2. Message Stream Container --}}
        <div class="card-body" id="chat-messages" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
            @foreach($messages as $msg)
                <div class="d-flex {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }} mb-4" id="msg-container-{{ $msg->id }}">
                    <div class="{{ $msg->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-dark border' }} p-3 rounded shadow-sm" style="max-width: 70%; min-width: 280px;">

                        {{-- Parse Custom Structural System Messages --}}
                        @if(str_contains($msg->message, '[RESCHEDULE_REQUEST]'))
                            @php
                                $cleanMsg = str_replace('[RESCHEDULE_REQUEST]', '', $msg->message);
                                $details = json_decode($cleanMsg, true) ?? [];
                            @endphp
                            <div class="text-dark">
                                <div class="d-flex align-items-center justify-content-between text-danger mb-1">
                                    <h6 class="font-weight-bold mb-0">⚠️ Reschedule Proposal</h6>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                                <hr class="my-1">
                                <small><strong>Class:</strong> {{ $details['class_name'] ?? 'Selected Module' }}</small><br>
                                <small><strong>Proposed Time:</strong> {{ $details['time'] ?? 'N/A' }}</small><br>
                                <small><strong>Reason:</strong> {{ $details['reason'] ?? 'No reason provided' }}</small>

                                {{-- Display Action Hooks to Receiving Tutor Only --}}
                                @if(Auth::user()->role === 'tutor' && $msg->sender_id != Auth::id())
                                    <div class="mt-2 d-flex justify-content-end gap-2" id="actions-{{ $msg->id }}">
                                        <button class="btn btn-sm btn-success me-2" onclick="handleReschedule({{ $msg->id }}, 'approve')">Approve</button>
                                        <button class="btn btn-sm btn-danger" onclick="handleReschedule({{ $msg->id }}, 'reject')">Reject</button>
                                    </div>
                                @endif
                            </div>

                        @elseif(str_contains($msg->message, '[RESCHEDULE_RESOLVED_APPROVED]'))
                            @php
                                $cleanMsg = str_replace('[RESCHEDULE_RESOLVED_APPROVED]', '', $msg->message);
                                $details = json_decode($cleanMsg, true) ?? [];
                            @endphp
                            <div class="text-muted">
                                <h6 class="font-weight-bold text-success mb-1">✅ Reschedule Approved</h6>
                                <hr class="my-1">
                                <small><strong>Class:</strong> {{ $details['class_name'] ?? 'N/A' }}</small><br>
                                <small><strong>New Makeup Slot:</strong> {{ $details['time'] ?? 'N/A' }}</small><br>
                                <span class="badge bg-success text-white mt-2 d-block text-center">Private 1-to-1 Makeup Session Generated</span>
                            </div>

                        @elseif(str_contains($msg->message, '[RESCHEDULE_RESOLVED_REJECTED]'))
                            <div class="text-muted">
                                <h6 class="font-weight-bold text-secondary mb-1">❌ Reschedule Declined</h6>
                                <hr class="my-1">
                                <p class="small mb-0 text-italic">This schedule alteration proposal was rejected by the instructor.</p>
                            </div>

                        @else
                            {{-- Output standard message components --}}
                            <span>{{ $msg->message }}</span>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

        {{-- 3. Footer Communication Controls --}}
        <div class="card-footer">
            <div class="input-group">
                <input type="hidden" id="receiver_id" value="{{ $receiver->id }}">

                <input type="text"
                       id="message-input"
                       class="form-control"
                       placeholder="Type your message..."
                       autocomplete="off">

                <button type="button" class="btn btn-success" id="send-button" onclick="sendMessage()">
                    Send
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 🌟 Student Reschedule Capture Modal Box (Bootstrap 5 Compatible) --}}
@if(Auth::user()->role === 'student')
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold" id="rescheduleModalLabel">📅 Propose Reschedule Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rescheduleForm">
                    <div class="form-group mb-3">
                        <label for="modal-class-id" class="form-label font-weight-bold">Select Target Class</label>
                        <select class="form-control" id="modal-class-id" required>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->subject->name ?? 'Class Slot' }} (Code: {{ $class->class_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="modal-time" class="form-label font-weight-bold">Proposed New Date & Time</label>
                        <input type="datetime-local" class="form-control" id="modal-time" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="modal-reason" class="form-label font-weight-bold">Reason for Absence</label>
                        <textarea class="form-control" id="modal-reason" rows="3" placeholder="State your physical emergency or conflict details..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning font-weight-bold text-dark" onclick="submitRescheduleRequest()">Submit Request</button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    const currentUserId = {{ Auth::id() }};
    const receiverId = {{ $receiver->id }};
    const chatWindow = document.getElementById('chat-messages');

    // Auto Scroll Lifecycle
    chatWindow.scrollTop = chatWindow.scrollHeight;

    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value;
        if (messageText.trim() === '') return;

        messageInput.disabled = true;
        postMessagePayload(messageText, () => {
            messageInput.value = '';
            messageInput.disabled = false;
            messageInput.focus();
            appendMessage(messageText, 'sender');
        });
    }

    function postMessagePayload(textString, callbackSuccess) {
        axios.post('/chat/send', {
            receiver_id: receiverId,
            message: textString
        })
        .then(response => { callbackSuccess(); })
        .catch(error => {
            console.error(error);
            document.getElementById('message-input').disabled = false;
            alert("Could not process transmission payload.");
        });
    }

    /**
     * Packages the modal choices and sends it as a structured string message
     */
    function submitRescheduleRequest() {
        const classSelect = document.getElementById('modal-class-id');
        if (!classSelect) {
            alert("No active classes found with this tutor to reschedule.");
            return;
        }

        const className = classSelect.options[classSelect.selectedIndex].text;
        const classId = classSelect.value;
        const timeInput = document.getElementById('modal-time').value;
        const reasonInput = document.getElementById('modal-reason').value;

        if (!timeInput || !reasonInput) {
            alert("Please complete all fields.");
            return;
        }

        const structuredPayload = "[RESCHEDULE_REQUEST]" + JSON.stringify({
            class_id: classId,
            class_name: className,
            time: timeInput.replace('T', ' '),
            reason: reasonInput
        });

        postMessagePayload(structuredPayload, () => {
            // Close Bootstrap 5 Modal programmatically
            const modalEl = document.getElementById('rescheduleModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalInstance.hide();

            appendMessage(structuredPayload, 'sender');
        });
    }

    /**
 * Dispatches the approve/reject state mutation request via Axios
 */
    /**
 * Dispatches the approve/reject state mutation request via Axios automatically
 */
    function handleReschedule(messageId, actionType) {
        const actionContainer = document.getElementById(`actions-${messageId}`);

        // Simple confirmation check box fallback
        if (!confirm(`Are you sure you want to ${actionType} this class reschedule request?`)) {
            return;
        }

        actionContainer.innerHTML = `<small class="text-muted font-italic">Processing state update...</small>`;

        axios.post(`/chat/reschedule/${actionType}`, {
            message_id: messageId
            // Notice: zoom_link is no longer passed from the front end. Server takes over!
        })
        .then(response => {
            if (actionType === 'approve') {
                actionContainer.innerHTML = `<span class="badge bg-success p-2">✅ Approved - Zoom Room Spawned</span>`;
            } else {
                actionContainer.innerHTML = `<span class="badge bg-secondary p-2">❌ Declined</span>`;
            }
        })
        .catch(error => {
            console.error(error);
            actionContainer.innerHTML = `<span class="badge bg-danger p-2">⚠️ Error occurred</span>`;
        });
    }

    function appendMessage(text, type) {
        const isSender = (type === 'sender');
        const alignment = isSender ? 'justify-content-end' : 'justify-content-start';
        const bgClass = isSender ? 'bg-primary text-white' : 'bg-white text-dark border';

        let innerContent = `<span>${text}</span>`;

        if (text.includes('[RESCHEDULE_REQUEST]')) {
            const cleanText = text.replace('[RESCHEDULE_REQUEST]', '');
            const parsed = JSON.parse(cleanText) || {};
            innerContent = `
                <div class="text-dark">
                    <h6 class="font-weight-bold text-danger mb-1">📅 Reschedule Proposal Pending</h6>
                    <hr class="my-1">
                    <small><strong>Class:</strong> ${parsed.class_name}</small><br>
                    <small><strong>Proposed Time:</strong> ${parsed.time}</small><br>
                    <small><strong>Reason:</strong> ${parsed.reason}</small>
                </div>
            `;
        } else if (text.includes('[RESCHEDULE_RESOLVED_APPROVED]')) {
            const cleanText = text.replace('[RESCHEDULE_RESOLVED_APPROVED]', '');
            const parsed = JSON.parse(cleanText) || {};
            innerContent = `
                <div class="text-muted">
                    <h6 class="font-weight-bold text-success mb-1">✅ Reschedule Approved</h6>
                    <hr class="my-1">
                    <small><strong>Class:</strong> ${parsed.class_name}</small><br>
                    <small><strong>New Makeup Slot:</strong> ${parsed.time}</small><br>
                </div>
            `;
        } else if (text.includes('[RESCHEDULE_RESOLVED_REJECTED]')) {
            innerContent = `
                <div class="text-muted">
                    <h6 class="font-weight-bold text-secondary mb-1">❌ Reschedule Declined</h6>
                </div>
            `;
        }

        const newMessageHtml = `
            <div class="d-flex ${alignment} mb-4">
                <div class="${bgClass} p-3 rounded shadow-sm" style="max-width: 70%; min-width: 280px;">
                    ${innerContent}
                </div>
            </div>
        `;

        chatWindow.insertAdjacentHTML('beforeend', newMessageHtml);
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    // Real-Time Pusher Handshake Listeners
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Echo !== 'undefined') {
            window.Echo.private(`chat.${currentUserId}`)
            .listen('.App.Events.MessageSent', (e) => {
                appendMessage(e.message.message, 'receiver');
            })
            .listen('MessageSent', (e) => {
                appendMessage(e.message.message, 'receiver');
            });
        }
    });

    document.getElementById('message-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') sendMessage();
    });
</script>
@endsection
