<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gemma Chat</title>
</head>

<body>
    <h1>Chat with Gemma (Ollama)</h1>

    <form method="POST" action="/ai-chat">
        @csrf
        <textarea name="prompt" rows="5" cols="60" placeholder="Ask something...">{{ old('prompt') }}</textarea><br>
        <button type="submit">Send</button>
    </form>

    @if (isset($response))
    <h3>Gemma's Response:</h3>
    <p>{{ $response }}</p>
    @endif
</body>

</html>