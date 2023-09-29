<?php

namespace App\API;

class PackingResponse
{
    public function __construct(
        public readonly array $boxes,
        public readonly array $unpackedItems,
        public readonly bool $criticalError = false,
        public readonly array $errors = [],
    ){}

    public static function deserialize(array $responseData): self
    {
        return new self(
            $responseData['bins_packed'],
            $responseData['not_packed_items'],
            $responseData['status'] != 1,
            $responseData['errors'],
        );
    }

    public function getProductIds(): array
    {
        return array_map(fn (array $boxes) => $boxes['bin_data']['id'], $this->boxes);
    }

    public function getErrorTexts(): array
    {
        return array_map(fn (array $error) => $error['message'], $this->errors);
    }
}
