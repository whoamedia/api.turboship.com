<?php

namespace App\Jobs\Orders;

use App\Jobs\Job;
use App\Repositories\Doctrine\OMS\OrderRepository;
use App\Services\Order\OrderApprovalService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EntityManager;

class OrderApprovalJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var int
     */
    private $orderId;

    /**
     * @var OrderRepository
     */
    private $orderRepo;

    /**
     * @var OrderApprovalService
     */
    private $orderApprovalService;

    /**
     * OrderApprovalJob constructor.
     * @param   int     $orderId
     */
    public function __construct($orderId)
    {
        parent::__construct();
        $this->orderId                  = $orderId;
    }


    public function handle()
    {
        $this->orderRepo                = EntityManager::getRepository('App\Models\OMS\Order');
        $order                          = $this->orderRepo->getOneById($this->orderId);
        $this->orderApprovalService     = new OrderApprovalService();
        $order                          = $this->orderApprovalService->processOrder($order);
        $this->orderRepo->saveAndCommit($order);
    }
}
