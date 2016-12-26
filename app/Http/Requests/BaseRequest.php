<?php

namespace App\Http\Requests;


use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use jamesvweston\Utilities\BooleanUtil AS BU;

class BaseRequest
{

    /**
     * @param   string|null     $values
     * @param   string          $fieldName
     * @return  string|null
     */
    protected function validateIds ($values, $fieldName)
    {
        if (is_null($values))
            return null;

        $values                         = explode(',', $values);
        $newValues                      = '';
        foreach ($values AS $item)
        {
            $id                         = InputUtil::getInt(trim($item));
            if (is_null($id))
                throw new BadRequestHttpException($fieldName . ' must be comma separated integers');

            $newValues                  .= $id . ',';
        }

        return rtrim($newValues, ',');
    }

    /**
     * @param   string|bool|null    $value
     * @param   string              $fieldName
     * @return  bool|null
     */
    protected function validateBoolean ($value, $fieldName)
    {
        if (is_null($value))
            return null;

        $value                          = BU::getBooleanValue($value);
        if (is_null($value))
            throw new BadRequestHttpException($fieldName . ' must be boolean value');

        return $value;
    }
}