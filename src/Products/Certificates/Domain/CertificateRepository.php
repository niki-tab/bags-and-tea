<?php

declare(strict_types=1);

namespace Src\Products\Certificates\Domain;

use Src\Products\Certificates\Infrastructure\Eloquent\CertificateEloquentModel;

interface CertificateRepository
{
    public function save(CertificateEloquentModel $certificate): void;

    public function findByCertificateNumber(string $certificateNumber): ?CertificateEloquentModel;

    public function findByOrderItem(string $orderItemId): array;

    public function generateCertificateNumber(): string;
}
