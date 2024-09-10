<?php

namespace App\Modules\Security;

use App\Modules\Security\Exceptions\EncodingTokenError;
use App\Modules\Security\Exceptions\UnknownTokenType;

class JWTToken
{
    /**
     * Период после которого access_token будет недействителен
     * Значение - 3 часа
     * Величина - секунды.
     *
     * @var int
     */
    private const int ACCESS_TOKEN_EXPIRED_PERIOD = 10800000;

    public const string TYPE_ACCESS = 'access';

    /**
     * Формирует поля необходимые для JWT токена.
     *
     * @throws UnknownTokenType
     */
    private function formJWTHeader(string $tokenType): array
    {
        $now = time();

        try {
            $expire = match ($tokenType) {
                self::TYPE_ACCESS => self::ACCESS_TOKEN_EXPIRED_PERIOD,
            };
        } catch (\UnhandledMatchError) {
            throw new UnknownTokenType();
        }

        return [
            'iat' => $now,
            'exp' => $now + $expire,
        ];
    }

    public function decodeToken(string $encodedHead, string $encodedPayload): array
    {
        $head = $this->base64url_decode($encodedHead);
        $payload = $this->base64url_decode($encodedPayload);
        $decodedHead = json_decode($head, true);
        $decodedPayload = json_decode($payload, true);

        return array_merge($decodedHead, $decodedPayload);
    }

    public function decodeSignature(string $encodedServerSignature): array|bool
    {
        // Расшифровываем подпись
        $serverSignature = $this->base64url_decode($encodedServerSignature);
        // Вытаскиваем из подписи заголовок и груз
        $signature = explode('.', $serverSignature);
        if (2 !== count($signature)) {
            return false;
        }

        return $signature;
    }

    public function generateToken(string $tokenType = self::TYPE_ACCESS, array $payload = []): string
    {
        $jwtHeader = $this->formJWTHeader($tokenType);

        $encodedHeader = json_encode($jwtHeader, JSON_UNESCAPED_UNICODE);
        $encodedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

        if (false === $encodedHeader || false === $encodedPayload) {
            throw new EncodingTokenError();
        }

        // Кодируем заголовок и тело
        $encodedHeader = $this->base64url_encode($encodedHeader);
        $encodedPayload = $this->base64url_encode($encodedPayload);

        // Формируем подпись сервера
        $signature = $this->base64url_encode("$encodedHeader.$encodedPayload");

        // Формируем полный токен
        return "$encodedHeader.$encodedPayload.$signature";
    }

    private function base64url_encode($data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64url_decode($data): bool|string
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
