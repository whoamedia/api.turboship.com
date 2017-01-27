<?php

namespace App\Console\Commands;


use App\Repositories\Doctrine\Integrations\IntegratedWebHookRepository;
use Illuminate\Console\Command;

class RebootApplicationCommand extends Command
{

    protected $signature = 'turboship:reboot';

    protected $description = 'Reverses migrations. Migrates. Seeds.';


    /**
     * @var IntegratedWebHookRepository
     */
    protected $integratedWebHookRepo;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('app.env') == 'dev')
        {
            try
            {
                $process = new \Symfony\Component\Process\Process('sudo /usr/sbin/service supervisor stop');
                $process->run();

                if (!$process->isSuccessful()) {
                    $this->info($process->getErrorOutput());
                }
            }
            catch (\Exception $exception)
            {
                $this->info($exception->getMessage());
            }
        }

        try {
            $this->call('migrate:refresh', [
                '--seed' => 1
            ]);
        }
        catch (\Exception $ex)
        {
            $this->info('migrate:refresh --seed threw the following Exception');
            $this->error($ex->getMessage());
        }

        $this->call('turboship:client:create', [
            'clientName'    =>  'james@turboship.com',
            'clientId'      =>  'seloVYGtW6yFM1iz',
            'clientSecret'  =>  'b175ZuxK0041VTYU1fLJoxVT72CrqG1v'
        ]);


        if (config('app.env') == 'dev')
        {
            try
            {
                $process = new \Symfony\Component\Process\Process('sudo /usr/sbin/service supervisor start');
                $process->run();

                if (!$process->isSuccessful()) {
                    $this->info($process->getErrorOutput());
                }
            }
            catch (\Exception $exception)
            {
                $this->info($exception->getMessage());
            }
        }
    }

}
