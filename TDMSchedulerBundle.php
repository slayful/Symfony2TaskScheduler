<?php

namespace TDM\SchedulerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TDM\SchedulerBundle\DependencyInjection\CompilerPass\IdentifyServicesPass;

class TDMSchedulerBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new IdentifyServicesPass());
    }

}
