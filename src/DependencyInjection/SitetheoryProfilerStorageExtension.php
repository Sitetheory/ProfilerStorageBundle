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

        foreach ($config['profiler'] as $key => $value) {
            $container->setParameter($this->getAlias().'.profiler.'.$key, $value);
        }
    }
}
