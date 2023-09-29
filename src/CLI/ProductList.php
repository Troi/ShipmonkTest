<?php
declare(strict_types=1);

namespace App\CLI;

use Symfony\Component\Validator\Constraints as Assert;

class ProductList
{
    /** @var array<int, Product> */
    #[Assert\Count(min: 1, minMessage: "You must pack at least one product")]
    #[Assert\Valid]
    public array $products = [];

    public function getCacheKey(): string
    {
        $productIds = array_map(
            fn(Product $product) => $product->id, $this->products
        );
        rsort($productIds);
        return implode('_', $productIds);
    }

    public static function deserialize(string $data): ProductList
    {
        $deserializedData = json_decode($data, true);

        if ($deserializedData === null) {
            throw new \InvalidArgumentException('Invalid json input format');
        }

        $list = new ProductList();

        foreach ($deserializedData['products'] as $productData) {
            $product = new Product();
            $product->id = $productData['id'] ?? -1;
            $product->width = $productData['width'] ?? -1;
            $product->height = $productData['height'] ?? -1;
            $product->weight = $productData['weight'] ?? -1;
            $product->length = $productData['length'] ?? -1;
            $list->products[] = $product;
        }

        return $list;
    }

    public static function normalize(ProductList $productList): ProductList
    {
        // TODO: sort products
        // TODO: sort dimensions of product
        return $productList;
    }
}
