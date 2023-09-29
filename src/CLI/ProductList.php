<?php

namespace App\CLI;

class ProductList
{
    /** @var array<int, Product> */
    public array $products = [];

    public static function deserialize(string $data): ProductList
    {
        $deserializedData = json_decode($data, true);

        $list = new ProductList();

        foreach ($deserializedData as $productData) {
            // FIXME: deserialize in right way
            $product = new Product();
            $product->id = $productData['id'] ?? 0;
            $product->width = $productData['width'] ?? 0;
            $product->height = $productData['height'] ?? 0;
            $product->weight = $productData['weight'] ?? 0;
            $product->length = $productData['length'] ?? 0;
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
