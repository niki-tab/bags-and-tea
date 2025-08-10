<?php

namespace Src\Order\Shipping\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Illuminate\Support\Collection;

interface ShippingRateRepository
{
    public function save(ShippingRate $shippingRate): void;

    public function searchAll(): Collection;

    public function searchByCriteria(Criteria $criteria): Collection;

    public function searchById(ShippingRateId $id): ?ShippingRate;

    public function searchByCountryCode(string $countryCode): Collection;

    public function delete(ShippingRateId $id): void;
}