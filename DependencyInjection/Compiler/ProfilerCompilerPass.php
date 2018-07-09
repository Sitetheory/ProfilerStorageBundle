<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProfilerCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('profiler');
        // Compatibility for Definitions from Symfony 2.7 to 3.3
        if (2 === count($definition->getArguments())) {
            $definition->addArgument(true);
        }
        $definition->addArgument('%sitetheory_profiler_storage.profiler.defaultStorage%');
        $definition->addArgument('%sitetheory_profiler_storage.profiler.class%');
        $definition->addArgument('%sitetheory_profiler_storage.profiler.dsn%');
        $definition->addArgument('%sitetheory_profiler_storage.profiler.username%');
        $definition->addArgument('%sitetheory_profiler_storage.profiler.password%');
        $definition->addArgument('%sitetheory_profiler_storage.profiler.ttl%');
        $definition->setClass('Sitetheory\Bundle\ProfilerStorageBundle\Profiler\Profiler');
    }
}
