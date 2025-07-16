<?php

declare(strict_types=1);

namespace Src\Products\Product\Application;

use Src\Shared\Domain\Criteria\Order;
use Src\Shared\Domain\Criteria\Filters;
use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Product\Domain\ProductRepository;

final class ProductByCriteriaSearcher
{
    public function __construct(private ProductRepository $productRepository) {}

    public function __invoke(
        array $filters,
        ?string $orderBy = null,
        ?string $order = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $filters = Filters::fromValues($filters);
        $order = Order::fromValues($orderBy, $order);
        $criteria = new Criteria($filters, $order, $offset, $limit);

        $products = $this->productRepository->searchByCriteria($criteria);
        return $products;
    }
}
