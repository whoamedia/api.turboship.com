<?php

namespace App\Http\Controllers;


use App\Http\Requests\Totes\CreateTote;
use App\Http\Requests\Totes\GetTotes;
use App\Http\Requests\Totes\ShowTote;
use App\Http\Requests\Totes\UpdateTote;
use App\Models\WMS\Tote;
use Illuminate\Http\Request;
use EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToteController extends BaseAuthController
{

    /**
     * @var \App\Repositories\Doctrine\WMS\ToteRepository
     */
    private $toteRepo;


    public function __construct ()
    {
        $this->toteRepo                  = EntityManager::getRepository('App\Models\WMS\Tote');
    }


    public function index (Request $request)
    {
        $getTotes                       = new GetTotes($request->input());
        $getTotes->setOrganizationIds(parent::getAuthUserOrganization()->getId());
        $query                          = $getTotes->jsonSerialize();

        $results                        = $this->toteRepo->where($query, false);
        return response($results);
    }

    public function store (Request $request)
    {
        $createTote                     = new CreateTote($request->input());
        $createTote->validate();
        $createTote->clean();

        $toteQuery          = [
            'organizationIds'           => parent::getAuthUserOrganization()->getId(),
            'barCodes'                  => $createTote->getBarCode(),
        ];
        $toteResults                     = $this->toteRepo->where($toteQuery);
        if (sizeof($toteResults) != 0)
            throw new BadRequestHttpException('Tote barCode must be unique');

        $json                           = $createTote->jsonSerialize();
        $tote                           = new Tote($json);
        $tote->setOrganization(parent::getAuthUserOrganization());

        $this->toteRepo->saveAndCommit($tote);
        return response($tote, 201);
    }

    public function show (Request $request)
    {
        $tote                            = $this->getToteFromRoute($request->route('id'));
        return response($tote);
    }

    public function update (Request $request)
    {
        $updateTote                      = new UpdateTote($request->input());
        $updateTote->setId($request->route('id'));
        $updateTote->validate();
        $updateTote->clean();

        $tote                            = $this->getToteFromRoute($updateTote->getId());

        if (!is_null($updateTote->getBarCode()) && $tote->getBarCode() != $updateTote->getBarCode())
        {
            $toteQuery       = [
                'organizationIds'       => parent::getAuthUserOrganization()->getId(),
                'barCodes'              => $updateTote->getBarCode(),
            ];
            $toteResults                 = $this->toteRepo->where($toteQuery);
            if (sizeof($toteResults) != 0)
                throw new BadRequestHttpException('Tote barCode must be unique');

            $tote->setBarCode($updateTote->getBarCode());
        }

        if (!is_null($updateTote->getWeight()))
            $tote->setWeight($updateTote->getWeight());

        $this->toteRepo->saveAndCommit($tote);
        return response($tote);
    }

    /**
     * @param   int     $id
     * @return  Tote
     */
    private function getToteFromRoute ($id)
    {
        $showTote                        = new ShowTote();
        $showTote->setId($id);
        $showTote->validate();
        $showTote->clean();

        $tote                            = $this->toteRepo->getOneById($showTote->getId());
        if (is_null($tote))
            throw new NotFoundHttpException('Tote not found');
        if ($tote->getOrganization()->getId() != parent::getAuthUserOrganization()->getId())
            throw new NotFoundHttpException('Tote not found');

        return $tote;
    }

}