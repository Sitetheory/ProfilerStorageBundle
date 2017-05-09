<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\Profiler;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Profiler\Profiler as ProfilerBase;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;

/**
 * Class Profiler
 * @package Sitetheory\Bundle\ProfilerStorageBundle\Profiler
 */
class Profiler extends ProfilerBase
{
    /**
     * Profiler constructor.
     *
     * @param ProfilerStorageInterface $storage
     * @param LoggerInterface $logger
     * @param bool $defaultEnabled
     * @param null $class
     * @param null $dsn
     * @param null $username
     * @param null $password
     * @param int $ttl
     */
    public function __construct(ProfilerStorageInterface $storage, LoggerInterface $logger, $defaultEnabled = true, $class = null, $dsn = null, $username = null, $password = null, $ttl = 3600)
    {
        if ($defaultEnabled !== true) {
            $storage = new $class($dsn, $username, $password, $ttl);
        }
        parent::__construct($storage, $logger);
    }
}
