<?php
declare(strict_types=1);

namespace App\CLI;

use Symfony\Component\Validator\Constraints as Assert;

class Product
{
    public function __construct(
        #[Assert\GreaterThan(0)]
        public readonly int $id,
        #[Assert\GreaterThan(0)]
        public readonly float $width,
        #[Assert\GreaterThan(0)]
        public readonly float $height,
        #[Assert\GreaterThan(0)]
        public readonly float $length,
        #[Assert\GreaterThan(0)]
        public readonly float $weight
    ){}

}
