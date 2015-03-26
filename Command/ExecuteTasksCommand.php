<?php

namespace TDM\SchedulerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TDM\SchedulerBundle\Interfaces\ScheduleRunnerInterface;
use TDM\SchedulerBundle\Interfaces\TaskExecutionInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

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
        $this->addStyles($output);
        $results = $this->getScheduleRunner()->execute();
        $count = count($results);
        $output->writeln($count . ' Scheduled task' . ($count > 1 ? 's' : '') . ' identified.');
        foreach ($results as $taskId => $taskExecution) {
            if ($taskExecution instanceof TaskExecutionInterface) {
                if ($taskExecution->getStatus() == TaskExecutionInterface::STATUS_SUCCESS) {
                    $output->writeln('<info>Task \'' . $taskId . '\' was executed and was successful.</info>');
                } else if ($taskExecution->getStatus() == TaskExecutionInterface::STATUS_IN_PROGRESS) {
                    throw new \RuntimeException("Task should no longer be in progress");
                } else {
                    $output->writeln('<error>Task \'' . $taskId . '\' was executed and was not successful.</error>');
                }
            } else {
                $output->writeln('Task \'' . $taskId . '\' was not executed.');
            }
        }
    }
    
    private function addStyles(OutputInterface $output) {
        $style = new OutputFormatterStyle('red', 'black');
        $output->getFormatter()->setStyle('error', $style);
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
