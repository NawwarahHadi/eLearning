import './bootstrap'; // This imports Echo and Pusher from bootstrap.js

// import Alpine from 'alpinejs';
// window.Alpine = Alpine;

// // Chat Logic for Alpine
// Alpine.data('chatBox', (receiverId, initialMessages) => ({
//     messages: initialMessages,
//     newMessage: '',
//     receiverId: receiverId,

//     init() {
//         // Listen for real-time messages using Laravel Echo
//         window.Echo.private(`chat.${userId}`) // userId needs to be defined in your blade
//             .listen('MessageSent', (e) => {
//                 this.messages.push(e.message);
//                 this.scrollToBottom();
//             });
//     },

//    sendMessage() {
//         // Don't send empty messages
//         if (this.newMessage.trim() === '') return;

//         // This URL must match your route in web.php
//         axios.post('/chat/send', {
//             receiver_id: this.receiverId,
//             message: this.newMessage
//         })
//         .then(response => {
//             // This adds the message to the screen immediately
//             this.messages.push(response.data.message);
//             this.newMessage = ''; // Clear the input box
//             this.scrollToBottom();
//         })
//         .catch(error => {
//             console.error("Save Error:", error.response.data);
//             alert("Failed to save message. Check the console!");
//         });
//     },

//     scrollToBottom() {
//         setTimeout(() => {
//             const container = document.getElementById('chat-messages');
//             container.scrollTop = container.scrollHeight;
//         }, 10);
//     }
// }));

// Alpine.start();
// import './bootstrap';

// // Wait for the page to load
// document.addEventListener('DOMContentLoaded', function () {
//     const sendButton = document.getElementById('send-button');
//     const messageInput = document.getElementById('message-input');
//     const receiverId = document.getElementById('receiver-id').value;

//     if (sendButton) {
//         sendButton.addEventListener('click', function () {
//             const message = messageInput.value;

//             if (message.trim() === '') return;

//             // Use Axios to send data to your ChatController
//             axios.post('/chat/send', {
//                 receiver_id: receiverId,
//                 message: message
//             })
//             .then(response => {
//                 // Clear input
//                 messageInput.value = '';
//                 // Refresh the page or append the message to the list manually
//                 window.location.reload();
//             })
//             .catch(error => {
//                 console.error("Error:", error.response);
//             });
//         });
//     }
// });

