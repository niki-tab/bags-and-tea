<?php

declare(strict_types=1);

namespace Src\Products\Product\Application;

use Src\Products\Product\Domain\ProductRepository;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

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
