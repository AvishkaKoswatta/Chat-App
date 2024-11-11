















<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for chat boxes */
        .chat-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            height: 80vh;
            overflow-y: scroll;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .message-box {
            padding: 10px;
            margin: 10px 0;
            border-radius: 10px;
            max-width: 75%;
        }
        .message-left {
            background-color: #f1f1f1;
            text-align: left;
        }
        .message-right {
            background-color: #007bff;
            color: #fff;
            text-align: right;
            margin-left: auto;
        }
        .input-group {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 10px;
            background: #fff;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="chat-container" id="chat-box">
        <!-- Chat messages will be appended here dynamically -->
    </div>

    <!-- Message input area -->
    <div class="input-group">
        <input type="text" id="message-input" class="form-control" placeholder="Type a message">
        <button class="btn btn-primary" onclick="sendMessage()">Send</button>
    </div>
</div>

<!-- Pusher and Axios JS -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Initialize Pusher
    Pusher.logToConsole = true;
    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        encrypted: true
    });

    // Subscribe to the 'chat-channel' channel
    const channel = pusher.subscribe('chat-app');//private-chat-app

    // Listen for 'message-sent' events
    channel.bind('message.sent', function(data) {
        const messageBox = document.createElement('div');
        messageBox.classList.add('message-box', 'message-left');
        messageBox.innerHTML = `<p><strong>${data.user.name}:</strong> ${data.message}</p>`;
        document.getElementById('chat-box').appendChild(messageBox);
        document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
    });

    // Function to send a message
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value.trim();

        if (messageText) {
            // Append the message to the chat window immediately
            const messageBox = document.createElement('div');
            messageBox.classList.add('message-box', 'message-right');
            messageBox.innerHTML = `<p>${messageText}</p>`;
            document.getElementById('chat-box').appendChild(messageBox);
            document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;

            // Send the message to the backend
            axios.post('/chat/send', {
                message: messageText
            }).then(response => {
                console.log(response.data.status);
            }).catch(error => console.error(error));

            // Clear the input field
            messageInput.value = '';
            messageInput.focus();
        }
    }
</script>

</body>
</html>

