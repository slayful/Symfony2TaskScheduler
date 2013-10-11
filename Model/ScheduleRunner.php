<?php

namespace TDM\SchedulerBundle\Model;

use TDM\SchedulerBundle\Interfaces\ScheduleRunnerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TDM\SchedulerBundle\Interfaces\ScheduledTaskInterface;
use TDM\SchedulerBundle\Exceptions\SchedulerException;
use TDM\SchedulerBundle\Interfaces\TaskExecutionRepositoryInterface;
use TDM\SchedulerBundle\Interfaces\TaskExecutionInterface;
use TDM\SchedulerBundle\Document\TaskExecution;
use \DateTime;

/**
 * Description of ScheduleRunner
 *
 * @author wpigott
 */
class ScheduleRunner implements ScheduleRunnerInterface {

    private $services = array();
    private $objectManager;

    public function __construct(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

    public function addService($serviceId, ScheduledTaskInterface $service) {
        $this->services[$serviceId] = $service;
    }

    /**
     * 
     * @return ObjectManager
     */
    protected function getObjectManager() {
        return $this->objectManager;
    }

    /**
     * 
     * @return TaskExecutionRepositoryInterface
     */
    protected function getTaskExecutionRepository() {
        return $this->getObjectManager()->getRepository('TDMSchedulerBundle:TaskExecution');
    }

    public function execute() {
        $tasks = array();
        foreach ($this->services as $serviceId => $service) {
            $tasks[$serviceId] = FALSE;
            try {
                if ($this->executeTask($serviceId, $service)) {
                    $this->getObjectManager()->flush();
                    $tasks[$serviceId] = TRUE;
                }
            } catch (SchedulerException $e) {
                continue;
            }
        }
        return $tasks;
    }

    private function executeTask($serviceId, ScheduledTaskInterface $service) {
        // Get the schedule and check if it should run now.
        if (!$service->checkShouldExecute($this->getLastTimeRun($serviceId)))
            return FALSE;

        // Create the TaskExecution object to store the records.
        $taskExecution = $this->makeTaskExecution();
        $taskExecution->setDateTime(new DateTime());
        $taskExecution->setServiceId($serviceId);

        // Execute the task.
        $start = microtime(TRUE);
        $service->execute($taskExecution);
        $taskExecution->setDuration(microtime(TRUE) - $start);

        // Persist the TaskExecution
        $this->getObjectManager()->persist($taskExecution);
        return TRUE;
    }

    /**
     * 
     * @return TaskExecutionInterface
     */
    protected function makeTaskExecution() {
        return new TaskExecution();
    }

    protected function getLastTimeRun($serviceId) {
        $last = $this->getTaskExecutionRepository()->getLastRun($serviceId);
        if ($last instanceof TaskExecutionInterface)
            return $last->getDateTime();
        return new DateTime(gmmktime());
    }

}

?>
