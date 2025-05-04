<?php

namespace App\Services;

use \ArdaGnsrn\Ollama\Ollama;

class OllamaService
{
    public static function chat(string $prompt): string
    {
        $ollama =  Ollama::client();
        $response = '';

        foreach (
            $ollama->completions()->createStreamed([
                'model' => 'gemma3:1b',
                'prompt' => $prompt,
            ]) as $chunk
        ) {
            $response .= $chunk->response;
        }

        return $response;
    }
}
