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

        // Apply filters step by step to ensure proper AND logic
        foreach ($criteria->plainFilters() as $filter) {
            $field = $filter->field()->value();
            $operator = $filter->operator()->value();
            $value = $filter->value()->value();


            // Handle special cases for relationships
            if ($field === 'categories' || strpos($field, 'categories_') === 0) {
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

            if ($field === 'attributes' || strpos($field, 'attributes_') === 0) {
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

            // Handle search functionality
            if ($field === 'search') {
                $locale = app()->getLocale();
                $query->where(function ($q) use ($value, $locale) {
                    // Search in product name (JSON translatable field)
                    $q->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      // Search in SKU
                      ->orWhere('sku', 'LIKE', '%' . $value . '%')
                      // Search in description fields (JSON translatable)
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_1, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_2, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      // Search in brand name (through relationship)
                      ->orWhereHas('brand', function ($brandQuery) use ($value, $locale) {
                          $brandQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // Search in category names (through relationship)
                      ->orWhereHas('categories', function ($categoryQuery) use ($value, $locale) {
                          $categoryQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // Search in attribute names (through relationship)
                      ->orWhereHas('attributes', function ($attributeQuery) use ($value, $locale) {
                          $attributeQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // SOUNDEX for typo tolerance on product name
                      ->orWhereRaw("SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}'))) = SOUNDEX(?)", [$value]);
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
        $query->with(['brand', 'vendor', 'quality', 'categories', 'attributes', 'primaryImage', 'media']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                if ($orderBy->value() === 'stock_status_and_created') {
                    // Custom ordering: in-stock products first, then by newest created
                    $query->orderByRaw('is_sold_out ASC, created_at DESC');
                } else {
                    $query->orderBy($orderBy->value(), $orderType->value());
                }
            }
        }

        $limit = $criteria->limit();
        if ($limit === 0 || !isset($limit)) {
            $limit = 50;
        }

        // Log the final SQL query
        \Log::info('Final SQL query: ' . $query->toSql());
        \Log::info('Query bindings:', $query->getBindings());

        $results = $query->offset($criteria->offset() ?? 0)
            ->limit($limit)
            ->get()
            ->all();


        return $results;
    }

    public function searchByCriteriaPaginated(Criteria $criteria, int $perPage = 20)
    {
        $query = ProductEloquentModel::query();

        // Apply filters step by step to ensure proper AND logic
        foreach ($criteria->plainFilters() as $filter) {
            $field = $filter->field()->value();
            $operator = $filter->operator()->value();
            $value = $filter->value()->value();

            // Handle special cases for relationships
            if ($field === 'categories' || strpos($field, 'categories_') === 0) {
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

            if ($field === 'attributes' || strpos($field, 'attributes_') === 0) {
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

            // Handle search functionality
            if ($field === 'search') {
                $locale = app()->getLocale();
                $query->where(function ($q) use ($value, $locale) {
                    $q->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhere('sku', 'LIKE', '%' . $value . '%')
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_1, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_2, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhereHas('brand', function ($brandQuery) use ($value, $locale) {
                          $brandQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      ->orWhereHas('categories', function ($categoryQuery) use ($value, $locale) {
                          $categoryQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      ->orWhereHas('attributes', function ($attributeQuery) use ($value, $locale) {
                          $attributeQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      ->orWhereRaw("SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}'))) = SOUNDEX(?)", [$value]);
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

        // Always include relationships for shop display
        $query->with(['brand', 'vendor', 'quality', 'categories', 'attributes', 'primaryImage', 'media']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                if ($orderBy->value() === 'stock_status_and_created') {
                    $query->orderByRaw('is_sold_out ASC, created_at DESC');
                } else {
                    $query->orderBy($orderBy->value(), $orderType->value());
                }
            }
        }

        return $query->paginate($perPage);
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

            // Handle search functionality
            if ($field === 'search') {
                $locale = app()->getLocale();
                $query->where(function ($q) use ($value, $locale) {
                    // Search in product name (JSON translatable field)
                    $q->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      // Search in SKU
                      ->orWhere('sku', 'LIKE', '%' . $value . '%')
                      // Search in description fields (JSON translatable)
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_1, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_2, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      // Search in brand name (through relationship)
                      ->orWhereHas('brand', function ($brandQuery) use ($value, $locale) {
                          $brandQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // Search in category names (through relationship)
                      ->orWhereHas('categories', function ($categoryQuery) use ($value, $locale) {
                          $categoryQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // Search in attribute names (through relationship)
                      ->orWhereHas('attributes', function ($attributeQuery) use ($value, $locale) {
                          $attributeQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // SOUNDEX for typo tolerance on product name
                      ->orWhereRaw("SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}'))) = SOUNDEX(?)", [$value]);
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
        $query->with(['brand', 'vendor.user', 'quality', 'categories', 'attributes', 'primaryImage', 'media']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                if ($orderBy->value() === 'stock_status_and_created') {
                    // Custom ordering: in-stock products first, then by newest created
                    $query->orderByRaw('is_sold_out ASC, created_at DESC');
                } else {
                    $query->orderBy($orderBy->value(), $orderType->value());
                }
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
            ->with(['brand', 'vendor.user', 'quality', 'categories', 'attributes', 'primaryImage', 'media'])
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

            // Handle search functionality
            if ($field === 'search') {
                $locale = app()->getLocale();
                $query->where(function ($q) use ($value, $locale) {
                    // Search in product name (JSON translatable field)
                    $q->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      // Search in SKU
                      ->orWhere('sku', 'LIKE', '%' . $value . '%')
                      // Search in description fields (JSON translatable)
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_1, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_2, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      // Search in brand name (through relationship)
                      ->orWhereHas('brand', function ($brandQuery) use ($value, $locale) {
                          $brandQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // Search in category names (through relationship)
                      ->orWhereHas('categories', function ($categoryQuery) use ($value, $locale) {
                          $categoryQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // Search in attribute names (through relationship)
                      ->orWhereHas('attributes', function ($attributeQuery) use ($value, $locale) {
                          $attributeQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      // SOUNDEX for typo tolerance on product name
                      ->orWhereRaw("SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}'))) = SOUNDEX(?)", [$value]);
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
        $query->with(['brand', 'vendor.user', 'quality', 'categories', 'attributes', 'primaryImage', 'media']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                if ($orderBy->value() === 'stock_status_and_created') {
                    // Custom ordering: in-stock products first, then by newest created
                    $query->orderByRaw('is_sold_out ASC, created_at DESC');
                } else {
                    $query->orderBy($orderBy->value(), $orderType->value());
                }
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

    public function searchByCriteriaForUserPaginated(string $userId, Criteria $criteria, int $perPage = 20)
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

            // Handle search functionality
            if ($field === 'search') {
                $locale = app()->getLocale();
                $query->where(function ($q) use ($value, $locale) {
                    $q->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhere('sku', 'LIKE', '%' . $value . '%')
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_1, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_2, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%'])
                      ->orWhereHas('brand', function ($brandQuery) use ($value, $locale) {
                          $brandQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      ->orWhereHas('categories', function ($categoryQuery) use ($value, $locale) {
                          $categoryQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      ->orWhereHas('attributes', function ($attributeQuery) use ($value, $locale) {
                          $attributeQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $value . '%']);
                      })
                      ->orWhereRaw("SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}'))) = SOUNDEX(?)", [$value]);
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
        $query->with(['brand', 'vendor.user', 'quality', 'categories', 'attributes', 'primaryImage', 'media']);

        // Apply sorting if specified
        $order = $criteria->order();
        if ($order && !$order->isNone()) {
            $orderBy = $order->orderBy();
            $orderType = $order->orderType();
            if ($orderBy && $orderType) {
                if ($orderBy->value() === 'stock_status_and_created') {
                    $query->orderByRaw('is_sold_out ASC, created_at DESC');
                } else {
                    $query->orderBy($orderBy->value(), $orderType->value());
                }
            }
        }

        return $query->paginate($perPage);
    }
}
