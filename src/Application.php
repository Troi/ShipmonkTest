<?php

namespace App;

use App\CLI\NormalizerService;
use App\CLI\ProductList;
use App\CLI\ProductListUtils;
use App\Exception\NoSuitableBoxException;
use App\Service\BoxingService;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Application
{
    public function __construct(
        private readonly BoxingService $boxingService,
        private readonly ValidatorInterface $validator
    )
    {
    }

    public function run(RequestInterface $request): ResponseInterface
    {
        $productList = ProductList::deserialize($request->getBody()->getContents());
        $violations = $this->validator->validate($productList); // FIXME: validator is wrongly configured
        if ($violations->count() > 0) {
            return new Response(400, [], 'These products can\'t be packed: '.implode("\n", iterator_to_array($violations)));
        }
        $normalizedProductList = ProductList::normalize($productList);
        try {
            $box = $this->boxingService->getBox($normalizedProductList);
        } catch (NoSuitableBoxException $noSuitableBoxException) {
            return new Response(400, [], 'These products can\'t be packed.');
        }
        // TODO: serialize response
        return new Response(200,  [], json_encode($box));
    }

}
