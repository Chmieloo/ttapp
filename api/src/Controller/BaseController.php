<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseController extends AbstractController
{
    protected $serializer;
    protected $slackKey;
    protected $guiUrl;

    /**
     * BaseController constructor.
     * @param string $slackKey
     * @param string $guiUrl
     */
    public function __construct(string $slackKey, string $guiUrl)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
        $this->slackKey = $slackKey;
        $this->guiUrl = $guiUrl;
    }

    /**
     * @param $data
     * @return Response
     */
    protected function sendJsonResponse($data)
    {
        $data = $this->serializer->serialize($data, 'json');

        $response = new Response();
        $response->setContent($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
