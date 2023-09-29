<?php

namespace App\Service;

use App\CLI\Box;
use App\CLI\ProductList;
use App\Exception\NoSuitableBoxException;

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
     * @return Box
     * @throws NoSuitableBoxException
     */
    public function getBox(ProductList $productList): Box
    {
        $package = $this->packagingService->getPackage($productList);
        if (!$package) {
            $package = $this->packagingGuesserService->getPackage($productList);
        }
        if (!$package) {
            throw new NoSuitableBoxException();
        }

        return Box::createFromPackage($package);
    }
}
