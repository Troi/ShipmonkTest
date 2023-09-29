<?php

namespace App\Service;

use App\CLI\ProductList;
use App\Entity\Packaging;
use App\Exception\NoSuitableBoxException;

/**
 * Guess most suitable package without external API help
 *
 * INTENTION: fast, but don't get best solution
 */
class PackagingGuesserService
{
    /**
     * @param ProductList $productList
     * @return Packaging|null
     */
    public function getPackage(ProductList $productList): ?Packaging
    {
        return null;
    }
}
