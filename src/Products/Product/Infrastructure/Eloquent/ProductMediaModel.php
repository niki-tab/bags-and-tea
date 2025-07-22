<?php

namespace Src\Products\Product\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductMediaModel extends Model
{
    protected $table = 'product_media';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'product_id',
        'file_path',
        'file_name',
        'file_type',
        'mime_type',
        'file_size',
        'sort_order',
        'is_primary',
        'alt_text',
    ];

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'file_size' => 'integer',
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductEloquentModel::class, 'product_id');
    }

    public function getFullUrlAttribute(): string
    {
        // Check if it's an R2 URL (full URL) or local storage path
        if (str_starts_with($this->file_path, 'https://') || str_contains($this->file_path, 'r2.cloudflarestorage.com')) {
            return $this->file_path; // Use R2 URL directly
        } else {
            return asset($this->file_path); // Use asset() for local storage
        }
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function scopeImages($query)
    {
        return $query->where('file_type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('file_type', 'video');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}