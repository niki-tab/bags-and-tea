<?php

declare(strict_types=1);

namespace Src\Products\Certificates\Infrastructure;

use Src\Products\Certificates\Domain\CertificateRepository;
use Src\Products\Certificates\Infrastructure\Eloquent\CertificateEloquentModel;

final class EloquentCertificateRepository implements CertificateRepository
{
    public function save(CertificateEloquentModel $certificate): void
    {
        $certificate->save();
    }

    public function findByCertificateNumber(string $certificateNumber): ?CertificateEloquentModel
    {
        return CertificateEloquentModel::where('certificate_number', $certificateNumber)->first();
    }

    public function findByOrderItem(string $orderItemId): array
    {
        return CertificateEloquentModel::where('order_item_id', $orderItemId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->all();
    }

    public function generateCertificateNumber(): string
    {
        return CertificateEloquentModel::generateCertificateNumber();
    }
}
