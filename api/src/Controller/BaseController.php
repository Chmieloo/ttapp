<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Persistence\ManagerRegistry;

class BaseController extends AbstractController
{
    protected Serializer $serializer;
    protected string $slackKey = "";
    protected string $guiUrl;
    protected ObjectManager $manager;

    /**
     * BaseController constructor.
     * @param ManagerRegistry $mr
     * @param string $slackKey
     * @param string $guiUrl
     */
    public function __construct(ManagerRegistry $mr, string $slackKey, string $guiUrl)
    {
        $this->manager = $mr->getManager();
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        /*
        For slack integration
        if ($slackKey) {
            $data = explode(';', $slackKey);
            foreach ($data as $item) {
                $line = explode('|', $item);
                if (is_array($line)) {
                    $this->slackKey[$line[0]] = trim($line[1]);
                }
            }
        }
        */

        $this->serializer = new Serializer($normalizers, $encoders);
        $this->guiUrl = $guiUrl;
    }

    /**
     * @param $data
     * @return Response
     */
    protected function sendJsonResponse($data): Response
    {
        $data = $this->serializer->serialize($data, 'json');

        $response = new Response();
        $response->setContent($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
