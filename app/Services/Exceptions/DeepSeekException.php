<?php

namespace App\Services\Exceptions;

use RuntimeException;

class DeepSeekException extends RuntimeException
{
    /**
     * Build an exception for a non-2xx API response.
     *
     * @param  array<string, mixed>|string|null  $body
     */
    public static function fromResponse(int $status, array|string|null $body = null): self
    {
        $detail = is_array($body) ? json_encode($body) : (string) $body;

        return new self("DeepSeek API returned HTTP {$status}: {$detail}", $status);
    }

    /**
     * Build an exception for connection / timeout failures.
     */
    public static function fromConnection(\Throwable $previous): self
    {
        return new self('DeepSeek API request failed: ' . $previous->getMessage(), 0, $previous);
    }

    /**
     * Build an exception when the integration is not configured.
     */
    public static function missingApiKey(): self
    {
        return new self('DeepSeek API key is not configured (set DEEPSEEK_API_KEY).', 500);
    }
}
