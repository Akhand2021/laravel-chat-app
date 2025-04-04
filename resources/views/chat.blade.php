<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/bootstrap.js'])
</head>

<body class="bg-gray-100">
    <div class="container mx-auto max-w-6xl p-4">
        <div class="bg-white rounded-lg shadow-lg flex h-[600px]">
            <!-- Users List -->
            <div class="w-1/4 border-r">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-semibold">Users</h2>
                </div>
                <div class="overflow-y-auto h-[calc(100%-4rem)]">
                    @foreach($users as $user)
                        <div class="p-4 hover:bg-gray-50 cursor-pointer user-item" data-user-id="{{ $user->id }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                                <span
                                    class="unread-count hidden bg-blue-500 text-white rounded-full px-2 py-1 text-xs">0</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-3/4 flex flex-col">
                <div class="p-4 border-b">
                    <h1 class="text-2xl font-bold text-gray-800">Chat Room</h1>
                </div>

                <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                    <div class="text-center text-gray-500">
                        Select a user to start chatting
                    </div>
                </div>

                <div class="p-4 border-t">
                    <form id="message-form" class="flex space-x-2">
                        <input type="hidden" id="recipient_id" name="recipient_id">
                        <input type="text" id="message"
                            class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Type your message...">
                        <button type="button" id="send-button"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messagesContainer = document.getElementById('messages');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message');
            const recipientInput = document.getElementById('recipient_id');
            let selectedUserId = null;

            // Handle user selection
            document.querySelectorAll('.user-item').forEach(item => {
                item.addEventListener('click', async () => {
                    selectedUserId = item.dataset.userId;
                    recipientInput.value = selectedUserId;

                    // Update active state
                    document.querySelectorAll('.user-item').forEach(i => i.classList.remove('bg-blue-50'));
                    item.classList.add('bg-blue-50');

                    // Load messages
                    await loadMessages(selectedUserId);
                });
            });

            // Load messages for a specific user
            async function loadMessages(userId) {
                try {
                    const response = await fetch(`/messages?user_id=${userId}`);
                    const messages = await response.json();

                    messagesContainer.innerHTML = '';
                    messages.forEach(message => {
                        appendMessage(message);
                    });

                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            // Append a message to the chat
            function appendMessage(message) {
                const messageElement = document.createElement('div');
                messageElement.className = `flex items-start ${message.user_id == {{ auth()->id() }} ? 'justify-end' : 'justify-start'}`;
                messageElement.innerHTML = `
                    <div class="max-w-xs ${message.user_id == {{ auth()->id() }} ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'} rounded-lg px-4 py-2">
                        <p class="text-sm font-semibold">${message.user.name}</p>
                        <p>${message.message}</p>
                        <p class="text-xs ${message.user_id == {{ auth()->id() }} ? 'text-blue-100' : 'text-gray-500'}">${new Date(message.created_at).toLocaleTimeString()}</p>
                    </div>
                `;
                messagesContainer.appendChild(messageElement);
            }

            // Listen for new messages
            window.Echo.private(`chat.{{{ auth()->id() }}}`)
                .listen('MessageSent', (e) => {
                    console.log('Received message:', e); // Add debugging
                    const message = e.message;

                    // Always append the message if it's from the selected user or to the selected user
                    if (selectedUserId && (message.user_id == selectedUserId || message.recipient_id == selectedUserId)) {
                        appendMessage(message);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }

                    // Update unread count for the sender
                    if (message.user_id != {{ auth()->id() }}) {
                        updateUnreadCount(message.user_id);
                    }
                });

            // Send message
            const sendButton = document.getElementById('send-button');
            sendButton.addEventListener('click', async function () {
                if (!selectedUserId) {
                    alert('Please select a user to chat with');
                    return;
                }

                const message = messageInput.value;
                if (!message) return;

                try {
                    const response = await fetch('/messages', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            message,
                            recipient_id: selectedUserId
                        })
                    });

                    if (response.ok) {
                        messageInput.value = '';
                        // Optionally append the sent message to the chat
                        const sentMessage = await response.json();
                        appendMessage(sentMessage);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                }
            });

            // Also handle Enter key in the message input
            messageInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendButton.click();
                }
            });

            // Update unread count
            async function updateUnreadCount(userId) {
                try {
                    const response = await fetch(`/messages/unread-count`);
                    const data = await response.json();
                    const unreadElement = document.querySelector(`[data-user-id="${userId}"] .unread-count`);
                    if (unreadElement) {
                        unreadElement.textContent = data.count;
                        unreadElement.classList.toggle('hidden', data.count === 0);
                    }
                } catch (error) {
                    console.error('Error updating unread count:', error);
                }
            }

            // Initial unread counts
            document.querySelectorAll('.user-item').forEach(item => {
                updateUnreadCount(item.dataset.userId);
            });
        });
    </script>

</body>

</html>