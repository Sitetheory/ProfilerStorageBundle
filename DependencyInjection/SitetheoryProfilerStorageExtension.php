<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class SitetheoryProfilerStorageExtension
 * @package Sitetheory\Bundle\ProfilerStorageBundle\DependencyInjection
 */
class SitetheoryProfilerStorageExtension extends FrameworkExtension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Root Configuration Parameters
        $container->setParameter('sitetheory_profiler_storage.profiler.defaultEnabled',$config['profiler']['defaultStorage']);
        $container->setParameter('sitetheory_profiler_storage.profiler.class',$config['profiler']['class']);
        $container->setParameter('sitetheory_profiler_storage.profiler.dsn',$config['profiler']['dsn']);
        $container->setParameter('sitetheory_profiler_storage.profiler.username',$config['profiler']['username']);
        $container->setParameter('sitetheory_profiler_storage.profiler.password',$config['profiler']['password']);
        $container->setParameter('sitetheory_profiler_storage.profiler.ttl',$config['profiler']['ttl']);
    }
}
