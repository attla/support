<?php

namespace Attla\Support;

use Illuminate\Support\Str as LaravelStr;

class Str
{
    /**
     * Check value if was valid base64.
     *
     * @param string $data
     * @return bool
     */
    public static function isBase64($data): bool
    {
        return is_string($data) && base64_encode(base64_decode($data)) === $data;
    }

    /**
     * Get base64 length.
     *
     * @param string $data
     * @return int
     */
    public static function strlenBase64($data): int
    {
        return static::isBase64($data)
            ? strlen(rtrim($data, '=')) * 3 / 4
            : 0;
    }

    /**
     * Determines if an string is binary.
     *
     * @param string $data
     * @return bool
     */
    public static function isBinary($data): bool
    {
        return is_string($data) && preg_match('~[^\x20-\x7E\t\r\n]~', $data) > 0;
    }

    /**
     * Check value if was http query
     *
     * @param string $data
     * @return bool
     */
    public static function isHttpQuery($data): bool
    {
        return $data
            && is_string($data)
            && preg_match('/^([+\w\.\/%_-]+=([+\w\.\/%_-]*)?(&[+\w\.\/%_-]+(=[+\w\.\/%_-]*)?)*)?$/', $data) > 0;
    }

    /**
     * Check value if was serialized
     *
     * @param string $data
     * @return bool
     */
    public static function isSerialized($data): bool
    {
        if (!is_string($data) || !$data) {
            return false;
        }

        try {
            $unserialized = @unserialize($data);
        } catch (\Exception $e) {
            return false;
        }

        return serialize($unserialized) === $data;
    }

    /**
     * Determines if an string is hex.
     *
     * @param string $data
     * @return bool
     */
    public static function isHex(string $data): bool
    {
        try {
            return ctype_xdigit($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Remove prefix from string
     *
     * @param string $data
     * @param string[] ...$prefixes
     * @return string
     */

    public static function removePrefix(string $data, ...$prefixes): string
    {
        foreach ($prefixes as $prefix) {
            if (
                LaravelStr::startsWith(
                    strtolower($data),
                    strtolower($prefix = (string) $prefix)
                )
            ) {
                $data = lcfirst(substr($data, strlen($prefix)));
            }
        }

        return $data;
    }

    /**
     * Split multi bytes string.
     *
     * @param string $data
     * @return array
     */
    public static function multiByteSplit(string $data): array
    {
        return preg_split('/(?!^)(?=.)/u', $data) ?: [];
    }
}
