<?php

namespace Tests;


use App\Models\CMS\Validation\ClientValidation;
use EntityManager;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var ClientValidation
     */
    protected $clientValidation;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $this->clientValidation         = new ClientValidation(EntityManager::getRepository('App\Models\CMS\Client'));

        return $app;
    }
}
