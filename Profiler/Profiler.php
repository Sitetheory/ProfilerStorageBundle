<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\Profiler;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Profiler\Profiler as ProfilerBase;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;

/**
 * Class Profiler.
 */
class Profiler extends ProfilerBase
{
    /**
     * Profiler constructor.
     *
     * @param ProfilerStorageInterface $storage
     * @param LoggerInterface          $logger
     * @param bool                     $enable
     * @param bool                     $defaultStorage
     * @param null                     $class
     * @param null                     $dsn
     * @param null                     $username
     * @param null                     $password
     * @param int                      $ttl
     */
    public function __construct(ProfilerStorageInterface $storage, LoggerInterface $logger, $enable = true, $defaultStorage = true, $class = null, $dsn = null, $username = null, $password = null, $ttl = 3600)
    {
        if (true !== $defaultStorage) {
            $storage = new $class($dsn, $username, $password, $ttl);
        }
        parent::__construct($storage, $logger, $enable);
    }
}
