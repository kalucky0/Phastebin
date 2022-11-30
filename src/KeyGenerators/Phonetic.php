<?php

namespace Phastebin\KeyGenerators;

use Exception;

class Phonetic
{
    private const consonants = 'bcdfghjklmnpqrstvwxy';
    private const vowels = 'aeiou';

    /**
     * @throws Exception
     */
    public function createKey(?int $key_length = null): string
    {
        if (!$key_length)
            $key_length = 8;

        $key = '';
        for ($i = 0; $i < $key_length; $i++) {
            // Alternate between a consonant and a vowel
            $chars = ($i & 1) !== 0 ? self::consonants : self::vowels;

            // Grab a random character
            $key .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $key;
    }
}
