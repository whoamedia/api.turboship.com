<?php

namespace App\Console\Commands;


use App\Models\OAuth\OAuthClient;
use App\Repositories\Doctrine\OAuth\OAuthClientRepository;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use EntityManager;

class ClientCreatorCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'turboship:client:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new OAuth Client';


    /**
     * @var OAuthClientRepository
     */
    private $oAuthClientRepo;


    public function __construct()
    {
        parent::__construct();

        $this->oAuthClientRepo          = EntityManager::getRepository('App\Models\OAuth\OAuthClient');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $clientName                 = $this->argument('clientName');
        $clientId                   = $this->argument('clientId');
        $clientSecret               = $this->argument('clientSecret');

        $data = [
            'id'                    => $clientId,
            'name'                  => $clientName,
            'secret'                => $clientSecret
        ];

        $oAuthClient                = new OAuthClient($data);

        $this->oAuthClientRepo->saveAndCommit($oAuthClient);

        $this->info('Client created successfully');
        $this->info('Client Name: ' . $oAuthClient->getName());
        $this->info('Client ID: ' . $oAuthClient->getId());
        $this->info('Client Secret: ' . $oAuthClient->getSecret());
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            ['clientName', InputArgument::REQUIRED, 'The Client\'s name'],
            ['clientId', InputArgument::OPTIONAL, 'Client ID to use. A random one will be generated if none is provided.', null],
            ['clientSecret', InputArgument::OPTIONAL, 'Client Secret to use. A random one will be generated if none is provided.', null],
        ];
    }
}