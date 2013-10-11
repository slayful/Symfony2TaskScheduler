<?php

namespace TDM\SchedulerBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use TDM\SchedulerBundle\Interfaces\TaskExecutionRepositoryInterface;

/**
 * Description of TaskExecutionDocument
 *
 * @author wpigott
 */
class TaskExecutionDocument extends DocumentRepository implements TaskExecutionRepositoryInterface {

    public function getLastRun($serviceId) {
        $qb = $this->createQueryBuilder();
        $qb->find($this->getClassName());
        $qb->field('serviceId')->equals($serviceId);
        $qb->sort('dateTime');
        return $qb->getQuery()->getSingleResult();
    }

}

?>
