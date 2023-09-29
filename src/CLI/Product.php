<?php
declare(strict_types=1);

namespace App\CLI;

use Symfony\Component\Validator\Constraints as Assert;

class Product
{
    #[Assert\GreaterThan(0)]
    public int $id;
    #[Assert\GreaterThan(0)]
    public float $width = 0;
    #[Assert\GreaterThan(0)]
    public float $height = 0;
    #[Assert\GreaterThan(0)]
    public float $length = 0;
    #[Assert\GreaterThan(0)]
    public float $weight = 0;
}
