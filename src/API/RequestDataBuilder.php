<?php

namespace App\API;

use App\CLI\Product;
use App\Entity\Packaging;

class RequestDataBuilder
{
    private ?string $username = null;
    private ?string $apiKey = null;
    private array $products = [];
    private array $boxes = [];
    private array $parameters = [];

    public function setAuth(string $userName, string $apiKey): void
    {
        $this->username = $userName;
        $this->apiKey = $apiKey;
    }

    // TODO: use DTO
    public function addProduct(Product $productData): void
    {
        $this->products[] = $productData;
    }

    // TODO: use DTO
    public function addBox(Packaging $boxData): void
    {
        $this->boxes[] = $boxData;
    }

    public function setParameter(string $name, string $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function build(): array
    {
        return [
            'username' => $this->username,
            'api_key' => $this->apiKey,
            'items' => array_map(fn (Product $productData) => [ // FIXME: check parameters
                "id" => $productData->id,
                "w" => $productData->width,
                "h" => $productData->height,
                "d" => $productData->length,
                "wg" => $productData->weight,
                "q" => $productData->length,
                "vr" => true,
            ], $this->products),
            'bins' => array_map(fn (Packaging $boxData) => [ // FIXME: check parameters
                "id" => $boxData->getId(),
                "h" => $boxData->getHeight(),
                "w" => $boxData->getWidth(),
                "d" => $boxData->getLength(),
                "wg" => "",
                "max_wg" => $boxData->getMaxWeight(),
                "q" => null,
                "cost" => 0,
                "type" => "box"
            ], $this->boxes),
            'params' => $this->parameters,
        ];
    }
}
