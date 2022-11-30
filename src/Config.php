<?php

namespace Phastebin;

class Config
{
    public final const displayErrorDetails = false;

    public final const storeType = 'File';
    public final const keygenType = 'Phonetic';

    public final const storageDir = '../data/';
    public final const redisConnection = 'tcp://127.0.0.1:6379';
}