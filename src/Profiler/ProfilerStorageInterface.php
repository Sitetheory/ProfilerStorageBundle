<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\Profiler;

use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface as ProfilerStorageBase;

interface ProfilerStorageInterface extends ProfilerStorageBase
{
    /**
     * ProfilerStorageInterface constructor.
     *
     * @param $dsn
     * @param $username
     * @param $password
     * @param $ttl
     */
    public function __construct($dsn, $username, $password, $ttl);
}
