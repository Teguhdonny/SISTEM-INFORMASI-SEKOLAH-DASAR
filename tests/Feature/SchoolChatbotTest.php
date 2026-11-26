<?php

namespace Tests\Feature;

use App\Services\SchoolChatbotService;
use Mockery;
use Tests\TestCase;

class SchoolChatbotTest extends TestCase
{
    public function test_public_chatbot_returns_an_ai_answer(): void
    {
        $chatbot = Mockery::mock(SchoolChatbotService::class);
        $chatbot->shouldReceive('ask')
            ->once()
            ->with('Kapan pendaftaran PPDB?')
            ->andReturn('Pendaftaran PPDB mengikuti periode yang diumumkan sekolah.');
        $this->app->instance(SchoolChatbotService::class, $chatbot);

        $response = $this->postJson(route('chatbot.message'), [
            'message' => 'Kapan pendaftaran PPDB?',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Pendaftaran PPDB mengikuti periode yang diumumkan sekolah.');

    }

    public function test_public_chatbot_validates_the_question(): void
    {
        $response = $this->postJson(route('chatbot.message'), [
            'message' => str_repeat('x', 1001),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['message']);
    }
}
