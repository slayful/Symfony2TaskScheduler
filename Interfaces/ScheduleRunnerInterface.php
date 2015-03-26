<?php

namespace TDM\SchedulerBundle\Interfaces;

/**
 *
 * @author wpigott
 */
interface ScheduleRunnerInterface {

    public function execute();
    
    public function addService($serviceId, ScheduledTaskInterface $service);
}

?>
