<?php

declare(strict_types=1);

namespace Src\Products\Product\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Product\Domain\ProductRepository;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

final class EloquentProductRepository implements ProductRepository
{
    public function save(ProductEloquentModel $product): void
    {
        $product->save();
    }
    public function search(string $id): ?ProductEloquentModel
    {
        return ProductEloquentModel::find($id);
    }
    public function searchByCriteria(Criteria $criteria): array
    {
        $query = ProductEloquentModel::query();
        
        foreach ($criteria->plainFilters() as $filter) {
            $field = $filter->field()->value();
            $operator = $filter->operator()->value();
            $value = $filter->value()->value();

            // Handle special cases for relationships
            if ($field === 'categories') {
                $query->whereHas('categories', function ($q) use ($operator, $value) {
                    if ($operator === 'in') {
                        // Handle comma-separated string values from FilterValue
                        $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                        $q->whereIn('categories.id', $values);
                    } else {
                        $q->where('categories.id', $operator, $value);
                    }
                });
                continue;
            }

            if ($field === 'attributes') {
                $query->whereHas('attributes', function ($q) use ($operator, $value) {
                    if ($operator === 'in') {
                        // Handle comma-separated string values from FilterValue
                        $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                        $q->whereIn('attributes.id', $values);
                    } else {
                        $q->where('attributes.id', $operator, $value);
                    }
                });
                continue;
            }

            // Handle special price ranges filter
            if ($field === 'price_ranges') {
                $priceRanges = explode(',', $value);
                $query->where(function ($q) use ($priceRanges) {
                    foreach ($priceRanges as $priceRange) {
                        $q->orWhere(function ($subQ) use ($priceRange) {
                            switch (trim($priceRange)) {
                                case '0-100':
                                    $subQ->whereBetween('price', [0, 100]);
                                    break;
                                case '100-500':
                                    $subQ->whereBetween('price', [100, 500]);
                                    break;
                                case '500-1000':
                                    $subQ->whereBetween('price', [500, 1000]);
                                    break;
                                case '1000-2000':
                                    $subQ->whereBetween('price', [1000, 2000]);
                                    break;
                                case '2000+':
                                    $subQ->where('price', '>=', 2000);
                                    break;
                            }
                        });
                    }
                });
                continue;
            }

            // Handle standard filters
            if ($operator === 'like') {
                $value = '%' . $value . '%';
            }

            if ($operator === 'in') {
                // Handle comma-separated string values from FilterValue
                $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                $query->whereIn($field, $values);
            } else {
                $query->where($field, $operator, $value);
            }
        }

        // Always include relationships for shop display
        $query->with(['brand', 'vendor', 'quality', 'categories', 'attributes']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                $query->orderBy($orderBy->value(), $orderType->value());
            }
        }

        $limit = $criteria->limit();
        if ($limit === 0 || !isset($limit)) {
            $limit = 50;
        }
        
        $results = $query->offset($criteria->offset() ?? 0)
            ->limit($limit)
            ->get()
            ->all();
        
        return $results;
    }

    public function addCategory(string $productId, string $categoryId): void
    {
        $product = ProductEloquentModel::findOrFail($productId);
        $product->categories()->attach($categoryId);
    }

    public function addAttribute(string $productId, string $attributeId): void
    {
        $product = ProductEloquentModel::findOrFail($productId);
        $product->attributes()->attach($attributeId);
    }

    public function searchByCriteriaForVendor(string $vendorId, Criteria $criteria): array
    {
        $query = ProductEloquentModel::query();
        
        // Filter by vendor first
        $query->where('vendor_id', $vendorId);
        
        foreach ($criteria->plainFilters() as $filter) {
            $field = $filter->field()->value();
            $operator = $filter->operator()->value();
            $value = $filter->value()->value();

            // Handle special cases for relationships
            if ($field === 'categories') {
                $query->whereHas('categories', function ($q) use ($operator, $value) {
                    if ($operator === 'in') {
                        $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                        $q->whereIn('categories.id', $values);
                    } else {
                        $q->where('categories.id', $operator, $value);
                    }
                });
                continue;
            }

            if ($field === 'attributes') {
                $query->whereHas('attributes', function ($q) use ($operator, $value) {
                    if ($operator === 'in') {
                        $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                        $q->whereIn('attributes.id', $values);
                    } else {
                        $q->where('attributes.id', $operator, $value);
                    }
                });
                continue;
            }

            // Handle standard filters
            if ($operator === 'like') {
                $value = '%' . $value . '%';
            }

            if ($operator === 'in') {
                $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                $query->whereIn($field, $values);
            } else {
                $query->where($field, $operator, $value);
            }
        }

        // Always include relationships
        $query->with(['brand', 'vendor.user', 'quality', 'categories', 'attributes']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                $query->orderBy($orderBy->value(), $orderType->value());
            }
        }

        $limit = $criteria->limit();
        if ($limit === 0 || !isset($limit)) {
            $limit = 50;
        }
        
        $results = $query->offset($criteria->offset() ?? 0)
            ->limit($limit)
            ->get()
            ->all();
        
        return $results;
    }

    public function findByVendor(string $vendorId): array
    {
        return ProductEloquentModel::where('vendor_id', $vendorId)
            ->with(['brand', 'vendor.user', 'quality', 'categories', 'attributes'])
            ->get()
            ->all();
    }

    public function searchByCriteriaForUser(string $userId, Criteria $criteria): array
    {
        $query = ProductEloquentModel::query();
        
        // Filter by user's vendor
        $query->whereHas('vendor', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
        
        foreach ($criteria->plainFilters() as $filter) {
            $field = $filter->field()->value();
            $operator = $filter->operator()->value();
            $value = $filter->value()->value();

            // Handle special cases for relationships
            if ($field === 'categories') {
                $query->whereHas('categories', function ($q) use ($operator, $value) {
                    if ($operator === 'in') {
                        $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                        $q->whereIn('categories.id', $values);
                    } else {
                        $q->where('categories.id', $operator, $value);
                    }
                });
                continue;
            }

            if ($field === 'attributes') {
                $query->whereHas('attributes', function ($q) use ($operator, $value) {
                    if ($operator === 'in') {
                        $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                        $q->whereIn('attributes.id', $values);
                    } else {
                        $q->where('attributes.id', $operator, $value);
                    }
                });
                continue;
            }

            // Handle standard filters
            if ($operator === 'like') {
                $value = '%' . $value . '%';
            }

            if ($operator === 'in') {
                $values = is_string($value) ? explode(',', $value) : (is_array($value) ? $value : [$value]);
                $query->whereIn($field, $values);
            } else {
                $query->where($field, $operator, $value);
            }
        }

        // Always include relationships
        $query->with(['brand', 'vendor.user', 'quality', 'categories', 'attributes']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                $query->orderBy($orderBy->value(), $orderType->value());
            }
        }

        $limit = $criteria->limit();
        if ($limit === 0 || !isset($limit)) {
            $limit = 50;
        }
        
        $results = $query->offset($criteria->offset() ?? 0)
            ->limit($limit)
            ->get()
            ->all();
        
        return $results;
    }
}
