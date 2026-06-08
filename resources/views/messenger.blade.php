@extends('layouts.app')

@section('content')
<style>
    .chat-shell { display: flex; height: 78vh; background: #fff; border-radius: 14px;
        box-shadow: 0 2px 14px rgba(0,0,0,.06); overflow: hidden; }
    .chat-sidebar { width: 340px; border-right: 1px solid #eef0f4; display: flex; flex-direction: column; }
    .chat-search { padding: 16px; border-bottom: 1px solid #eef0f4; }
    .chat-search input { border-radius: 10px; background: #f5f7fa; border: none; }
    .contact-list { overflow-y: auto; flex: 1; }
    .contact-item { display: flex; align-items: center; gap: 12px; padding: 14px 16px;
        cursor: pointer; border-bottom: 1px solid #f4f6f9; text-decoration: none; color: inherit; }
    .contact-item:hover { background: #f9fafb; }
    .contact-item.active { background: #eef3ff; }
    .avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
    .contact-meta { flex: 1; min-width: 0; }
    .contact-name { font-weight: 600; color: #1f2937; margin: 0; font-size: .95rem; }
    .contact-email { color: #9aa4b2; font-size: .8rem; margin: 0; white-space: nowrap;
        overflow: hidden; text-overflow: ellipsis; }
    .unread-badge { background: #22c55e; color: #fff; font-size: .72rem; font-weight: 700;
        min-width: 20px; height: 20px; padding: 0 6px; border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .chat-main { flex: 1; display: flex; flex-direction: column; }
    .chat-head { padding: 18px 22px; border-bottom: 1px solid #eef0f4;
        display: flex; align-items: center; justify-content: space-between; }
    .chat-head .name { font-weight: 700; color: #111827; }
    .chat-head .status { color: #22c55e; font-size: .8rem; }
    .chat-head .status .dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%;
        background: #22c55e; margin-right: 5px; }
    #chat-messages { flex: 1; overflow-y: auto; padding: 22px; background: #fff; }
    .msg-row { display: flex; margin-bottom: 18px; }
    .msg-row.me { justify-content: flex-end; }
    .bubble { max-width: 70%; padding: 12px 16px; border-radius: 14px; font-size: .92rem; line-height: 1.4; }
    .bubble.them { background: #f4f1ff; color: #1f2937; border-top-left-radius: 4px; }
    .bubble.me { background: #e7f0ff; color: #1f2937; border-top-right-radius: 4px; }
    .chat-foot { padding: 16px 22px; border-top: 1px solid #eef0f4; }
    .chat-foot .input-group input { border: none; background: #f5f7fa; border-radius: 10px; }
    .send-btn { background: #3b82f6; border: none; border-radius: 10px; padding: 8px 22px; color: #fff; font-weight: 600; }
</style>

<div class="container-fluid">
    <div class="chat-shell">

        {{-- LEFT: Contacts --}}
        <div class="chat-sidebar">
            <div class="chat-search">
                <input type="text" id="contact-search" class="form-control" placeholder="Search by username or email...">
            </div>
            <div class="contact-list" id="contact-list">
                @foreach($users as $user)
                    <a href="{{ route('chat.show', $user->id) }}"
                       class="contact-item {{ $receiver && $user->id == $receiver->id ? 'active' : '' }}"
                       data-contact-id="{{ $user->id }}">
                        <img src="{{ $user->avatar_url }}" class="avatar" alt="{{ $user->name }}">
                        <div class="contact-meta">
                            <p class="contact-name">{{ $user->name }}</p>
                            <p class="contact-email">{{ $user->email }}</p>
                        </div>
                        <span class="unread-badge" id="contact-badge-{{ $user->id }}"
                              style="{{ ($user->unread_count ?? 0) > 0 ? '' : 'display:none;' }}">
                            {{ ($user->unread_count ?? 0) > 0 ? $user->unread_count : '' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Conversation --}}
        <div class="chat-main">
            @if($receiver)
                <div class="chat-head">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $receiver->avatar_url }}" class="avatar" style="width:40px;height:40px;">
                        <div>
                            <div class="name">{{ $receiver->name }}</div>
                            <div class="status"><span class="dot"></span>Active</div>
                        </div>
                    </div>
                    {{-- @if(Auth::user()->role === 'student')
                        <button type="button" class="btn btn-warning btn-sm fw-bold"
                                data-bs-toggle="modal" data-bs-target="#rescheduleModal">
                            📅 Request Reschedule
                        </button>
                    @endif --}}
                </div>

                <div id="chat-messages">
                    @foreach($messages as $msg)
                        <div class="msg-row {{ $msg->sender_id == Auth::id() ? 'me' : 'them' }}" id="msg-container-{{ $msg->id }}">
                            <div class="bubble {{ $msg->sender_id == Auth::id() ? 'me' : 'them' }}">
                                @if(str_contains($msg->message, '[RESCHEDULE_REQUEST]'))
                                    @php $details = json_decode(str_replace('[RESCHEDULE_REQUEST]', '', $msg->message), true) ?? []; @endphp
                                    <div class="d-flex align-items-center justify-content-between text-danger mb-1">
                                        <h6 class="fw-bold mb-0">⚠️ Reschedule Proposal</h6>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </div>
                                    <hr class="my-1">
                                    <small><strong>Class:</strong> {{ $details['class_name'] ?? 'Selected Module' }}</small><br>
                                    <small><strong>Proposed Time:</strong> {{ $details['time'] ?? 'N/A' }}</small><br>
                                    <small><strong>Reason:</strong> {{ $details['reason'] ?? 'No reason provided' }}</small>
                                    @if(Auth::user()->role === 'tutor' && $msg->sender_id != Auth::id())
                                        <div class="mt-2 d-flex justify-content-end gap-2" id="actions-{{ $msg->id }}">
                                            <button class="btn btn-sm btn-success me-2" onclick="handleReschedule({{ $msg->id }}, 'approve')">Approve</button>
                                            <button class="btn btn-sm btn-danger" onclick="handleReschedule({{ $msg->id }}, 'reject')">Reject</button>
                                        </div>
                                    @endif
                                @elseif(str_contains($msg->message, '[RESCHEDULE_RESOLVED_APPROVED]'))
                                    @php $details = json_decode(str_replace('[RESCHEDULE_RESOLVED_APPROVED]', '', $msg->message), true) ?? []; @endphp
                                    <h6 class="fw-bold text-success mb-1">✅ Reschedule Approved</h6>
                                    <hr class="my-1">
                                    <small><strong>Class:</strong> {{ $details['class_name'] ?? 'N/A' }}</small><br>
                                    <small><strong>New Makeup Slot:</strong> {{ $details['time'] ?? 'N/A' }}</small>
                                @elseif(str_contains($msg->message, '[RESCHEDULE_RESOLVED_REJECTED]'))
                                    <h6 class="fw-bold text-secondary mb-1">❌ Reschedule Declined</h6>
                                    <p class="small mb-0">This proposal was rejected by the instructor.</p>
                                @else
                                    <span>{{ $msg->message }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="chat-foot">
                    <div class="input-group">
                        <input type="hidden" id="receiver_id" value="{{ $receiver->id }}">
                        <input type="text" id="message-input" class="form-control" placeholder="Type your message..." autocomplete="off">
                        <button type="button" class="send-btn" id="send-button" onclick="sendMessage()">Send</button>
                    </div>
                </div>
            @else
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                    <i class="ki-duotone ki-messages" style="font-size:3rem;opacity:.3;"></i>
                    <p class="mt-3 fs-5">Select a conversation to start chatting</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Reschedule Modal --}}
{{-- @if(Auth::user()->role === 'student' && $receiver)
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold">📅 Propose Reschedule Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rescheduleForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Target Class</label>
                        <select class="form-control" id="modal-class-id" required>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->subject->name ?? 'Class Slot' }} (Code: {{ $class->class_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Proposed New Date & Time</label>
                        <input type="datetime-local" class="form-control" id="modal-time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Absence</label>
                        <textarea class="form-control" id="modal-reason" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning fw-bold text-dark" onclick="submitRescheduleRequest()">Submit Request</button>
            </div>
        </div>
    </div>
</div>
@endif --}}

<script>
    // ============ ALWAYS-ON (both empty state and conversation) ============
    const myUserId = {{ Auth::id() }};

    @if($receiver)
        window.currentOpenChatId = {{ $receiver->id }};
    @else
        window.currentOpenChatId = null;
    @endif

    // Contact search
    const searchInput = document.getElementById('contact-search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.contact-item').forEach(item => {
                item.style.display = item.innerText.toLowerCase().includes(q) ? 'flex' : 'none';
            });
        });
    }

    // Sidebar badge bump
    function bumpContactBadge(senderId) {
        const badge = document.getElementById(`contact-badge-${senderId}`);
        if (!badge) return;
        let current = parseInt(badge.textContent) || 0;
        badge.textContent = current + 1;
        badge.style.display = '';
    }

    // appendMessage — only does something if a chat window exists
    function appendMessage(text, type, messageId = null) {
        const chatWindow = document.getElementById('chat-messages');
        if (!chatWindow) return;

        const isSender = (type === 'sender');
        const rowClass = isSender ? 'me' : 'them';
        let inner = `<span>${text}</span>`;

        if (text.includes('[RESCHEDULE_REQUEST]')) {
            const p = JSON.parse(text.replace('[RESCHEDULE_REQUEST]', '')) || {};
            const btns = (!isSender && window.currentUserRole === 'tutor' && messageId)
                ? `<div class="mt-2 d-flex justify-content-end gap-2" id="actions-${messageId}">
                       <button class="btn btn-sm btn-success me-2" onclick="handleReschedule(${messageId}, 'approve')">Approve</button>
                       <button class="btn btn-sm btn-danger" onclick="handleReschedule(${messageId}, 'reject')">Reject</button>
                   </div>` : '';
            inner = `<h6 class="fw-bold text-danger mb-1">📅 Reschedule Proposal Pending</h6><hr class="my-1">
                     <small><strong>Class:</strong> ${p.class_name}</small><br>
                     <small><strong>Proposed Time:</strong> ${p.time}</small><br>
                     <small><strong>Reason:</strong> ${p.reason}</small>${btns}`;
        } else if (text.includes('[RESCHEDULE_RESOLVED_APPROVED]')) {
            const p = JSON.parse(text.replace('[RESCHEDULE_RESOLVED_APPROVED]', '')) || {};
            inner = `<h6 class="fw-bold text-success mb-1">✅ Reschedule Approved</h6><hr class="my-1">
                     <small><strong>Class:</strong> ${p.class_name}</small><br>
                     <small><strong>New Makeup Slot:</strong> ${p.time}</small>`;
        } else if (text.includes('[RESCHEDULE_RESOLVED_REJECTED]')) {
            inner = `<h6 class="fw-bold text-secondary mb-1">❌ Reschedule Declined</h6>`;
        }

        chatWindow.insertAdjacentHTML('beforeend',
            `<div class="msg-row ${rowClass}"><div class="bubble ${rowClass}">${inner}</div></div>`);
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    // handleReschedule — used by tutor buttons (safe even if no chat open)
    function handleReschedule(id, action) {
        const box = document.getElementById(`actions-${id}`);
        if (!confirm(`Are you sure you want to ${action} this reschedule request?`)) return;
        box.innerHTML = `<small class="text-muted">Processing...</small>`;
        axios.post(`/chat/reschedule/${action}`, { message_id: id })
            .then(() => {
                box.innerHTML = action === 'approve'
                    ? `<span class="badge bg-success p-2">✅ Approved - Zoom Room Spawned</span>`
                    : `<span class="badge bg-secondary p-2">❌ Declined</span>`;
            })
            .catch(err => {
                console.error(err);
                box.innerHTML = `<span class="badge bg-danger p-2">⚠️ Error occurred</span>`;
            });
    }

    // ============ Real-Time Listener (runs on BOTH states) ============
    (function waitForEchoChat() {
        if (typeof window.Echo === 'undefined') {
            return setTimeout(waitForEchoChat, 200);
        }

        window.Echo.private(`chat.${myUserId}`)
            .listen('.MessageSent', (e) => {
                const senderId = parseInt(e.message.sender_id);

                if (window.currentOpenChatId && senderId === parseInt(window.currentOpenChatId)) {
                    // chat with this sender is OPEN → append + mark read
                    appendMessage(e.message.message, 'receiver', e.message.id);
                    axios.post(`/chat/mark-read/${window.currentOpenChatId}`).catch(() => {});
                } else {
                    // not open → sidebar badge only
                    bumpContactBadge(senderId);
                }
            });
    })();

    // ============ CONVERSATION-ONLY logic ============
    @if($receiver)
    const currentUserId = {{ Auth::id() }};
    const receiverId = {{ $receiver->id }};
    window.currentUserRole = '{{ Auth::user()->role }}';

    (function () {
        const cw = document.getElementById('chat-messages');
        if (cw) cw.scrollTop = cw.scrollHeight;
    })();

    function sendMessage() {
        const input = document.getElementById('message-input');
        const text = input.value;
        if (text.trim() === '') return;
        input.disabled = true;
        postMessagePayload(text, () => {
            input.value = '';
            input.disabled = false;
            input.focus();
            appendMessage(text, 'sender');
        });
    }

    function postMessagePayload(textString, onSuccess) {
        axios.post('/chat/send', { receiver_id: receiverId, message: textString })
            .then(() => onSuccess())
            .catch(err => {
                console.error(err);
                document.getElementById('message-input').disabled = false;
                alert("Could not send message.");
            });
    }

    function submitRescheduleRequest() {
        const sel = document.getElementById('modal-class-id');
        if (!sel) { alert("No active classes found with this tutor to reschedule."); return; }
        const time = document.getElementById('modal-time').value;
        const reason = document.getElementById('modal-reason').value;
        if (!time || !reason) { alert("Please complete all fields."); return; }

        const payload = "[RESCHEDULE_REQUEST]" + JSON.stringify({
            class_id: sel.value,
            class_name: sel.options[sel.selectedIndex].text,
            time: time.replace('T', ' '),
            reason: reason
        });

        postMessagePayload(payload, () => {
            const m = document.getElementById('rescheduleModal');
            (bootstrap.Modal.getInstance(m) || new bootstrap.Modal(m)).hide();
            appendMessage(payload, 'sender');
        });
    }

    document.getElementById('message-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') sendMessage();
    });
    @endif
</script>
@endsection
