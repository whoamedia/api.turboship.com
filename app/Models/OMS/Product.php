<?php

namespace App\Models\OMS;


use App\Models\BaseModel;
use App\Models\CMS\Client;
use App\Models\Support\Image;
use Doctrine\Common\Collections\ArrayCollection;
use jamesvweston\Utilities\ArrayUtil AS AU;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Product extends BaseModel implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $id;

    /**
     * Mapped to Shopify title
     * @var string
     */
    protected $name;

    /**
     * Mapped to Shopify body_html
     * @var string|null
     */
    protected $description;

    /**
     * @var Image|null
     */
    protected $image;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var ArrayCollection
     */
    protected $aliases;

    /**
     * @var ArrayCollection
     */
    protected $variants;

    /**
     * @var ArrayCollection
     */
    protected $images;

    /**
     * Product constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->createdAt                = new \DateTime();
        $this->aliases                  = new ArrayCollection();
        $this->variants                 = new ArrayCollection();
        $this->images                   = new ArrayCollection();

        $this->name                     = AU::get($data['name']);
        $this->description              = AU::get($data['description']);
        $this->image                    = AU::get($data['image']);
        $this->client                   = AU::get($data['client']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object['id']                   = $this->getId();
        $object['name']                 = $this->getName();
        $object['image']                = !is_null($this->image) ? $this->image->jsonSerialize() : null;
        $object['client']               = $this->getClient()->jsonSerialize();
        $object['description']          = $this->description;
        $object['createdAt']            = $this->createdAt;

        return $object;
    }

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Image|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image|null $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param ProductAlias $alias
     */
    public function addAlias (ProductAlias $alias)
    {
        $alias->setProduct($this);
        $alias->setClient($this->client);
        $this->aliases->add($alias);
    }

    /**
     * @return ProductAlias[]
     */
    public function getAliases ()
    {
        return $this->aliases->toArray();
    }

    /**
     * @param ProductAlias $productAlias
     */
    public function removeAlias (ProductAlias $productAlias)
    {
        $this->aliases->removeElement($productAlias);
    }

    /**
     * @param   Variant $variant
     * @return  Variant
     * @throws  BadRequestHttpException
     */
    public function addVariant(Variant $variant)
    {
        foreach ($this->getVariants() AS $item)
        {


        }

        $variant->setProduct($this);
        $variant->setClient($this->client);
        $this->variants->add($variant);

        return $variant;
    }

    /**
     * @return Variant[]
     */
    public function getVariants ()
    {
        return $this->variants->toArray();
    }

    /**
     * @param   int     $id
     * @return  Variant|null
     */
    public function getVariantById ($id)
    {
        foreach ($this->getVariants() AS $variant)
        {
            if ($variant->getId() == $id)
                return $variant;
        }
        return null;
    }

    /**
     * @param Variant $variant
     */
    public function removeVariant (Variant $variant)
    {
        $this->variants->removeElement($variant);

    }

    /**
     * @return Image[]
     */
    public function getImages ()
    {
        return $this->images->toArray();
    }

    /**
     * @param   string      $externalId
     * @return  Image|null
     */
    public function getImageByExternalId ($externalId)
    {
        foreach ($this->getImages() AS $image)
        {
            if ($image->getExternalId() == $externalId)
                return $image;
        }
        return null;
    }

    /**
     * @param Image $image
     */
    public function addImage (Image $image)
    {
        $this->images->add($image);
    }

    /**
     * @param Image $image
     */
    public function removeImage (Image $image)
    {
        //  If the image we are removing is the Product's primary image, set the primary image to null
        if (!is_null($this->image))
        {
            if ($this->image->getId() == $image->getId())
                $this->setImage(null);
        }
        $this->images->removeElement($image);
    }

}
