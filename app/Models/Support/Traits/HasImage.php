<?php

namespace App\Models\Support\Traits;


use App\Models\Support\Image;

trait HasImage
{

    /**
     * @var Image|null
     */
    protected $image;


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

}