<?php

namespace Src\Order\Fees\Domain;

interface MarketplaceFeeRepository
{
    public function findActiveVisibleFees(): array;
    
    public function findByCode(string $feeCode): ?array;
}