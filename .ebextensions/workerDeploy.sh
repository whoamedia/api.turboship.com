#!/usr/bin/env bash

updateSupervisor(){
    cp .ebextensions/supervisord.conf /etc/supervisord.conf
    sudo service supervisord stop
    sudo service supervisord start
    #/usr/bin/supervisorctl reload
    #/usr/bin/supervisorctl reread
    #/usr/bin/supervisorctl restart all
}

installSupervisor(){
    sudo php artisan doctrine:queue:work beanstalkd --queue=shopifyOrders --tries=5 --sleep=5 --daemon
}

installSupervisor