<?php

declare(strict_types=1);

namespace Src\Vendors\Infrastructure;

use Src\Vendors\Domain\VendorRepository;
use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;

final class EloquentVendorRepository implements VendorRepository
{
    public function save(VendorEloquentModel $vendor): void
    {
        $vendor->save();
    }

    public function search(string $id): ?VendorEloquentModel
    {
        return VendorEloquentModel::find($id);
    }

    public function findByUserId(string $userId): ?VendorEloquentModel
    {
        return VendorEloquentModel::where('user_id', $userId)->first();
    }

    public function findActive(): array
    {
        return VendorEloquentModel::active()
            ->with(['user'])
            ->get()
            ->all();
    }

    public function findAll(): array
    {
        return VendorEloquentModel::with(['user'])
            ->get()
            ->all();
    }

    public function delete(string $id): void
    {
        VendorEloquentModel::findOrFail($id)->delete();
    }
}