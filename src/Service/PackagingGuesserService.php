<?php

namespace App\Service;

use App\CLI\Product;
use App\CLI\ProductList;
use App\Entity\Packaging;
use App\Exception\NoSuitableBoxException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Guess most suitable package without external API help
 *
 * INTENTION: fast, but don't get best solution
 */
class PackagingGuesserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ){}

    /**
     * @param ProductList $productList
     * @return Packaging|null
     */
    public function getPackage(ProductList $productList): ?Packaging
    {
        // FIXME: can return too small box
        $biggestPseudoProduct = new Product();

        foreach ($productList->products as $product) {
            $biggestPseudoProduct->width = max($biggestPseudoProduct->width, $product->width);
            $biggestPseudoProduct->length = max($biggestPseudoProduct->length, $product->length);
            $biggestPseudoProduct->height = max($biggestPseudoProduct->height, $product->height);
            $biggestPseudoProduct->weight += $product->weight;
        }

        $query = $this->entityManager->createQueryBuilder();
        $query->select('p')
            ->from(Packaging::class, 'p')
            ->andWhere($query->expr()->gt('p.width', $biggestPseudoProduct->width))
            ->andWhere($query->expr()->gt('p.length', $biggestPseudoProduct->length))
            ->andWhere($query->expr()->gt('p.height', $biggestPseudoProduct->height))
            ->andWhere($query->expr()->gt('p.maxWeight', $biggestPseudoProduct->weight))
            ->orderBy('p.maxWeight', 'asc')
            ->orderBy('p.width', 'asc')
            ->orderBy('p.height', 'asc')
            ->setMaxResults(1)
            ;
        return $query->getQuery()->getSingleResult();
    }
}
