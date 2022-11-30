<?php

namespace Phastebin\Stores;

use Phastebin\Config;

class File
{
    public function set(string $key, $content): bool|string
    {
        $filename = Config::storageDir . $this->generateFilename($key);

        $save = file_put_contents($filename, $content);

        return $save === false ? false : $key;
    }

    public function get(string $key): bool|string
    {
        $filename = Config::storageDir . $this->generateFilename($key);

        if (!is_readable($filename))
            return false;

        return file_get_contents($filename);
    }

    private function generateFilename(string $key): string
    {
        return md5($key);
    }
}
