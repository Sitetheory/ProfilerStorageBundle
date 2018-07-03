<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Sitetheory\Bundle\ProfilerStorageBundle\Profiler\MongoDbProfilerStorage;
use Sitetheory\Bundle\ProfilerStorageBundle\Profiler\Profiler;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Profiler\Profile;

class FunctionalTest extends TestCase
{
    /**
     * @var KernelInterface
     */
    //private $kernel;

    /**
     * @var Profiler
     */
    private $profiler;

    protected function setUp()
    {
        /* TODO: There are more tests we could run with a kernel present *
        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();
        /* */
        //$this->kernel->getContainer()->get('yaml.test');
    }

    protected function tearDown()
    {
        /* *
        $this->kernel->shutdown();
        /* */
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected function makeToken($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return $string;
    }

    /**
     * @return MongoDbProfilerStorage|Profiler
     */
    public function getProfiler()
    {
        if (null !== $this->profiler) {
            return $this->profiler;
        }

        $profiler = new MongoDbProfilerStorage('mongodb://127.0.0.1/test/profiler');

        return $this->profiler = $profiler;
    }

    public function testConnect()
    {
        $this->assertNotEmpty($this->getProfiler());
    }

    public function testPurge()
    {
        $this->assertNotEmpty($this->getProfiler()->purge());
    }

    public function testReadWrite()
    {
        // Store Token
        $token = $this->makeToken();

        // Run Profile
        $profile = new Profile($token);
        $profile->setTime(time());
        $profile->setIp('127.0.0.1');
        $profile->setUrl('http://127.0.0.1/');
        $profile->setMethod('GET');
        $profile->setStatusCode(500);

        // Write
        $this->assertTrue($this->getProfiler()->write($profile), 'Write Profile');

        // Read
        $profiles = $this->getProfiler()->find(
            $profile->getIp(),
            $profile->getUrl(),
            20,
            $profile->getMethod(),
            null,
            null,
            $profile->getStatusCode()
        );

        // Ensure Profiles Exist
        $this->assertNotEmpty($profiles);
    }
}
