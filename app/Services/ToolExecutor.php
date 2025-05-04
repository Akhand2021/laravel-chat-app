<?php
namespace App\Services;

use App\Agents\WeatherAgent; // Assuming WeatherAgent exists in the Helpers folder

class ToolExecutor
{
    public static function handle(string $action): string
    {
        if (str_starts_with($action, 'getWeather')) {
            preg_match('/location="([^"]+)"/', $action, $matches);
            $location = $matches[1] ?? 'Unknown';

            // Using WeatherAgent to get the weather data
            $weatherData = WeatherAgent::getWeather($location);

            return "It's {$weatherData['temperature']} and {$weatherData['condition']} in {$location}.";
        }

        return "Unknown tool or parameters.";
    }
}
