<?php

namespace App;

use App\CLI\ProductList;
use App\Exception\NoSuitableBoxException;
use App\Service\BoxingService;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Application
{
    public function __construct(
        private readonly BoxingService $boxingService
    )
    {
    }

    public function run(RequestInterface $request): ResponseInterface
    {
        // TODO: deserialize input into ProductList
        $productList = new ProductList();
        // TODO: validate input data, use symfony validator
        // TODO: normalize input data, reorder sizes by size
        // TODO: call BoxingService
        try {
            $box = $this->boxingService->getBox($productList);
        } catch (NoSuitableBoxException $noSuitableBoxException) {
            return new Response(400, [], 'These products can\'t be packed.');
        }
        // TODO: serialize response
        return new Response(200,  [], json_encode($box));
    }

}
