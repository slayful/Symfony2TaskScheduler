<?php

namespace TDM\SchedulerBundle\Document;

use TDM\SchedulerBundle\Interfaces\TaskExecutionInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use \DateTime;

/**
 * Description of TaskExecution
 *
 * @author wpigott
 * @MongoDB\Document( collection="task_execution", repositoryClass="TDM\SchedulerBundle\Repository\TaskExecution"  )
 */
class TaskExecution implements TaskExecutionInterface {

    /**
     * @MongoDB\Id(strategy="UUID")
     * @var string 
     */
    private $id;

    /**
     * @MongoDB\String
     * @var string 
     */
    private $serviceId;

    /**
     * @MongoDB\Hash
     * @var array 
     */
    private $messages = array();

    /**
     * @MongoDB\Int
     * @var integer
     */
    private $duration;

    /**
     * @MongoDB\Date
     * @var DateTime 
     */
    private $dateTime;

    /**
     * @MongoDB\String
     * @var string 
     */
    private $status;

    public function getId() {
        return $this->id;
    }

    public function getServiceId() {
        return $this->serviceId;
    }

    public function setServiceId($serviceId) {
        $this->serviceId = $serviceId;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function addMessage($message) {
        $this->messages[] = $message;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
    }

    public function getDateTime() {
        return $this->dateTime;
    }

    public function setDateTime(DateTime $dateTime) {
        $this->dateTime = $dateTime;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

}

?>
