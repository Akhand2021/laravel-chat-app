<?php

namespace App\Helpers;

class ActionParser
{
    public static function extract(string $response): ?string
    {
        // Check if the AI response contains an action
        if (preg_match('/Action:\s*(\S+)\(location="([^"]+)"\)/i', $response, $matches)) {
            return "{$matches[1]}(location=\"{$matches[2]}\")";
        }

        // If no action is found, return null
        return null;
    }
}
