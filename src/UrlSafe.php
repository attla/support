<?php

namespace Attla\Support;

class UrlSafe
{
    /**
     * Encode a string with URL-safe Base64
     *
     * @param string $input The string you want encoded
     * @return string The base64 encode of what you passed in
     */
    public static function base64Encode(string $data): string
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    /**
     * Decode a string with URL-safe Base64
     *
     * @param string $data A Base64 encoded string
     * @return string A decoded string
     *
     * @throws \InvalidArgumentException invalid base64 characters
     */
    public static function base64Decode(string $data): string
    {
        $remainder = strlen($data) % 4;

        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
