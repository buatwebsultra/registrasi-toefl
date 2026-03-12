<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RyanChandler\LaravelCloudflareTurnstile\Contracts\ClientInterface;
use RyanChandler\LaravelCloudflareTurnstile\Responses\SiteverifyResponse;

class TurnstileClient implements ClientInterface
{
    public function __construct(
        protected string $secret
    ) {}

    public function siteverify(string $token): SiteverifyResponse
    {
        try {
            // Use the provided secret or fallback to config
            $secretKey = $this->secret ?: config('services.turnstile.secret');

            $response = Http::retry(3, 100)
                ->asForm()
                ->acceptJson()
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secretKey,
                    'response' => $token,
                ]);

            if ($response->ok() && $response->json('success')) {
                return SiteverifyResponse::success();
            }

            // Correctly handle failure and extract error codes if present
            $errorCodes = $response->json('error-codes') ?? [];
            return SiteverifyResponse::failure($errorCodes);

        } catch (\Exception $e) {
            Log::error('Turnstile verification failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return SiteverifyResponse::failure(['internal-error']);
        }
    }

    public function dummy(): string
    {
        return ClientInterface::RESPONSE_DUMMY_TOKEN;
    }
}
