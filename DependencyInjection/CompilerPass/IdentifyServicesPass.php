<?php

namespace TDM\SchedulerBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of IdentifyServicesPass
 *
 * @author wpigott
 */
class IdentifyServicesPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if (!$container->hasDefinition('tdm_scheduler.schedule_runner'))
            return;

        $definition = $container->getDefinition('tdm_scheduler.schedule_runner');

        $taggedServices = $container->findTaggedServiceIds('tdm-scheduled-task');
        foreach ($taggedServices as $id => $tagAttributes) {
            $definition->addMethodCall('addService', array($id, new Reference($id)));
        }
    }

}

?>
