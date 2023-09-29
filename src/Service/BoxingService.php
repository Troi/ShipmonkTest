<?php

namespace App\Service;

use App\CLI\Box;
use App\CLI\ProductList;
use App\Exception\NoSuitablePackageException;

/**
 * Get box for products
 * - cached
 * - fallback inaccessible API
 */
class BoxingService
{
    public function __construct(
        private readonly PackagingService $packagingService,
        private readonly PackagingGuesserService $packagingGuesserService,
    )
    {
    }

    /**
     * @param ProductList $productList
     * @throws NoSuitablePackageException
     * @return Box
     */
    public function getBox(ProductList $productList): Box
    {
        throw new NoSuitablePackageException();
    }
}
