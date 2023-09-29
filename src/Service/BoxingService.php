<?php

namespace App\Service;

use App\CLI\Box;
use App\CLI\ProductList;
use App\Exception\NoSuitableBoxException;
use App\Exception\WronglyPreparedAPICallException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Get box for products
 * - cached
 * - fallback inaccessible API
 */
class BoxingService
{
    public function __construct(
        private readonly RemotePackagingService  $packagingService,
        private readonly PackagingGuesserService $packagingGuesserService,
        private readonly ?CacheInterface         $packageCache = null,
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
        if (!$this->packageCache) {
            return $this->findBox($productList);
        }

        return $this->packageCache->get($productList->getCacheKey(), function (ItemInterface $boxCacheItem) use ($productList): Box {
            $boxCacheItem->expiresAfter(2);

            return $this->findBox($productList);
        });
    }

    /**
     * @param ProductList $productList
     * @return Box
     */
    private function findBox(ProductList $productList): Box
    {
        try {
            $package = $this->packagingService->getPackage($productList);
        } catch (WronglyPreparedAPICallException $e) {
            // TODO: log error and guess instead
            $package = null;
        }
        if (!$package) {
            $package = $this->packagingGuesserService->getPackage($productList);
        }
        if (!$package) {
            throw new NoSuitableBoxException();
        }

        return Box::createFromPackage($package);
    }
}
