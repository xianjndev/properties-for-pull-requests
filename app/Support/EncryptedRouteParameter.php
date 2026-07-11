<?php

namespace App\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class EncryptedRouteParameter
{
    public function __construct(
        private readonly string $key,
        private readonly string $cipher = 'AES-256-CBC',
    ) {}

    public static function fromConfig(): self
    {
        return new self(
            (string) config('app.key'),
            (string) config('app.cipher', 'AES-256-CBC'),
        );
    }

    public function encrypt(string $value): string
    {
        $key = $this->encryptionKey();
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));
        $encrypted = openssl_encrypt($value, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new RuntimeException('Unable to encrypt route parameter.');
        }

        $iv = base64_encode($iv);
        $value = base64_encode($encrypted);

        return $this->base64UrlEncode(json_encode([
            'iv' => $iv,
            'value' => $value,
            'mac' => $this->mac($iv, $value, $key),
        ], JSON_THROW_ON_ERROR));
    }

    public function decrypt(string $payload): string
    {
        $key = $this->encryptionKey();
        $decoded = json_decode($this->base64UrlDecode($payload), true);

        if (! is_array($decoded) || ! isset($decoded['iv'], $decoded['value'], $decoded['mac'])) {
            throw new InvalidArgumentException('Invalid encrypted route parameter.');
        }

        if (! hash_equals($this->mac($decoded['iv'], $decoded['value'], $key), $decoded['mac'])) {
            throw new InvalidArgumentException('Encrypted route parameter MAC is invalid.');
        }

        $iv = base64_decode($decoded['iv'], true);
        $value = base64_decode($decoded['value'], true);

        if ($iv === false || $value === false) {
            throw new InvalidArgumentException('Encrypted route parameter payload is invalid.');
        }

        $decrypted = openssl_decrypt($value, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new InvalidArgumentException('Unable to decrypt route parameter.');
        }

        return $decrypted;
    }

    private function encryptionKey(): string
    {
        if ($this->key === '') {
            throw new RuntimeException('Missing application encryption key.');
        }

        $key = str_starts_with($this->key, 'base64:')
            ? base64_decode(substr($this->key, 7), true)
            : $this->key;

        if ($key === false || $key === '') {
            throw new RuntimeException('Invalid application encryption key.');
        }

        if (ctype_xdigit($key) && strlen($key) === 64) {
            return hex2bin($key);
        }

        return strlen($key) === 32
            ? $key
            : hash('sha256', $key, true);
    }

    private function mac(string $iv, string $value, string $key): string
    {
        return hash_hmac('sha256', $iv.$value, $key);
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        $decoded = base64_decode(strtr($value, '-_', '+/').str_repeat('=', (4 - strlen($value) % 4) % 4), true);

        if ($decoded === false || ! Str::isJson($decoded)) {
            throw new InvalidArgumentException('Invalid encrypted route parameter encoding.');
        }

        return $decoded;
    }
}
