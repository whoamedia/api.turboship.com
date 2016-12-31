<?php

namespace App\Jobs;

use EntityManager;

abstract class Job
{

    public function __construct()
    {
        /**
         * Connect if the connection is not available
         */
        if (!EntityManager::getConnection()->ping())
        {
            EntityManager::getConnection()->close();
            EntityManager::getConnection()->connect();
        }

        if (!EntityManager::isOpen())
        {
            EntityManager::getConnection()->connect();
        }
    }

}