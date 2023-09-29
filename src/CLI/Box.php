<?php

namespace App\CLI;

use App\Entity\Packaging;

class Box implements \JsonSerializable
{
    public int $id;
    public float $width;
    public float $height;
    public float $length;

    public static function createFromPackage(Packaging $packaging): self
    {
        $box = new self();
        $box->id = $packaging->getId();
        $box->width = $packaging->getWidth();
        $box->height = $packaging->getHeight();
        $box->length = $packaging->getLength();

        return $box;
    }

    public function jsonSerialize(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'length' => $this->length,
        ];
    }
}
