<?php

namespace Phastebin\Stores;

use Phastebin\Config;
use Predis\Client;
use Predis\Response\Status;

class Redis
{
    private readonly Client $redis;

    public function __construct()
    {
        $this->redis = new Client(Config::redisConnection);
    }

    public function set(string $key, $content): Status
    {
        $keyName = $this->generateKeyName($key);
        return $this->redis->set($keyName, $content);
    }

    public function get(string $key): ?string
    {
        $keyName = $this->generateKeyName($key);
        return $this->redis->get($keyName);
    }

    private function generateKeyName(string $key): string
    {
        return 'docs.' . $key;
    }
}
