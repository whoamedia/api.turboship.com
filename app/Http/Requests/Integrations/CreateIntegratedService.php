<?php

namespace App\Http\Requests\Integrations;


use App\Http\Requests\_Contracts\Cleanable;
use App\Http\Requests\_Contracts\Validatable;
use jamesvweston\Utilities\ArrayUtil AS AU;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class CreateIntegratedService implements Cleanable, Validatable, \JsonSerializable
{

    /**
     * @var int
     */
    protected $integrationId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var CreateCredential[]
     */
    protected $credentials;

    /**
     * @var CreateWebHook[]
     */
    protected $webHooks;


    public function __construct($data = [])
    {
        $this->integrationId            = AU::get($data['integrationId']);
        $this->name                     = AU::get($data['name']);

        $this->credentials              = [];
        $credentials                    = AU::get($data['credentials'], []);

        if (!empty($credentials))
        {
            foreach ($credentials AS $item)
            {
                $this->credentials[]    = new CreateCredential($item);
            }
        }

        $this->webHooks                 = [];
        $webHooks                       = AU::get($data['webHooks'], []);
        foreach ($webHooks AS $item)
        {
            $this->webHooks[]           = new CreateWebHook($item);
        }
    }

    public function validate()
    {
        if (is_null($this->integrationId))
            throw new BadRequestHttpException('integrationId is required');

        if (is_null($this->name))
            throw new BadRequestHttpException('name is required');

        if (is_null($this->credentials))
            throw new BadRequestHttpException('credentials is required');


        if (is_null(InputUtil::getInt($this->integrationId)))
            throw new BadRequestHttpException('integrationId must be integer');

        if (empty(trim($this->name)))
            throw new BadRequestHttpException('name must be string');
        if (empty($this->credentials))
            throw new BadRequestHttpException('credentials is required');

        foreach ($this->credentials AS $credential)
            $credential->validate();

        foreach ($this->webHooks AS $webHook)
            $webHook->validate();
    }

    public function clean ()
    {
        $this->integrationId            = InputUtil::getInt($this->integrationId);

        foreach ($this->credentials AS $credential)
            $credential->clean();

        foreach ($this->webHooks AS $webHook)
            $webHook->clean();
    }

    /**
     * @return array
     */
    public function jsonSerialize ()
    {
        $object['integrationId']        = $this->integrationId;
        $object['name']                 = $this->name;

        $object['credentials']          = [];
        foreach ($this->credentials AS $credential)
            $object['credentials']      = $credential->jsonSerialize();

        $object['webHooks']             = [];
        foreach ($this->webHooks AS $webHook)
            $object['webHooks'][]       = $webHook->jsonSerialize();

        return $object;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return CreateCredential[]
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param CreateCredential[] $credentials
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @return CreateWebHook[]
     */
    public function getWebHooks()
    {
        return $this->webHooks;
    }

    /**
     * @param CreateWebHook[] $webHooks
     */
    public function setWebHooks($webHooks)
    {
        $this->webHooks = $webHooks;
    }

}