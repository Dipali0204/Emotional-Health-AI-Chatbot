<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emotional Health Chatbot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0f2d;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .chat-container {
            width: 400px;
            background-color: #1c2333;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 90vh;
            padding: 10px;
        }
        .chat-title {
            text-align: center;
            color: white;
            font-size: 18px;
            padding: 10px;
        }
        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }
        .message {
            max-width: 70%;
            padding: 8px 12px;
            border-radius: 15px;
            margin-bottom: 10px;
            display: inline-block;
            word-wrap: break-word;
        }
        .user-message {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
            text-align: right;
        }
        .bot-message {
            background-color: #e0e0e0;
            color: black;
            align-self: flex-start;
            text-align: left;
        }
        .input-container {
            display: flex;
            padding: 10px;
            border-top: 1px solid #333;
        }
        input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 20px;
            outline: none;
        }
        button {
            background: #007bff;
            border: none;
            padding: 10px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 50%;
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-title">Emotional Health Chatbot</div>
        <div class="chat-box" id="chat-box"></div>
        <div class="input-container">
            <input type="text" id="user-input" placeholder="Type a message...">
            <button id="send-button">▶</button>
        </div>
    </div>

    <script>
        document.getElementById("send-button").addEventListener("click", sendMessage);
        document.getElementById("user-input").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        });

        function sendMessage() {
            let userInput = document.getElementById("user-input").value.trim();
            if (userInput === "") return;

            let chatBox = document.getElementById("chat-box");
            let userMessage = document.createElement("div");
            userMessage.className = "message user-message";
            userMessage.textContent = userInput;
            chatBox.appendChild(userMessage);
            document.getElementById("user-input").value = "";
            chatBox.scrollTop = chatBox.scrollHeight;

            // Flask API Call
            fetch("http://127.0.0.1:5000/analyze", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ text: userInput })
            })
            .then(response => response.json())
            .then(data => {
                let botMessage = document.createElement("div");
                botMessage.className = "message bot-message";
                botMessage.textContent = `Emotion: ${data.emotion} (Confidence: ${data.confidence * 100}%)`;
                chatBox.appendChild(botMessage);
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => {
                console.error("Error connecting to AI server:", error);
                let botMessage = document.createElement("div");
                botMessage.className = "message bot-message";
                botMessage.textContent = "Error connecting to AI server!";
                chatBox.appendChild(botMessage);
                chatBox.scrollTop = chatBox.scrollHeight;
            });
        }
    </script>
</body>
</html>
