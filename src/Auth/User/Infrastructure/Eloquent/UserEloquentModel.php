<?php

namespace App\Auth\User\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Auth\Role\Infrastructure\Eloquent\RoleEloquentModel;
use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserEloquentModel extends Authenticatable
{
    use HasUuids, Notifiable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['password', 'remember_token'])
            ->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}")
            ->useLogName('admin-users');
    }

    protected $table = 'users';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            RoleEloquentModel::class,
            'user_roles',
            'user_id',
            'role_id'
        )->withTimestamps();
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(VendorEloquentModel::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isBuyer(): bool
    {
        return $this->hasRole('buyer');
    }

    public function isVendor(): bool
    {
        return $this->hasRole('vendor');
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
}