<?php

namespace Src\ThirdPartyServices\Vinted\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class VintedListingEloquentModel extends Model
{
    use HasUuids;

    protected $table = 'vinted_listings';

    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'bag_search_query_id',
        'vinted_item_id',
        'title',
        'price',
        'currency',
        'url',
        'main_image_url',
        'images',
        'seller_name',
        'seller_rating',
        'condition',
        'description',
        'size',
        'brand_detected',
        'raw_data',
        'is_interesting',
        'is_verified_product',
        'verification_reason',
        'details_scraped',
        'published_at',
        'uploaded_text',
        'notification_sent',
        'scraped_at',
    ];

    protected $casts = [
        'id' => 'string',
        'bag_search_query_id' => 'string',
        'vinted_item_id' => 'string',
        'title' => 'string',
        'price' => 'decimal:2',
        'currency' => 'string',
        'url' => 'string',
        'main_image_url' => 'string',
        'images' => 'array',
        'seller_name' => 'string',
        'seller_rating' => 'decimal:2',
        'condition' => 'string',
        'description' => 'string',
        'size' => 'string',
        'brand_detected' => 'string',
        'raw_data' => 'array',
        'is_interesting' => 'boolean',
        'is_verified_product' => 'boolean',
        'verification_reason' => 'string',
        'details_scraped' => 'boolean',
        'published_at' => 'datetime',
        'uploaded_text' => 'string',
        'notification_sent' => 'boolean',
        'scraped_at' => 'datetime',
    ];

    public function searchQuery(): BelongsTo
    {
        return $this->belongsTo(BagSearchQueryEloquentModel::class, 'bag_search_query_id');
    }

    public function scopeInteresting($query)
    {
        return $query->where('is_interesting', true);
    }

    public function scopeNeedsDetailsScraping($query)
    {
        return $query->where('is_interesting', true)
            ->where('details_scraped', false);
    }

    public function scopeNotNotified($query)
    {
        return $query->where('notification_sent', false);
    }

    public function scopePendingNotification($query)
    {
        return $query->where('is_interesting', true)
            ->where('details_scraped', true)
            ->where('notification_sent', false);
    }
}
