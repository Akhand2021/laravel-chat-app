<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\OllamaService;
use App\Helpers\PromptBuilder;
use App\Helpers\ActionParser;
use App\Services\ToolExecutor;
use Illuminate\Support\Facades\Log;
use App\Agents\WeatherAgent;

class AIChatController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = \ArdaGnsrn\Ollama\Ollama::client();
    }
    public function index()
    {
        $response =  $this->client->models()->runningList();

        return Inertia::render("AIChat", ['model' => $response->toArray()]);
    }

    public function viewWeather()
    {
        return Inertia::render('WeatherChat');
    }

    public function ask(Request $request)
    {
        $ollama = $this->client;

        // Retrieve the previous chat history from the session or initialize it
        $chatHistory = session('chat_history', []);

        // Add the new user message to the chat history
        $chatHistory[] = [
            'role' => 'user',
            'content' => $request->input('prompt'),
        ];

        // Keep only the last 10 messages in the chat history
        $chatHistory = array_slice($chatHistory, -10);

        // Save the updated chat history back to the session
        session(['chat_history' => $chatHistory]);

        return new StreamedResponse(function () use ($ollama, $chatHistory) {
            $responses = $ollama->completions()->createStreamed([
                'model' => 'gemma3:1b',
                'prompt' => $this->buildChatPrompt($chatHistory),
            ]);

            foreach ($responses as $response) {
                echo $response->response;
                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    private function buildChatPrompt(array $chatHistory): string
    {
        $prompt = "";
        foreach ($chatHistory as $message) {
            $role = ucfirst($message['role']); // e.g., "User" or "Assistant"
            $content = $message['content'];
            $prompt .= "{$role}: {$content}\n";
        }
        return $prompt;
    }

    // public function ask(Request $request)
    // {
    //     // Log the incoming request for debugging purposes
    //     Log::info('Received user message', ['message' => $request->input('prompt')]);

    //     // Retrieve user input
    //     $userMessage = $request->input('prompt');

    //     // Get the current date and time
    //     $currentDateTime = now()->toDateTimeString();  // Example: 2025-04-12 21:04:22

    //     // Build the initial prompt to send to the AI model, include current date/time
    //     $prompt = PromptBuilder::build($userMessage, $currentDateTime);
    //     Log::info("Prompt: " . $prompt);

    //     // Get the initial AI response (may include action or location extraction)
    //     $initialResponse = OllamaService::chat($prompt);

    //     // Log the initial response for debugging purposes
    //     Log::info("Initial Response: ", ['response' => $initialResponse]);

    //     // Now, we ask the model to extract the location from the initial response
    //     $action = ActionParser::extract($initialResponse); // Extract if there's an action in the response

    //     if ($action) {
    //         // If an action (e.g., weather request) was found, process it and get the observation (e.g., weather data)
    //         $observation = ToolExecutor::handle($action);

    //         // Update the prompt with the action and observation
    //         $followUpPrompt = $prompt . "\nAction: {$action}\nObservation: {$observation}\n";

    //         // Get the final response after executing the action
    //         $finalResponse = OllamaService::chat($followUpPrompt);

    //         // Clean the final response by removing unnecessary Action and Observation parts
    //         $finalResponse = preg_replace('/Action:.*?\n/', '', $finalResponse); // Remove action part
    //         $finalResponse = preg_replace('/Observation:.*?\n/', '', $finalResponse); // Remove observation part if it repeats

    //         // Clean up any unnecessary whitespace or newlines
    //         $finalResponse = trim($finalResponse);
    //     } else {
    //         // If no action is found, ask the model to extract the location
    //         $location = $this->extractLocationFromMessage($userMessage);

    //         if ($location) {
    //             // If a location is extracted, call the WeatherAgent to fetch the weather
    //             $observation = WeatherAgent::getWeather($location);

    //             // Format the final response with weather information
    //             $finalResponse = "The weather in {$location} is currently {$observation['condition']} with a temperature of {$observation['temperature']}.";
    //         } else {
    //             // If no location could be extracted, ask the user for clarification
    //             $finalResponse = "I couldn't determine the location from your message. Could you please provide the city or location you're asking about?";
    //         }
    //     }

    //     // Return the cleaned-up response as plain text
    //     return response($finalResponse);
    // }

    public function askWeather(Request $request)
    {
        // Log the incoming user message
        $userMessage = $request->input('prompt');
        Log::info('Received user message', ['message' => $userMessage]);

        // STEP 1: PLAN
        // Ask the model to determine if the query is related to weather.
        $planPrompt = <<<EOT
You are an AI assistant. 
Based on the user's query: "$userMessage"
Please determine if this query is about weather query containt any location. 
Answer "yes" or "no" only.
EOT;
        $planResponse = OllamaService::chat($planPrompt);
        Log::info('Plan response', ['response' => $planResponse]);

        // If the model does not answer "yes", then return the original model output.
        if (stripos($planResponse, 'yes') === false) {
            $finalResponse = "Your query does not seem to be about weather. " . $planResponse;
            return response($finalResponse);
        }

        // STEP 2: ACTION
        // Now ask the model to extract the location from the user's query.
        $extractPrompt = <<<EOT
You are an AI assistant. 
Extract the location from the following query and provide only the location name.
Query: "$userMessage"
EOT;
        $extractionResult = OllamaService::chat($extractPrompt);
        $location = trim($extractionResult);
        Log::info('Extracted location', ['location' => $location]);

        if (empty($location)) {
            $finalResponse = "I couldn't determine the location from your query. Could you please specify the city or region?";
            return response($finalResponse);
        }

        // STEP 3: OBSERVATION
        // Call the weather API using the extracted location.
        $weather = WeatherAgent::getWeather($location);
        Log::info("Weather data for {$location}", [
            'temperature' => $weather['temperature'],
            'condition'   => $weather['condition']
        ]);

        // STEP 4: RESPONSE
        // Ask the model to generate the final response based on the weather information.
        $finalPrompt = <<<EOT
You are an AI assistant providing weather updates.
The extracted location is "$location".
The weather data is as follows:
- Temperature: {$weather['temperature']}
- Condition: {$weather['condition']}

Based on this information, produce a friendly and engaging response to the user. Do not include any meta information such as "Action" or "Observation", just deliver the final answer.
EOT;
        $finalResponse = OllamaService::chat($finalPrompt);
        Log::info('Final response from model', ['finalResponse' => $finalResponse]);

        // Return the final response as plain text.
        return response($finalResponse);
    }



    private function extractLocationFromMessage(string $message): ?string
    {
        // Build a specific prompt to ask the model to extract a location from the user's message
        $prompt = "Extract the location from this message: {$message}. Please provide only the location name.";
        Log::info("Location extraction prompt: " . $prompt);

        // Get the model's response with the extracted location
        $response = OllamaService::chat($prompt);

        // Log the model's response for debugging purposes
        Log::info("Location Extraction Response: ", ['response' => $response]);

        // You can either return the location directly or process it to clean up
        return trim($response);  // If the response is already a clean location
    }
}
