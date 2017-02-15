<?php

namespace App\Models\Support\Traits;


use Illuminate\Support\Str;

trait HasBarcode
{

    /**
     * @var string
     */
    protected $barCode;

    /**
     * @return string
     */
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * @param string $barCode
     */
    public function setBarCode($barCode)
    {
        $this->barCode = $barCode;
    }

    public function generateBarCode ($characters = 150)
    {
        $this->barCode = Str::random($characters);
    }
}