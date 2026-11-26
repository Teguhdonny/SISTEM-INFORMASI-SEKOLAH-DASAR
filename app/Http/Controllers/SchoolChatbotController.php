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
        ]);

        return response()->json([
            'message' => $chatbot->ask($validated['message']),
        ]);
    }
}
