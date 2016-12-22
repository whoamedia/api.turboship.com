<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateClientIntegration implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $integrationId;

    /**
     * @var string
     */
    protected $symbol;

    /**
     * @var CreateClientCredential[]
     */
    protected $credentials;


    public function __construct($data = [])
    {
        $this->id                       = AU::get($data['id']);
        $this->integrationId            = AU::get($data['integrationId']);
        $this->symbol                   = AU::get($data['symbol']);

        $this->credentials              = [];
        $credentials                    = AU::get($data['credentials'], []);

        if (!empty($credentials))
        {
            foreach ($credentials AS $item)
            {
                $this->credentials[]    = new CreateClientCredential($item);
            }
        }
    }

    public function validate()
    {
        if (is_null($this->id))
            throw new BadRequestHttpException('id is required');

        if (is_null($this->integrationId))
            throw new BadRequestHttpException('integrationId is required');

        if (is_null($this->symbol))
            throw new BadRequestHttpException('symbol is required');

        if (is_null($this->credentials))
            throw new BadRequestHttpException('credentials is required');


        if (is_null(InputUtil::getInt($this->id)))
            throw new BadRequestHttpException('id must be integer');

        if (is_null(InputUtil::getInt($this->integrationId)))
            throw new BadRequestHttpException('integrationId must be integer');

        if (empty(trim($this->symbol)))
            throw new BadRequestHttpException('symbol must be string');
        if (empty($this->credentials))
            throw new BadRequestHttpException('credentials is required');

        foreach ($this->credentials AS $credential)
            $credential->validate();
    }

    public function clean ()
    {
        $this->id                       = InputUtil::getInt($this->id);
        $this->integrationId            = InputUtil::getInt($this->integrationId);
        foreach ($this->credentials AS $credential)
            $credential->clean();
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['id']                   = $this->id;
        $object['integrationId']        = $this->integrationId;
        $object['symbol']               = $this->symbol;
        $object['credentials']          = [];
        foreach ($this->credentials AS $credential)
            $object['credentials']      = $credential->jsonSerialize();

        return $object;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIntegrationId()
    {
        return $this->integrationId;
    }

    /**
     * @param int $integrationId
     */
    public function setIntegrationId($integrationId)
    {
        $this->integrationId = $integrationId;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return CreateClientCredential[]
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param CreateClientCredential[] $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

}