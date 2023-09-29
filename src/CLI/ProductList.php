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

    public function __construct(array $products)
    {
        $this->products = $products;
    }

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

        $list = new ProductList([]);

        foreach ($deserializedData['products'] as $productData) {
            $list->products[] = new Product(
                $productData['id'] ?? -1,
            $productData['width'] ?? -1,
            $productData['height'] ?? -1,
            $productData['weight'] ?? -1,
            $productData['length'] ?? -1,
            );
        }

        return $list;
    }

    public static function normalize(ProductList $productList): ProductList
    {
        $normalizedProducts = [];

        foreach ($productList->products as $product) {
            $rawDimensions = [$product->length, $product->height, $product->width];
            rsort($rawDimensions);
            $normalizedProducts[] = new Product(
                $product->id,
                $rawDimensions[0],
                $rawDimensions[1],
                $rawDimensions[2],
                $product->weight,
            );
        }

        usort($normalizedProducts, fn(Product $p1, Product $p2) => $p1->width <=> $p2->width);
        return new ProductList($normalizedProducts);
    }
}
