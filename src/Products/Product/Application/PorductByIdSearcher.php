<?php

declare(strict_types=1);

namespace Src\Products\Product\Application;

use ChannelManager\Core\Bookings\Domain\BookingRepository;
use ChannelManager\Core\Bookings\Infrastructure\Eloquent\BookingEloquentModel;

final class ProductByIdSearcher
{
    public function __construct(private ProductRepository $productRepository) {}

    public function __invoke(
        string $id,
    ): ProductEloquentModel {

        $product = $this->productRepository->search($id);

        return $product;
    }
}
