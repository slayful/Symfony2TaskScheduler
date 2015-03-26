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

    /**
     * Changing the execution so that only one task is executed.
     * @return array
     */
    public function execute() {
        $tasks = [];
        foreach ($this->services as $serviceId => $service) {
            $tasks[$serviceId] = FALSE;
            try {
                $tasks[$serviceId] = $this->executeTask($serviceId, $service);
            } catch (SchedulerException $e) {
                continue;
            }
        }
        return $tasks;
    }

    /**
     *
     * @param string $serviceId
     * @param ScheduledTaskInterface $service
     * @return TaskExecutionInterface|boolean
     */
    private function executeTask($serviceId, ScheduledTaskInterface $service) {
        // Get the schedule and check if it should run now.
        if (!$service->checkShouldExecute($this->getLastTimeRun($serviceId))) {
            return FALSE;
        }

        // Create the TaskExecution object to store the records.
        $taskExecution = $this->makeTaskExecution($serviceId);

        // Execute the task.
        $start = microtime(TRUE);
        $service->execute($taskExecution);
        $taskExecution->setDuration((microtime(TRUE) - $start) * 1000);
        $this->getObjectManager()->flush();
        return $taskExecution;
    }

    /**
     *
     * @param $serviceId
     * @return TaskExecutionInterface
     */
    protected function makeTaskExecution($serviceId) {
        $execution = new TaskExecution();
        $execution->setDateTime(new DateTime());
        $execution->setServiceId($serviceId);
        $execution->setStatus(TaskExecutionInterface::STATUS_IN_PROGRESS);

        // Persist the TaskExecution
        $this->getObjectManager()->persist($execution);
        $this->getObjectManager()->flush();

        return $execution;
    }

    protected function getLastTimeRun($serviceId) {
        $last = $this->getTaskExecutionRepository()->getLastRun($serviceId);
        if ($last instanceof TaskExecutionInterface) {
            return $last->getDateTime();
        }
        return new DateTime('1-1-1970');
    }

}

?>
