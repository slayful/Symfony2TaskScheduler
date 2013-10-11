Symfony2TaskScheduler
===================

Allows for any services to be executed on schedules specific to those services.

Set your cron to run every 60 seconds, 5 minutes, hours, etc.

All services are checked for when they last ran and the service determines if it should run again.

All executions are logged along with any messages.
