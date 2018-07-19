<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sitetheory\Bundle\ProfilerStorageBundle\DependencyInjection\Compiler\ProfilerCompilerPass;

/**
 * Class SitetheoryProfilerStorageBundle.
 */
class SitetheoryProfilerStorageBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProfilerCompilerPass());
        parent::build($container);
    }
}
