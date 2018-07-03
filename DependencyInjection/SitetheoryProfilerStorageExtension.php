<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;

/**
 * Class SitetheoryProfilerStorageExtension.
 */
class SitetheoryProfilerStorageExtension extends FrameworkExtension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Root Configuration Parameters
        $container->setParameter('sitetheory_profiler_storage.profiler.defaultStorage', $config['profiler']['defaultStorage']);
        $container->setParameter('sitetheory_profiler_storage.profiler.class', $config['profiler']['class']);
        $container->setParameter('sitetheory_profiler_storage.profiler.dsn', $config['profiler']['dsn']);
        $container->setParameter('sitetheory_profiler_storage.profiler.username', $config['profiler']['username']);
        $container->setParameter('sitetheory_profiler_storage.profiler.password', $config['profiler']['password']);
        $container->setParameter('sitetheory_profiler_storage.profiler.ttl', $config['profiler']['ttl']);
    }
}
