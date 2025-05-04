import { useState, useRef } from 'react';
import ReactMarkdown from 'react-markdown';
import { Prism as SyntaxHighlighter } from 'react-syntax-highlighter';
import { oneDark } from 'react-syntax-highlighter/dist/esm/styles/prism';
import { Head } from '@inertiajs/react';

const AIChat = ({ model }) => {
    const [prompt, setPrompt] = useState('');
    const [messages, setMessages] = useState([]);
    const [isStreaming, setIsStreaming] = useState(false);
    const abortControllerRef = useRef(null);

    const handleSend = async () => {
        if (!prompt.trim()) return;

        setMessages(prev => [...prev, { sender: 'user', text: prompt }]);
        setPrompt('');
        setIsStreaming(true);

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const controller = new AbortController();
        abortControllerRef.current = controller;

        try {
            const res = await fetch('/aichat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ prompt }),
                signal: controller.signal
            });
            const reader = res.body.getReader();
            let aiText = '';
            const decoder = new TextDecoder();

            while (true) {
                const { done, value } = await reader.read();
                if (done) break;

                aiText += decoder.decode(value);
                setMessages(prev => {
                    const updated = [...prev];
                    const lastMsg = updated[updated.length - 1];

                    if (lastMsg && lastMsg.sender === 'ai') {
                        // Update the last AI message
                        updated[updated.length - 1] = { ...lastMsg, text: aiText };
                    } else {
                        // Add new AI message
                        updated.push({ sender: 'ai', text: aiText });
                    }

                    return updated;
                });

            }
        } catch (err) {
            if (err.name === 'AbortError') {
                console.log('Request aborted');
            } else {
                console.error('AI Error:', err);
                setMessages(prev => [...prev, { sender: 'ai', text: 'Error getting response.' }]);
            }
        } finally {
            setIsStreaming(false);
        }
    };

    const handleStop = () => {
        if (abortControllerRef.current) {
            abortControllerRef.current.abort();
            setIsStreaming(false);
        }
    };
    const TypingLoader = () => (
        <div className="flex items-center space-x-1 ml-2">
            <div className="w-2 h-2 bg-green-500 rounded-full animate-bounce [animation-delay:0s]"></div>
            <div className="w-2 h-2 bg-green-500 rounded-full animate-bounce [animation-delay:0.2s]"></div>
            <div className="w-2 h-2 bg-green-500 rounded-full animate-bounce [animation-delay:0.4s]"></div>
        </div>
    );

    return (
        <>
            <Head>
                <title>AI Chat</title>
            </Head>
            <div className="bg-gray-100 p-4 m-auto max-w-screen-xl w-full flex flex-col h-screen">
                <h1 className="text-blue-600 font-bold mb-4">AI Chat</h1>
                <div id="chat" className="flex-grow overflow-y-auto">
                    <div id="messages" className="flex flex-col space-y-2">
                        {messages.map((msg, index) => (
                            <div
                                key={index}
                                className={`p-3 my-2 rounded-lg ${msg.sender === 'user'
                                    ? 'bg-blue-50 text-blue-700'
                                    : 'bg-green-50 text-gray-900'
                                    } flex items-start`}
                            >
                                {msg.sender === 'user' ? (
                                    <div>
                                        <strong>You:</strong>
                                        <p className="ml-2 whitespace-pre-wrap">{msg.text}</p>
                                    </div>
                                ) : (
                                    <div className="w-full">
                                        <div className="flex items-start">
                                            <span className="mr-3 text-2xl">🤖</span>
                                            <div className="overflow-x-auto w-full">
                                                <ReactMarkdown
                                                    components={{
                                                        code({ node, inline, className, children, ...props }) {
                                                            const match = /language-(\w+)/.exec(className || '');
                                                            return !inline && match ? (
                                                                <div className="overflow-x-auto max-w-full bg-gray-900 rounded-lg p-2 my-2">
                                                                    <SyntaxHighlighter
                                                                        style={oneDark}
                                                                        language={match[1]}
                                                                        PreTag="div"
                                                                        customStyle={{ margin: 0 }}
                                                                        {...props}
                                                                    >
                                                                        {String(children).replace(/\n$/, '')}
                                                                    </SyntaxHighlighter>
                                                                </div>
                                                            ) : (
                                                                <code className="bg-gray-200 px-1 py-0.5 rounded" {...props}>
                                                                    {children}
                                                                </code>
                                                            );
                                                        },
                                                        table({ children }) {
                                                            return (
                                                                <div className="overflow-x-auto my-2">
                                                                    <table className="min-w-full border border-gray-300">{children}</table>
                                                                </div>
                                                            );
                                                        },
                                                        th({ children }) {
                                                            return <th className="border px-3 py-2 bg-gray-200 text-left">{children}</th>;
                                                        },
                                                        td({ children }) {
                                                            return <td className="border px-3 py-2">{children}</td>;
                                                        },
                                                    }}
                                                >
                                                    {msg.text}
                                                </ReactMarkdown>

                                            </div>
                                        </div>

                                        {isStreaming && index === messages.length - 1 && <TypingLoader />}
                                    </div>
                                )}
                            </div>
                        ))}


                    </div>
                </div>

                <div className="flex flex-col space-y-2 mt-4">
                    <textarea
                        value={prompt}
                        onChange={(e) => setPrompt(e.target.value)}
                        onKeyDown={(e) => {
                            if (e.key === 'Enter' && !e.shiftKey) {
                                e.preventDefault();
                                handleSend();
                            }
                        }}
                        placeholder="Type your message here..."
                        className="border border-gray-300 p-2 rounded-lg w-full bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="3"
                    />
                    <div className="flex gap-2">
                        <button
                            onClick={handleSend}
                            disabled={isStreaming}
                            className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 ease-in-out disabled:opacity-50"
                        >
                            Send
                        </button>
                        {isStreaming && (
                            <button
                                onClick={handleStop}
                                className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 ease-in-out"
                            >
                                Stop
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
};

export default AIChat;
