<?php
namespace App\Agents;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class WeatherAgent
{
    // This method now calls the WeatherAPI endpoint
    public static function getWeather(string $location): array
    {
        Log::info("Test getWeather called");
        // Replace YOUR_API_KEY with your actual API key
        $apiKey = env("WEATHER_API_KEY");
        $url = "http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$location}&aqi=no";

        // Make the HTTP request to the WeatherAPI
        $response = Http::get($url);

        // Check if the response was successful
        if ($response->successful()) {
            // Parse the response JSON to extract weather data
            $data = $response->json();

            // Return temperature and condition from the API response
            $temperature = $data['current']['temp_c'] . '°C';  // Temperature in Celsius
            $condition = $data['current']['condition']['text']; // Weather condition (e.g., Sunny)

            // Log the weather data
            Log::info("Weather data for {$location}: Temperature - {$temperature}, Condition - {$condition}");

            return [
                'temperature' => $temperature,
                'condition' => $condition,
            ];
        }else{
            Log::error("Failed to fetch weather data for {$location}: {$response->status()} - {$response->body()}");
        }

        // If the API request failed, return default values
        return [
            'temperature' => 'unknown',
            'condition' => 'unknown',
        ];
    }
}
