<?php

namespace App\Jobs\Inventory;


use App\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class ReadyInventoryAddedJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var int
     */
    private $variantId;


    public function __construct($variantId)
    {
        parent::__construct();
        $this->variantId                = $variantId;
    }
}