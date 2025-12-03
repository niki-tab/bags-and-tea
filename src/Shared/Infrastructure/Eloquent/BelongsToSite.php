<?php

namespace Src\Shared\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Sites\Infrastructure\Eloquent\SiteEloquentModel;

trait BelongsToSite
{
    /**
     * Boot the BelongsToSite trait for a model.
     */
    protected static function bootBelongsToSite(): void
    {
        // Add global scope for automatic site_id filtering
        static::addGlobalScope('site', function ($query) {
            // Skip if running in console (seeders, commands) or if no current site
            if (app()->runningInConsole() && !app()->runningUnitTests()) {
                return;
            }

            // Check if current_site exists in container
            if (!app()->has('current_site')) {
                return;
            }

            $currentSite = app('current_site');

            if (!$currentSite) {
                return;
            }

            // Special rule: NULL site_id belongs to bagsandtea (legacy data)
            // New records always have explicit site_id, but we include NULL for backwards compatibility
            if ($currentSite->slug === 'bagsandtea') {
                $query->where(function ($q) use ($currentSite) {
                    $q->where('site_id', $currentSite->id)
                      ->orWhereNull('site_id');
                });
            } else {
                // Other sites: only explicit site_id
                $query->where('site_id', $currentSite->id);
            }
        });

        // Automatically set site_id when creating new records
        static::creating(function ($model) {
            if (!$model->site_id && app()->has('current_site')) {
                $currentSite = app('current_site');

                // Set explicit site_id for ALL new records (including bagsandtea)
                // NULL site_id should only exist for legacy data
                if ($currentSite) {
                    $model->site_id = $currentSite->id;
                }
            }
        });
    }

    /**
     * Get the site that owns the model.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(SiteEloquentModel::class, 'site_id');
    }

    /**
     * Scope a query to a specific site.
     */
    public function scopeForSite($query, ?string $siteId = null)
    {
        if ($siteId === null) {
            return $query;
        }

        $site = SiteEloquentModel::find($siteId);

        if (!$site) {
            return $query;
        }

        // Apply same logic: NULL belongs to bagsandtea (legacy data)
        if ($site->slug === 'bagsandtea') {
            return $query->where(function ($q) use ($siteId) {
                $q->where('site_id', $siteId)
                  ->orWhereNull('site_id');
            });
        }

        return $query->where('site_id', $siteId);
    }
}
