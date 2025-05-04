import React, { useState } from 'react';
import { Head } from '@inertiajs/react';

function WeatherChat() {
  const [messages, setMessages] = useState([
    { from: 'bot', text: '👋 Hi! Ask me about the weather in any city.' }
  ]);
  const [input, setInput] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSend = async () => {
    if (!input.trim()) return;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const userMessage = { from: 'user', text: input };
    setMessages((prev) => [...prev, userMessage]);
    setLoading(true);

    try {
      const res = await fetch('/ask-weather', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify({ prompt: input }),
      });

      const reply = await res.text();
      setMessages((prev) => [...prev, { from: 'bot', text: reply }]);
    } catch (err) {
      setMessages((prev) => [
        ...prev,
        { from: 'bot', text: '❌ Oops! Something went wrong. Please try again.' },
      ]);
    } finally {
      setInput('');
      setLoading(false);
    }
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Enter') handleSend();
  };

  return (
    <>
      <Head>
        <title>Weather</title>
      </Head>
      <div className="max-w-lg mx-auto mt-10 p-6 bg-white rounded-xl shadow-lg">
        <h2 className="text-2xl font-semibold mb-4 text-center">🌦️ Weather Assistant</h2>

        <div className="h-96 overflow-y-auto border border-gray-200 rounded-md p-4 mb-4 bg-gray-50 space-y-2">
          {messages.map((msg, i) => (
            <div
              key={i}
              className={`text-sm px-3 py-2 rounded-lg max-w-xs ${msg.from === 'user'
                ? 'bg-blue-500 text-white self-end ml-auto'
                : 'bg-gray-200 text-gray-800 self-start mr-auto'
                }`}
            >
              {msg.text}
            </div>
          ))}
          {loading && (
            <div className="text-sm px-3 py-2 bg-gray-200 text-gray-800 rounded-lg w-fit">
              ⏳ Thinking...
            </div>
          )}
        </div>

        <div className="flex gap-2">
          <input
            type="text"
            placeholder="Ask about the weather..."
            className="flex-grow border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            value={input}
            onChange={(e) => setInput(e.target.value)}
            onKeyDown={handleKeyDown}
            disabled={loading}
          />
          <button
            onClick={handleSend}
            className="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition"
            disabled={loading}
          >
            Send
          </button>
        </div>
      </div>
    </>

  );
}

export default WeatherChat;
