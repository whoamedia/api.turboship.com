<?php

function generateProgram($connection, $queue, $tries, $sleep, $numProcs, $startSecs){
    $program = <<<EOT

[program:$queue]
command=sudo php artisan doctrine:queue:work $connection --queue=$queue --tries=$tries --sleep=$sleep --daemon
directory=/var/app/current/
autostart=true
autorestart=true
process_name=$queue-%(process_num)s
numprocs=$numProcs
startsecs=$startSecs

EOT;
    return $program;
}
$programs           = '';
$programs           .= generateProgram('beanstalkd', 'shopifyOrders', 5, 5, 2, 1);

$superLocation = '';
if(file_exists('/var/app/ondeck/.ebextensions/supervisord.conf'))
    $superLocation = '/var/app/ondeck/.ebextensions/supervisord.conf';
else if(file_exists('/var/app/current/.ebextensions/supervisord.conf'))
    $superLocation = '/var/app/current/.ebextensions/supervisord.conf';

file_put_contents($superLocation, $programs.PHP_EOL, FILE_APPEND);

