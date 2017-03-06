<?php

namespace App\Models\WMS;


use jamesvweston\Utilities\ArrayUtil AS AU;

class TotePick extends PickInstruction implements \JsonSerializable
{

    /**
     * @var Tote
     */
    protected $tote;


    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->tote                     = AU::get($data['tote']);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $object                         = parent::jsonSerialize();
        $object['tote']                 = $this->tote->jsonSerialize();

        return $object;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return 'TotePick';
    }

    /**
     * @return Tote
     */
    public function getTote()
    {
        return $this->tote;
    }

    /**
     * @param Tote $tote
     */
    public function setTote($tote)
    {
        $this->tote = $tote;
    }

}