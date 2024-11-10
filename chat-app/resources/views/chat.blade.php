<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Chat</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite(['resources/js/app.js'])
    <style>
        .chat-box {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .chat-message {
            max-width: 75%;
            padding: 10px;
            border-radius: 15px;
            margin-bottom: 10px;
            display: inline-block;
            position: relative;
        }
        .chat-message.user {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
            margin-left: auto;
        }
        .chat-message.other {
            background-color: #e1e1e1;
            color: #333;
            align-self: flex-start;
            margin-right: auto;
        }
        .chat-message .timestamp {
            font-size: 0.75rem;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0">Chat</h5>
                    </div>
                    <div id="chat" class="card-body chat-box d-flex flex-column" style="height: 400px;">
                        <!-- Messages will be dynamically loaded here -->
                    </div>
                    <div class="card-footer d-flex justify-content-start align-items-center p-3">
                        <form id="chat-form" class="d-flex w-100">
                            @csrf
                            <input type="text" id="message" class="form-control form-control-lg mx-2"
                                   placeholder="Type a message" required>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Pusher
            const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                encrypted: true,
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: { headers: { 'X-CSRF-Token': '{{ csrf_token() }}' }}
            });

            // Subscribe to the private chat-app channel
            const channel = pusher.subscribe('private-chat-app');

            // Listen for broadcasted messages
            channel.bind('App\\Events\\MessageSent', function(data) {
                displayMessage(data.user, data.message);
            });

            // Handle message form submission
            const form = document.getElementById('chat-form');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const messageInput = document.getElementById('message');
                axios.post('/chat/send', { message: messageInput.value })
                    .then(() => messageInput.value = '')
                    .catch(error => console.error(error));
            });

            // Function to display a message
            function displayMessage(user, message) {
                const chatBox = document.getElementById('chat');
                const messageElement = document.createElement('div');

                // Determine if the message was sent by the logged-in user
                const isCurrentUser = user.id === {{ auth()->id() }};
                
                // Add styling based on who sent the message
                messageElement.classList.add('chat-message', isCurrentUser ? 'user' : 'other');
                
                // Create the message bubble with timestamp
                messageElement.innerHTML = `
                    <div>${message}</div>
                    <div class="timestamp">${new Date().toLocaleTimeString()}</div>
                `;

                // Append message and auto-scroll
                chatBox.appendChild(messageElement);
                chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the latest message
            }
        });
    </script>
</body>
</html>
