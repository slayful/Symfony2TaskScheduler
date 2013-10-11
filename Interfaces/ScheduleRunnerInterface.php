<?php

namespace TDM\SchedulerBundle\Interfaces;

use TDM\SchedulerBundle\Interfaces\ScheduledTaskInterface;

/**
 *
 * @author wpigott
 */
interface ScheduleRunnerInterface {

    public function execute();
    
    public function addService($serviceId, ScheduledTaskInterface $service);
}

?>
