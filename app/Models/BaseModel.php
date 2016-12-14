<?php

namespace App\Models;


abstract class BaseModel implements \JsonSerializable
{

    public abstract function validate();

}