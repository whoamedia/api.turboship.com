<?php

namespace App\Models\OMS;


use App\Models\BaseModel;
use App\Models\CMS\Client;
use App\Models\Locations\Country;
use App\Models\Locations\Validation\CountryValidation;
use App\Models\OMS\Validation\VariantValidation;
use App\Utilities\CountryUtility;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class Variant extends BaseModel implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Country
     */
    protected $countryOfOrigin;

    /**
     * @var CRMSource
     */
    protected $crmSource;

    /**
     * Shopify title
     * @var string
     */
    protected $title;

    /**
     * Shopify price
     * @var float
     */
    protected $price;

    /**
     * Shopify barcode
     * @var string;
     */
    protected $barcode;

    /**
     * The original unmodified sku
     * @var string|null
     */
    protected $originalSku;

    /**
     * Shopify sku
     * @var string
     */
    protected $sku;

    /**
     * Shopify grams (converted to ounces)
     * @var float
     */
    protected $weight;

    /**
     * Shopify id
     * @var string
     */
    protected $externalId;

    /**
     * Shopify created_at
     * @var \DateTime
     */
    protected $externalCreatedAt;

    /**
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * ProductAlias constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->externalCreatedAt        = new \DateTime();  // Default it so the field doesn't have to be nullable

        $this->product                  = AU::get($data['product']);
        $this->client                   = AU::get($data['client']);
        $this->countryOfOrigin          = AU::get($data['countryOfOrigin']);
        $this->crmSource                = AU::get($data['crmSource']);
        $this->title                    = AU::get($data['title']);
        $this->price                    = AU::get($data['price']);
        $this->barcode                  = AU::get($data['barcode']);
        $this->originalSku              = AU::get($data['originalSku']);
        $this->sku                      = AU::get($data['sku']);
        $this->weight                   = AU::get($data['weight']);
        $this->externalId               = AU::get($data['externalId']);

        if (is_null($this->countryOfOrigin))
        {
            $countryValidation          = new CountryValidation();
            $this->countryOfOrigin      = $countryValidation->idExists(CountryUtility::UNITED_STATES);
        }
    }

    public function validate()
    {
        //  Validate required fields

        if (is_null($this->title) || empty(trim($this->title)))
            throw new MissingMandatoryParametersException('title is required');

        if (is_null($this->price) || empty(trim($this->price)))
            throw new MissingMandatoryParametersException('price is required');

        if (is_null($this->barcode) || empty(trim($this->barcode)))
            throw new MissingMandatoryParametersException('barcode is required');

        if (is_null($this->sku) || empty(trim($this->sku)))
            throw new MissingMandatoryParametersException('sku is required');

        if (is_null($this->weight) || empty(trim($this->weight)))
            throw new MissingMandatoryParametersException('weight is required');

        //  Validate formatting of fields
        if ($this->weight <= 0)
            throw new BadRequestHttpException('weight must be greater than 0');


        $variantValidation              = new VariantValidation();
        $variantValidation->validateUniqueClientSku($this);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->id;
        $object['title']                = $this->title;
        $object['price']                = $this->price;
        $object['barcode']              = $this->barcode;
        $object['sku']                  = $this->sku;
        $object['originalSku']          = $this->originalSku;
        $object['weight']               = $this->weight;
        $object['client']               = $this->client->jsonSerialize();
        $object['countryOfOrigin']      = $this->countryOfOrigin->jsonSerialize();
        $object['crmSource']            = $this->crmSource->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['externalId']           = $this->externalId;
        $object['externalCreatedAt']    = $this->externalCreatedAt;

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
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Country
     */
    public function getCountryOfOrigin()
    {
        return $this->countryOfOrigin;
    }

    /**
     * @param Country $countryOfOrigin
     */
    public function setCountryOfOrigin($countryOfOrigin)
    {
        $this->countryOfOrigin = $countryOfOrigin;
    }

    /**
     * @return CRMSource
     */
    public function getCrmSource()
    {
        return $this->crmSource;
    }

    /**
     * @param CRMSource $crmSource
     */
    public function setCrmSource($crmSource)
    {
        $this->crmSource = $crmSource;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }

    /**
     * @return null|string
     */
    public function getOriginalSku()
    {
        return $this->originalSku;
    }

    /**
     * @param null|string $originalSku
     */
    public function setOriginalSku($originalSku)
    {
        $this->originalSku = $originalSku;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return \DateTime
     */
    public function getExternalCreatedAt()
    {
        return $this->externalCreatedAt;
    }

    /**
     * @param \DateTime $externalCreatedAt
     */
    public function setExternalCreatedAt($externalCreatedAt)
    {
        $this->externalCreatedAt = $externalCreatedAt;
    }


}