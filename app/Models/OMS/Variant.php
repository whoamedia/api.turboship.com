<?php

namespace App\Models\OMS;


use App\Models\BaseModel;
use App\Models\CMS\Client;
use App\Models\Locations\Country;
use App\Models\Locations\Validation\CountryValidation;
use App\Models\OMS\Validation\VariantValidation;
use App\Models\Support\Source;
use App\Models\Support\Traits\HasBarcode;
use App\Models\WMS\Bin;
use App\Models\WMS\VariantInventory;
use App\Utilities\CountryUtility;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class Variant extends BaseModel implements \JsonSerializable
{

    use HasBarcode;


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
     * @var Source
     */
    protected $source;

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
     * @var ArrayCollection
     */
    protected $inventory;

    /**
     * Shopify inventory_quantity
     * @var int
     */
    protected $externalInventoryQuantity;

    /**
     * @var int
     */
    protected $totalQuantity;

    /**
     * @var int
     */
    protected $readyQuantity;

    /**
     * @var int
     */
    protected $reservedQuantity;

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
        $this->inventory                = new ArrayCollection();
        $this->createdAt                = new \DateTime();
        $this->externalCreatedAt        = new \DateTime();  // Default it so the field doesn't have to be nullable

        $this->product                  = AU::get($data['product']);
        $this->client                   = AU::get($data['client']);
        $this->countryOfOrigin          = AU::get($data['countryOfOrigin']);
        $this->source                   = AU::get($data['source']);
        $this->title                    = AU::get($data['title']);
        $this->price                    = AU::get($data['price']);
        $this->barCode                  = AU::get($data['barCode']);
        $this->originalSku              = AU::get($data['originalSku']);
        $this->sku                      = AU::get($data['sku']);
        $this->weight                   = AU::get($data['weight']);
        $this->externalId               = AU::get($data['externalId']);
        $this->externalInventoryQuantity= AU::get($data['externalInventoryQuantity'], 0);
        $this->totalQuantity            = AU::get($data['totalQuantity'], 0);
        $this->readyQuantity            = AU::get($data['readyQuantity'], 0);
        $this->reservedQuantity         = AU::get($data['reservedQuantity'], 0);

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

        if (is_null($this->barCode) || empty(trim($this->barCode)))
            throw new MissingMandatoryParametersException('barCode is required');

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
        $object['barCode']              = $this->barCode;
        $object['sku']                  = $this->sku;
        $object['originalSku']          = $this->originalSku;
        $object['weight']               = $this->weight;
        $object['client']               = $this->client->jsonSerialize();
        $object['countryOfOrigin']      = $this->countryOfOrigin->jsonSerialize();
        $object['source']               = $this->source->jsonSerialize();
        $object['createdAt']            = $this->createdAt;
        $object['externalId']           = $this->externalId;
        $object['externalCreatedAt']    = $this->externalCreatedAt;
        $object['totalQuantity']        = $this->totalQuantity;
        $object['readyQuantity']        = $this->readyQuantity;
        $object['reservedQuantity']     = $this->reservedQuantity;

        $object['product']              = [
            'id'                        => $this->product->getId(),
            'name'                      => $this->product->getName(),
        ];

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
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param Source $source
     */
    public function setSource($source)
    {
        $this->source = $source;
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

    /**
     * @return int
     */
    public function getExternalInventoryQuantity()
    {
        return $this->externalInventoryQuantity;
    }

    /**
     * @param int $externalInventoryQuantity
     */
    public function setExternalInventoryQuantity($externalInventoryQuantity)
    {
        $this->externalInventoryQuantity = $externalInventoryQuantity;
    }

    /**
     * @return int
     */
    public function getTotalQuantity()
    {
        return $this->totalQuantity;
    }

    /**
     * @param int $totalQuantity
     */
    public function setTotalQuantity($totalQuantity)
    {
        $this->totalQuantity = $totalQuantity;
    }

    /**
     * @return int
     */
    public function getReadyQuantity()
    {
        return $this->readyQuantity;
    }

    /**
     * @param int $readyQuantity
     */
    public function setReadyQuantity($readyQuantity)
    {
        $this->readyQuantity = $readyQuantity;
    }

    /**
     * @return int
     */
    public function getReservedQuantity()
    {
        return $this->reservedQuantity;
    }

    /**
     * @param int $reservedQuantity
     */
    public function setReservedQuantity($reservedQuantity)
    {
        $this->reservedQuantity = $reservedQuantity;
    }

    /**
     * @return VariantInventory[]
     */
    public function getInventory ()
    {
        return $this->inventory->toArray();
    }

    /**
     * @param   VariantInventory $inventory
     * @throws  \Exception
     */
    public function addInventory ($inventory)
    {
        if (is_null($inventory->getInventoryLocation()))
            throw new \Exception('InventoryLocation must be set to VariantInventory prior to adding it to Variant');

        $inventory->setVariant($this);
        $inventory->setOrganization($this->getClient()->getOrganization());
        $this->inventory->add($inventory);
    }

}