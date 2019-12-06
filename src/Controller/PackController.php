<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Repository\PackRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PackController extends AbstractController
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
    public function __construct(PackRepository $packRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->packRepository = $packRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/packs", name="packs.get", methods={"GET"})
     */
    public function packs(): Response
    {
        $packs = $this->packRepository->findBy([], ['count' => 'desc']);
        return new Response(($this->serializer->serialize($packs, 'json', ['output'])), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/packs", name="packs.add", methods={"POST"})
     */
    public function addPack(RequestStack $rs)
    {
        $request = $rs->getCurrentRequest();
        $entityManager = $this->getDoctrine()->getManager();

        try {
            $pack = $this->serializer->deserialize($request->getContent(), Pack::class, "json");

            $errors = $this->validator->validate($pack);
            if ($errors->count() > 0) {
                return new Response("Invalid input", 400);
            }
            $entityManager->persist($pack);
            $entityManager->flush();
            return new Response(($this->serializer->serialize($pack, 'json', ['output'])), 200, ['Content-Type' => 'application/json']);
        } catch (\Throwable $e) {
            return new Response("Invalid input", 400);
        }
    }
}
