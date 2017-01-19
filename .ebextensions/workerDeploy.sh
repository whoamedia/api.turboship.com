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
    pip install --install-option="--install-scripts=/usr/bin" supervisor --pre
    cp ../.ebextensions/supervisord /etc/init.d/supervisord
    chmod 777 /etc/init.d/supervisord
    mkdir -m 766 /var/log/supervisor
    umask 022
    touch /var/log/supervisor/supervisord.log
    cp .ebextensions/supervisord.conf /etc/supervisord.conf
    /etc/init.d/supervisord  start
    sudo chkconfig supervisord  on
}

#/opt/elasticbeanstalk/bin/get-config environment --output yaml | sed -n '1!p' | sed -e 's/^\(.*\): /\1=/g' > bashEnv

declare -A ary

readarray -t lines < "bashEnv"

for line in "${lines[@]}"; do
   key=${line%%=*}
   value=${line#*=}
   ary[$key]=$value
done

#if key exists and is true

if test "${ary['QUEUE_WORKER']+isset}" #if key exists
    then
        if [ ${ary['QUEUE_WORKER']} == "'true'" ] #if the value is true
            then
                echo "Found worker key!"
                echo "Copying environmental variables to dotenv"
                cp bashEnv .env
                echo "Starting worker deploy process...";

                if [ -f /etc/init.d/supervisord ];
                    then
                       echo "Config found. Supervisor already installed"
                       updateSupervisor
                    else
                       echo "No supervisor config found. Installing supervisor..."
                       installSupervisor
                    fi

                echo "Deployment done!"

            else
                echo "Worker variable set, but not true. Skipping worker installation";
        fi;

    else
        echo "No worker variable found. Skipping worker installation";
fi;