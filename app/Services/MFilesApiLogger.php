<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MFilesApiLogger
{
    public function log(
        string $eventType,
        string $apiCalled,
        string $syncRequestId,
        array $requestBody,
        array $response,
        ?int $statusCode,
        int $durationMs,
    ): void {
        $logUrl = config('services.match.mfiles_technical_description_api_logs_url');
        $logToken = config('services.match.token');

        if (blank($logUrl) || blank($logToken)) {
            Log::warning('Match M-Files technical description API logging is not configured.', [
                'url_configured' => filled($logUrl),
                'token_configured' => filled($logToken),
                'event_type' => $eventType,
            ]);

            return;
        }

        try {
            $logResponse = Http::acceptJson()
                ->withToken($logToken)
                ->withoutRedirecting()
                ->connectTimeout(3)
                ->timeout(5)
                ->post($logUrl, [
                    'event_type' => $eventType,
                    'api_called' => $apiCalled,
                    'sync_request_id' => $syncRequestId,
                    'status_code' => $statusCode,
                    'duration_ms' => $durationMs,
                    'request_body' => $requestBody,
                    'response' => $response,
                ]);

            if ($logResponse->failed()) {
                Log::warning('Match rejected the M-Files technical description API log.', [
                    'event_type' => $eventType,
                    'status' => $logResponse->status(),
                    'location' => $logResponse->header('Location'),
                    'response_body' => Str::limit($logResponse->body(), 2000),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('Unable to send the M-Files technical description API log to Match.', [
                'event_type' => $eventType,
                'exception_class' => $exception::class,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
