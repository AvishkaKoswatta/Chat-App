<!-- chat.blade.php -->
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
        <!-- Message from other user -->
        <div class="message-box message-left">
            <p><strong>Other User:</strong> Hello! How are you?</p>
        </div>

        <!-- Message from current user -->
        <div class="message-box message-right">
            <p>Hi! I'm good, thank you! How about you?</p>
        </div>

        <!-- Additional messages can be appended here -->
    </div>

    <!-- Message input area -->
    <div class="input-group">
        <input type="text" id="message-input" class="form-control" placeholder="Type a message">
        <button class="btn btn-primary" onclick="sendMessage()">Send</button>
    </div>
</div>

<!-- Optional JavaScript -->
<script>
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value.trim();
        if (messageText) {
            const messageBox = document.createElement('div');
            messageBox.classList.add('message-box', 'message-right');
            messageBox.innerHTML = `<p>${messageText}</p>`;
            document.getElementById('chat-box').appendChild(messageBox);
            messageInput.value = '';
            messageInput.focus();
            document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
        }
    }
</script>

</body>
</html>
