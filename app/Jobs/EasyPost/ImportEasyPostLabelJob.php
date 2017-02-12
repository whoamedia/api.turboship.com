<?php

namespace App\Jobs\EasyPost;


use App\Jobs\Job;
use App\Repositories\Doctrine\Shipments\PostageRepository;
use App\Services\EasyPost\EasyPostService;
use App\Services\S3Service;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use EntityManager;

class ImportEasyPostLabelJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var int
     */
    private $postageId;

    /**
     * @var string
     */
    private $format;

    /**
     * @var PostageRepository
     */
    private $postageRepo;

    /**
     * @var EasyPostService
     */
    private $easyPostService;

    /**
     * OrderApprovalJob constructor.
     * @param   int     $postageId
     * @param   string  $format
     */
    public function __construct($postageId, $format = 'ZPL')
    {
        parent::__construct();
        $this->postageId                = $postageId;
        $this->format                   = $format;
    }


    public function handle()
    {
        $this->postageRepo              = EntityManager::getRepository('App\Models\Shipments\Postage');
        $postage                        = $this->postageRepo->getOneById($this->postageId);

        $this->easyPostService          = new EasyPostService($postage->getRate()->getIntegratedShippingApi());

        $easyPostShipment               = $this->easyPostService->updateLabelFormat($postage->getRate()->getExternalShipmentId(), $this->format);
        $labelUrl                       = $easyPostShipment->getPostageLabel()->getLabelZplUrl();
        $labelContents                  = file_get_contents($labelUrl);

        $s3Key                          = 'postage/' . $postage->getId() . '_' . Str::random(50) . '.' . strtolower($this->format);
        $s3Service                      = new S3Service();
        $s3Url                          = $s3Service->store($s3Key, $labelContents);

        $postage->setZplPath($s3Url);
        $this->postageRepo->saveAndCommit($postage);
    }
}
