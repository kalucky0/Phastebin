<?php

namespace Phastebin\KeyGenerators;

use Exception;

class Random
{
    private const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    private const charsLength = 62;

    /**
     * @throws Exception
     */
    public function createKey(?int $key_length = null): string
    {
        if (!$key_length)
            $key_length = 8;

        $key = '';
        for ($i = 0; $i < $key_length; $i++) {
            // Grab a random character
            $key .= self::chars[random_int(0, self::charsLength - 1)];
        }

        return $key;
    }
}
