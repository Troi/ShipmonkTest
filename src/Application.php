<?php

namespace App;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Application
{

    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function run(RequestInterface $request): ResponseInterface
    {
        // TODO: deserialize input into ProductList
        // TODO: validate input data, use symfony validator
        // TODO: normalize input data, reorder sizes by size
        // TODO: call PackagingService
        // TODO: serialize response
        return new Response();
    }

}
