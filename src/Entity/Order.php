<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Order
{
    /**
     * @var array
     * @Groups("output")
     */
    private $packs;

    public function __construct() {
        $this->packs = [];
    }

    /**
     * Add a given quantity of packs
     *
     * @param Pack $pack
     * @param integer $quantity
     * @return self
     */
    public function addPack(Pack $pack, int $quantity) : self
    {
        if ($quantity) {
            $this->packs[$pack->getCount()] = ($this->packs[$pack->getCount()] ?? 0) + $quantity;
        }
        return $this;
    }

    /**
     * @Groups("output")
     * @SerializedName("order")
     * @return array
     */
    public function getLines(): array
    {
        return $this->packs;
    }
}
