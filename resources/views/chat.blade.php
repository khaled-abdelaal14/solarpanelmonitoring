<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatbot</title>
</head>
<body>
    <input type="text" id="message" placeholder="اكتب رسالتك">
    <button onclick="sendMessage()">إرسال</button>
    <div id="response"></div>

    <script>
        function sendMessage() {
            const message = document.getElementById('message').value;

            fetch('/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('response').innerText = data.queryResult.fulfillmentText;
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>