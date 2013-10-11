<?php

namespace TDM\SchedulerBundle\Interfaces;

use Doctrine\Common\Persistence\ObjectRepository;

/**
 *
 * @author wpigott
 */
interface TaskExecutionRepositoryInterface extends ObjectRepository {

    public function getLastRun($serviceId);
}

?>
