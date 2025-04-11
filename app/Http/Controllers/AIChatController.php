<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class AIChatController extends Controller
{
    public function index()
    {
        return view('aichat'); // we'll create this view
    }

    public function ask(Request $request)
    {
        $ollama = \ArdaGnsrn\Ollama\Ollama::client();

        $completions = $ollama->completions()->create([
            'model' => 'gemma3:1b',
            'prompt' => $request->input('prompt'),
        ]);

        $response =$completions->response; // '...in a land far, far away...'



        return view('aichat', [
            'response' => $response ?? 'No reply.',
            'prompt' => $request->input('prompt')
        ]);
    }
}
