<?php
declare(strict_types=1);

namespace App\Service;

use App\API\BinPackageAPI;
use App\API\RequestDataBuilder;
use App\CLI\ProductList;
use App\Entity\Packaging;
use App\Exception\NoSuitableBoxException;
use App\Exception\WronglyPreparedAPICallException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Finds Package for Products
 */
class RemotePackagingService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BinPackageAPI          $packageRequestAPI,
        private array                           $binPackageConfigureation = []
    )
    {
    }

    /**
     * @param ProductList $productList
     * @return Packaging|null
     */
    public function getPackage(ProductList $productList): ?Packaging
    {
        $packagingRepository = $this->entityManager->getRepository(Packaging::class);
        $requestBuilder = new RequestDataBuilder();
        // TODO: copy information from configuration to builder
        $requestBuilder->setAuth('asdf', 'qwer');

        foreach ($packagingRepository->findAll() as $package) {
            $requestBuilder->addBox($package);
        }

        foreach ($productList->products as $product) {
            $requestBuilder->addProduct($product);
        }

        try {
            $id = $this->packageRequestAPI->callApi($requestBuilder);

            return $packagingRepository->find($id);
        } catch (WronglyPreparedAPICallException $e) {
            // TODO: log error
            return null;
        }
    }
}
