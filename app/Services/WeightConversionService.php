<?php

namespace App\Services;


class WeightConversionService
{

    public function __construct()
    {

    }

    /**
     * @param   float   $weight
     * @return  float
     */
    public function gramsToOunces ($weight)
    {
        return $weight / 28.3495;
    }

    /**
     * @param   float   $weight
     * @return  float
     */
    public function ouncesToGrams ($weight)
    {
        return $weight * 28.3495;
    }

}