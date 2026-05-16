@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        {{-- 1. Header --}}
        <div class="card-header bg-success">
            <h3 class="card-title text-white">Chatting with {{ $receiver->name }}</h3>
        </div>

        {{-- 2. Message Body --}}
        <div class="card-body" id="chat-messages" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
            @foreach($messages as $msg)
                <div class="d-flex {{ $msg->sender_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }} mb-4">
                    <div class="{{ $msg->sender_id == Auth::id() ? 'bg-primary text-white' : 'bg-white text-dark border' }} p-3 rounded shadow-sm" style="max-width: 70%;">
                        <span>{{ $msg->message }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- 3. Footer Input --}}
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

<script>
    // 1. Pass PHP variables to JavaScript
    const currentUserId = {{ Auth::id() }};
    const receiverId = {{ $receiver->id }};

    // Scroll to bottom on load
    const chatWindow = document.getElementById('chat-messages');
    chatWindow.scrollTop = chatWindow.scrollHeight;

    /**
     * Send Message Function
     */
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value;

        if (messageText.trim() === '') return;

        // Disable input while sending to prevent double clicks
        messageInput.disabled = true;

        axios.post('/chat/send', {
            receiver_id: receiverId,
            message: messageText
        })
        .then(response => {
            messageInput.value = '';
            messageInput.disabled = false;
            messageInput.focus();

            // Append your message to your own screen (Right Side)
            appendMessage(messageText, 'sender');
        })
        .catch(error => {
            console.error(error);
            messageInput.disabled = false;
            alert("Could not send message. Check console for details.");
        });
    }

    /**
     * Append Message to UI
     */
    function appendMessage(text, type) {
        const isSender = (type === 'sender');
        const alignment = isSender ? 'justify-content-end' : 'justify-content-start';
        const bgClass = isSender ? 'bg-primary text-white' : 'bg-white text-dark border';

        const newMessageHtml = `
            <div class="d-flex ${alignment} mb-4">
                <div class="${bgClass} p-3 rounded shadow-sm" style="max-width: 70%;">
                    <span>${text}</span>
                </div>
            </div>
        `;

        chatWindow.insertAdjacentHTML('beforeend', newMessageHtml);
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    /**
     * Listen for Real-time events
     */
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.Echo !== 'undefined') {
            console.log("Echo is connected. Listening on chat." + currentUserId);

           window.Echo.private(`chat.${currentUserId}`)
    // Add the dot (.) before the class name
    .listen('.App.Events.MessageSent', (e) => {
        console.log("REAL TIME DATA ARRIVED:", e);
        appendMessage(e.message.message, 'receiver');
    })
    // Also keep the short version just in case
    .listen('MessageSent', (e) => {
        console.log("REAL TIME DATA ARRIVED:", e);
        appendMessage(e.message.message, 'receiver');
    });
        } else {
            console.error("Laravel Echo is not detected. Make sure your app.js is compiled.");
        }
    });

    // Allow "Enter" key to send message
    document.getElementById('message-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
</script>
@endsection
