<?php
declare(strict_types=1);

namespace App\Service;

use App\CLI\ProductList;
use App\Entity\Packaging;
use App\Exception\NoSuitableBoxException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Finds Package for Products
 */
class PackagingService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @param ProductList $productList
     * @return Packaging|null
     */
    public function getPackage(ProductList $productList): ?Packaging
    {
        return null;
    }
}
