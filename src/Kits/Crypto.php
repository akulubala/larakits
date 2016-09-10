<?php

namespace Tks\Larakits\Kits;

use Illuminate\Support\Facades\Config;
use Tks\Larakits\Encryption\LaravelCrypto;
use Tks\Larakits\Encryption\LarakitsEncrypter;

class Crypto
{
    /**
     * Make the value shareable
     * @return LarakitsEncrypter
     */
    public static function shareable()
    {
        $key = Config::get('gondolyn.gondolyn.authKeys')[0] ?: "someRandomString";
        return (new LarakitsEncrypter($key, $key));
    }

    /**
     * Encrypt using the Laravel Crypto
     *
     * @param string $value
     * @return string
     */
    public static function encrypt($value)
    {
        return (new LaravelCrypto())->encrypt($value);
    }

    /**
     * Decrypt using the Laravel Crypto
     *
     * @param string $value
     * @return string
     */
    public static function decrypt($value)
    {
        return (new LaravelCrypto())->decrypt($value);
    }

    /**
     * Generate a UUID
     *
     * @return string
     */
    public static function uuid()
    {
        return (new LaravelCrypto())->uuid();
    }
}
