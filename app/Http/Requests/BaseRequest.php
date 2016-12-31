<?php

namespace App\Http\Requests;


use jamesvweston\Utilities\DateUtil;
use jamesvweston\Utilities\InputUtil;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use jamesvweston\Utilities\BooleanUtil AS BU;

class BaseRequest
{

    /**
     * @param   $value
     * @return  bool|null
     */
    protected function getBoolean ($value)
    {
        if (is_null($value))
            return null;
        else
            return BU::getBooleanValue($value);
    }

    /**
     * @param   $value
     * @return  int|null
     */
    protected function getInteger ($value)
    {
        if (is_null($value))
            return null;
        else
            return InputUtil::getInt(trim($value));
    }

    /**
     * @param   $value
     * @return  null|string
     */
    protected function getIds ($value)
    {
        if (is_null($value))
            return null;

        $value                          = explode(',', $value);
        $newValues                      = '';
        foreach ($value AS $item)
        {
            $id                         = trim($item);
            $newValues                  .= $id . ',';
        }

        return rtrim($newValues, ',');
    }

    /**
     * @param   $value
     * @return  float|int|null
     */
    protected function getFloat ($value)
    {
        if (is_null($value))
            return null;

        if (!is_null(InputUtil::getInt($value)))
            return InputUtil::getInt($value);

        return InputUtil::getFloat($value);
    }

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

        $value                          = $this->getBoolean($value);

        if (is_null($value))
            throw new BadRequestHttpException($fieldName . ' must be boolean value');

        return $value;
    }

    /**
     * @param   string|bool|null    $value
     * @param   string              $fieldName
     * @return  string|null
     */
    protected function validateDate ($value, $fieldName)
    {
        if (is_null($value))
            return null;

        if (DateUtil::validate($value, 'YYYY-MM-DD') == false)
            throw new BadRequestHttpException($fieldName . ' must be formatted as YYYY-MM-DD');

        return $value;
    }

    /**
     * @param   string|bool|null    $value
     * @param   string              $fieldName
     * @return  float|null
     */
    protected function validateFloat ($value, $fieldName)
    {
        if (is_null($value))
            return null;

        if (!is_null(InputUtil::getInt($value)))
            return InputUtil::getInt($value);

        $value                          = InputUtil::getFloat($value);
        if (is_null($value))
            throw new BadRequestHttpException($fieldName . ' must be float value');

        return $value;
    }

    /**
     * @param   string|bool|null    $value
     * @param   string              $fieldName
     * @return  string|null
     */
    protected function validateShipmentStatus ($value, $fieldName)
    {
        if (is_null($value))
            return null;

        $value                          = strtolower($value);
        if (!in_array($value, ['shipped', 'unshipped']))
            throw new BadRequestHttpException($fieldName . ' must be either shipped or unshipped');

        return $value;
    }

    /**
     * @param   string|null    $value
     * @return  string
     */
    protected function validateOrderByDirection ($value)
    {
        if (is_null($value))
            return 'ASC';

        $value                          = strtoupper($value);
        if (!in_array($value, ['ASC', 'DESC']))
            throw new BadRequestHttpException('direction must be either ASC or DESC');

        return $value;
    }

}