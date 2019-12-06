<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Repository\PackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @var PackRepository
     */
    protected $packRepository;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor
     *
     * @param PackRepository      $packRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(PackRepository $packRepository, SerializerInterface $serializer, OrderService $orderService)
    {
        $this->packRepository = $packRepository;
        $this->serializer = $serializer;
        $this->orderService = $orderService;
    }

    /**
     * @Route("/order", name="order.post", methods={"POST"})
     */
    public function order(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();

        $input = json_decode($request->getContent(), true);

        if (is_null($input) || ! isset($input['count']) || $input['count'] < 1) {
            return new Response("Invalid input", 400);
        }

        $order = $this->orderService->makeOrder($input['count']);
        return new Response(($this->serializer->serialize($order, 'json', ['output'])), 200, ['Content-Type' => 'application/json']);
    }
}
