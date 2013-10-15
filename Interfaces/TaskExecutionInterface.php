<?php

namespace TDM\SchedulerBundle\Interfaces;

use \DateTime;

/**
 *
 * @author wpigott
 */
interface TaskExecutionInterface {
    
    const STATUS_FAILED = 'FAILED';
    const STATUS_SUCCESS = 'SUCCESS';

    public function getId();

    public function getServiceId();

    public function setServiceId($serviceId);

    public function getMessages();

    public function addMessage($message);

    public function getDuration();

    public function setDuration($duration);

    public function getDateTime();

    public function setDateTime(DateTime $dateTime);

    public function getStatus();

    public function setStatus($status);
}

?>
