<?php

namespace WPTrait\Utils;

use WPTrait\Exceptions\Json\UnableDecodeJsonException;
use WPTrait\Exceptions\Json\UnableEncodeJsonException;

class Json
{

    /**
     * Convert Json To Object
     *
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     * @return mixed
     * @throws UnableDecodeJsonException
     */
    public static function decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0): mixed
    {
        $data = json_decode($json, $assoc, $depth, self::cleanUpOptions($options));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnableDecodeJsonException(json_last_error_msg(), json_last_error());
        }

        return $data;
    }

    /**
     * Convert Array To Json
     *
     * @param $value
     * @param int $options
     * @param int $depth
     * @return string
     * @throws UnableEncodeJsonException
     */
    public static function encode($value, int $options = 0, int $depth = 512): string
    {
        $string = json_encode($value, self::cleanUpOptions($options), $depth);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnableEncodeJsonException(json_last_error_msg(), json_last_error());
        }
        return (string)$string;
    }

    /**
     * Convert Json to Array
     *
     * @throws UnableDecodeJsonException
     */
    public static function asArray(string $json)
    {
        return self::decode($json, true, );
    }

    /**
     * Check IsValid Json Text
     *
     * @param string $json
     * @return bool
     */
    public static function isValid(string $json): bool
    {
        try {
            self::decode($json);
        } catch (UnableDecodeJsonException $exception) {
            return false;
        }

        return true;
    }

    private static function cleanUpOptions(int $options): int
    {
        if (PHP_VERSION_ID >= 70300
            && defined('JSON_THROW_ON_ERROR')
            && $options >= JSON_THROW_ON_ERROR) {
            return $options - JSON_THROW_ON_ERROR;
        }

        return $options;
    }

}