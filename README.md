# Symfony2TaskScheduler #

## Overview ##

Allows for any services to be executed on schedules specific to those services.

Set your cron to run ```app/console scheduler:execute``` every 60 seconds, 5 minutes, hours, etc.

All services are checked for when they last ran and the service determines if it should run again.

All executions are logged along with any messages into a mongodb collection.

## Installation ##

The best way to install the bundle is via Composer.  

1) Go to the ```require``` section of your composer.json file and add

```json
"tdm/symfony-task-scheduler": "0.1.*@dev"
```

to the section, along with other packages you require.  Now run ```composer.phar update```.

2) Add Symfony2TaskScheduler to your application kernel:

```php
<?php

// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new TDM\SchedulerBundle\TDMSchedulerBundle(),
        // ...
    );
}
```

3) The bundle is now installed, and you should see no differences in your application performance.  You still need to write some task services that will be called by the scheduler.

## Usage ##

A scheduled task is simply a service that implements ```TDM\SchedulerBundle\Interfaces\ScheduledTaskInterface``` and has a tag of ```tdm-scheduled-task``` on the service definition.  The service will be detected by the scheduler when the cache is reloaded and will then be worked into the mix.

A task is required to have at least the following two methods (required by the interface):
* checkShouldExecute - Is passed a date time object representing the last time the task was executed.  (The DateTime is set to the unix epoch if no records are found).  This method then returns TRUE or FALSE to alert the system if it is time to process again.  This allows the task to determine when it should process.
* execute - Receives the $taskExecution object which is then used to log details about the executed task.  All messages and logs should be stored using this object.  The status should be set to one of the possible values in the TaskExecutionInterface upon completion (or failure).

