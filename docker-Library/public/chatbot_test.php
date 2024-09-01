<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .chatbot-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4a934a;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        .chatbot-fab:hover {
            background-color: #3a733a;
        }
        .chatbot-dialog {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            max-height: 400px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        .chatbot-header {
            background-color: #4a934a;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        .chatbot-body {
            padding: 10px;
            flex: 1;
            overflow-y: auto;
        }
        .chatbot-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .chatbot-footer input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h1 class="text-center my-5">Chatbot Test Page</h1>

    <!-- Floating Action Button -->
    <div class="chatbot-fab" id="chatbotFab">
        <i class="fas fa-comments"></i>
    </div>

    <!-- Chatbot Dialog Box -->
    <div class="chatbot-dialog" id="chatbotDialog">
        <div class="chatbot-header">
            AI Chat Bot
        </div>
        <div class="chatbot-body" id="chatbotBody">
            <!-- Chat content goes here -->
        </div>
        <div class="chatbot-footer">
            <input type="text" id="chatInput" placeholder="Type a message...">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('chatbotFab').addEventListener('click', function() {
            const dialog = document.getElementById('chatbotDialog');
            if (dialog.style.display === 'none' || dialog.style.display === '') {
                dialog.style.display = 'flex';
            } else {
                dialog.style.display = 'none';
            }
        });

        document.getElementById('chatInput').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                const input = event.target.value.trim();
                if (input) {
                    const message = document.createElement('div');
                    message.textContent = input;
                    message.style.marginBottom = '10px';
                    document.getElementById('chatbotBody').appendChild(message);
                    
                    $.ajax({
                        url: 'chatbot.php',
                        method: 'POST',
                        data: { message: input },
                        success: function(response) {
                            const botResponse = document.createElement('div');
                            botResponse.textContent = response;
                            botResponse.style.marginBottom = '10px';
                            botResponse.style.color = 'green'; // Bot response color
                            document.getElementById('chatbotBody').appendChild(botResponse);
                        }
                    });

                    event.target.value = '';
                }
            }
        });
    </script>
</body>
</html>
