<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\PackRepository;

class OrderService
{

    /**
     * @var PackRepository
     */
    protected $packRepository;

    /**
     * Constructor
     *
     * @param PackRepository      $packRepository
     */
    public function __construct(PackRepository $packRepository)
    {
        $this->packRepository = $packRepository;
    }

    /**
     * Create an order for the given amount of products
     *
     * @param integer $count
     * @return Order
     */
    public function makeOrder(int $count): Order
    {
        $packs = $this->packRepository->findBy([], ['count' => 'desc']);
        $order = new Order();
        foreach ($packs as $pack) {
            $order->addPack($pack, $count/$pack->getCount());
            $count %= $pack->getCount();
        }

        if ($count > 0) {
            $order->addPack(array_pop($packs), 1);
        }

        return $order;
    }
}
