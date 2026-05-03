<?php

namespace App\Http\Controllers\Child;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HabitantVoiceController extends Controller
{
    public function speak(Request $request)
    {
        $validated = $request->validate([
            'text' => ['required', 'string', 'max:220'],
            'voice_type' => ['nullable', 'string', 'max:80'],
        ]);

        $text = trim(strip_tags($validated['text']));
        $voiceType = $validated['voice_type'] ?? 'default';

        if ($text === '') {
            return response()->json([
                'success' => false,
                'message' => 'Voice text is empty.',
            ], 422);
        }

        $apiKey = config('services.openai.api_key');
        $model = config('services.openai.tts_model', 'gpt-4o-mini-tts');
        $voice = config('services.openai.tts_voice', 'shimmer');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'OpenAI API key is missing. Please check .env and config/services.php.',
                'status' => 500,
            ], 500);
        }

        /*
         * Auto-cache generated voice.
         * This avoids repeated OpenAI charges for the same line.
         */
        $hash = sha1($model . '|' . $voice . '|' . $voiceType . '|' . $text);
        $filePath = "habitant/generated-voices/{$hash}.mp3";

        if (Storage::disk('public')->exists($filePath)) {
            return response()->json([
                'success' => true,
                'audio_url' => Storage::disk('public')->url($filePath),
                'cached' => true,
            ]);
        }

        $instructions = match ($voiceType) {
            'hungry' => 'Speak in a soft, cute, slightly sad but child-friendly female voice. Natural, sweet, not robotic.',
            'foodStart', 'eating', 'afterFood' => 'Speak in a happy, sweet, playful female voice for a children game. Natural and cute.',
            'playStart', 'playing', 'afterPlay' => 'Speak in an excited, sweet, joyful female voice for a children game. Natural and energetic.',
            'guideTheme', 'guideBackground', 'guideAvatar', 'guideFood', 'guideDecor' => 'Speak like a warm, friendly female guide in a children game. Clear, sweet, and gentle.',
            default => 'Speak in a sweet, soft, friendly female voice for a children game. Sound natural, warm, cute, and not robotic.',
        };

        $response = Http::withToken($apiKey)
            ->timeout(60)
            ->asJson()
            ->withHeaders([
                'Accept' => 'audio/mpeg',
            ])
            ->post('https://api.openai.com/v1/audio/speech', [
                'model' => $model,
                'voice' => $voice,
                'input' => $text,
                'instructions' => $instructions,
                'response_format' => 'mp3',
            ]);

        if (!$response->successful()) {
            $status = $response->status();
            $errorBody = $response->json();

            Log::error('Habitant voice generation failed.', [
                'status' => $status,
                'body' => $response->body(),
            ]);

            $message = 'Voice generation failed.';

            if ($status === 429) {
                $message = 'OpenAI voice quota is finished. Please check billing, credits, or project usage limit.';
            }

            if ($status === 401) {
                $message = 'OpenAI API key is invalid, revoked, or belongs to the wrong project.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'status' => $status,
                'error' => $errorBody,
            ], $status);
        }

        Storage::disk('public')->put($filePath, $response->body());

        return response()->json([
            'success' => true,
            'audio_url' => Storage::disk('public')->url($filePath),
            'cached' => false,
        ]);
    }
}