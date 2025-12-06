<?php

namespace Src\ThirdPartyServices\Vinted\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BagSearchQueryEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'bag_search_queries';

    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'brand',
        'ideal_price',
        'min_price',
        'max_price',
        'vinted_search_url',
        'max_pages',
        'page_param',
        'platform',
        'is_active',
        'last_scanned_at',
    ];

    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'brand' => 'string',
        'ideal_price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'vinted_search_url' => 'string',
        'max_pages' => 'integer',
        'page_param' => 'string',
        'platform' => 'string',
        'is_active' => 'boolean',
        'last_scanned_at' => 'datetime',
    ];

    /**
     * Build URL for a specific page number
     */
    public function getUrlForPage(int $page): string
    {
        $url = $this->vinted_search_url;
        $param = $this->page_param ?? 'page';

        // Remove existing page param if present
        $url = preg_replace('/([&?])' . preg_quote($param, '/') . '=\d+/', '$1', $url);
        $url = rtrim($url, '&?');

        // Add new page param
        $separator = str_contains($url, '?') ? '&' : '?';
        return $url . $separator . $param . '=' . $page;
    }

    public function listings(): HasMany
    {
        return $this->hasMany(VintedListingEloquentModel::class, 'bag_search_query_id');
    }

    public function interestingListings(): HasMany
    {
        return $this->hasMany(VintedListingEloquentModel::class, 'bag_search_query_id')
            ->where('is_interesting', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
