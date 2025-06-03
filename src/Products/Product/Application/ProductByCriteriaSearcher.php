<?php

declare(strict_types=1);

namespace ChannelManager\Core\Bookings\Application\UseCases;

use ChannelManager\Shared\Domain\Criteria\Order;
use ChannelManager\Shared\Domain\Criteria\Filters;
use ChannelManager\Shared\Domain\Criteria\Criteria;
use ChannelManager\Core\Bookings\Domain\BookingRepository;

final class BookingsByCriteriaSearcher
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

        $reservations = $this->productRepository->searchByCriteria($criteria);
        return $reservations;
    }
}
