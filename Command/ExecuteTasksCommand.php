<?php

namespace TDM\SchedulerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TDM\SchedulerBundle\Interfaces\ScheduleRunnerInterface;

/**
 * Description of ExecuteTasksCommand
 *
 * @author wpigott
 */
class ExecuteTasksCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('scheduler:execute')->setDescription('Executes all scheduled tasks.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        ini_set('memory_limit', '512M');
        $results = $this->getScheduleRunner()->execute();
        $output->writeln(count($results) . ' Scheduled tasks successfully executed.');
        foreach ($results as $taskId => $status) {
            if ($status)
                $output->writeln('Task \'' . $taskId . '\' was executed.');
            else
                $output->writeln('Task \'' . $taskId . '\' was not executed.');
        }
    }

    /**
     * 
     * @return ScheduleRunnerInterface
     */
    protected function getScheduleRunner() {
        return $this->getContainer()->get('tdm_scheduler.schedule_runner');
    }

}

?>
