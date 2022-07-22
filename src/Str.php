<?php

namespace Attla\Support;

class Str
{
    /**
     * Check value if was valid base64
     *
     * @param string $data
     * @return bool
     */
    public static function isBase64($data): bool
    {
        return is_string($data) && base64_encode(base64_decode($data)) === $data;
    }

    /**
     * Check value if was http query
     *
     * @param string $data
     * @return bool
     */
    public static function isHttpQuery($data): bool
    {
        if (!is_string($data) || !$data) {
            return false;
        }

        return preg_match('/^([+\w\.\/%_-]+=([+\w\.\/%_-]*)?(&[+\w\.\/%_-]+(=[+\w\.\/%_-]*)?)*)?$/', $data)
            ? true
            : false;
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
        } catch (\Exception) {
            return false;
        }

        return serialize($unserialized) === $data;
    }
}
