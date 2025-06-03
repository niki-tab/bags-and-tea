<?php

declare(strict_types=1);

namespace Src\Products\Product\Application;

use Ramsey\Uuid\Uuid;
use ChannelManager\Core\Bookings\Domain\BookingRepository;
use ChannelManager\Core\Travelers\Domain\TravelerRepository;
use ChannelManager\Core\TourOptions\Domain\TourOptionRepository;
use ChannelManager\Core\BookingItems\Domain\BookingItemRepository;
use ChannelManager\Core\Reservations\Domain\ReservationRepository;
use ChannelManager\Core\Bookings\Domain\ValueObjects\BookingStatus;
use ChannelManager\Core\TourOptionSlots\Domain\TourOptionSlotRepository;
use ChannelManager\Core\TourOptions\Domain\ValueObjects\TourOptionStatus;
use ChannelManager\Core\BookingTravelers\Domain\BookingTravelerRepository;
use ChannelManager\Core\Bookings\Infrastructure\Eloquent\BookingEloquentModel;
use ChannelManager\Core\Travelers\Infrastructure\Eloquent\TravelerEloquentModel;
use ChannelManager\Core\BookingItems\Infrastructure\Eloquent\BookingItemEloquentModel;
use ChannelManager\Core\BookingTravelers\Infrastructure\Eloquent\BookingTravelerEloquentModel;
use ChannelManager\Core\Reservations\Domain\ValueObjects\ReservationStatus;

final class ProductCreator
{
    public function __construct(
        private ProductRepository $productRepository,

    ) {}
    public function __invoke(
        string $id,
        string $name,
        string $brand,
        string $slug,
        string $description_1,
        string $description_2,
        string $origin_general,
        string $origin_specific,
        string $quality,
        string $product_type,
        string $species_type,
        string $food_type,
        string $price_from,
        string $sell_unit,
        string $sell_mode,
        string $stock,
        string $stock_unit,
        string $out_of_stock,
        string $image,
        string $featured,
        string $featured_position,
        string $meta_title,
        string $meta_description,
    ): void {
        
        $product = new ProductEloquentModel(
            [
                'id' => $id,
                'name' => $name,
                'brand' => $brand,
                'slug' => $slug,
                'description_1' => $description_1,
                'description_2' => $description_2,
                'origin_general' => $origin_general,
                'origin_specific' => $origin_specific,
                'quality' => $quality,
                'product_type' => $product_type,
                'species_type' => $species_type,
                'food_type' => $food_type,
                'price_from' => $price_from,
                'sell_unit' => $sell_unit,
                'sell_mode' => $sell_mode,
                'stock' => $stock,
                'stock_unit' => $stock_unit,
                'out_of_stock' => $out_of_stock,
                'image' => $image,
                'featured' => $featured,
                'featured_position' => $featured_position,
                'meta_title' => $meta_title,
                'meta_description' => $meta_description,

            ]
        );

        $this->productRepository->save($product);
    }
}
