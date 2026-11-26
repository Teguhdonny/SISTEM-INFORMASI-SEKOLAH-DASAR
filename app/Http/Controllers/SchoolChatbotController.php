<?php

namespace App\Http\Controllers;

use App\Services\SchoolChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolChatbotController extends Controller
{
    public function __invoke(Request $request, SchoolChatbotService $chatbot): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['sometimes', 'array', 'max:8'],
            'history.*.role' => ['required', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:1000'],
        ]);

        return response()->json([
            'message' => isset($validated['history'])
                ? $chatbot->ask($validated['message'], $validated['history'])
                : $chatbot->ask($validated['message']),
        ]);
    }
}
