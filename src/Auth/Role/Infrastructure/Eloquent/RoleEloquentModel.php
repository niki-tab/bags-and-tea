<?php

namespace App\Auth\Role\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;

class RoleEloquentModel extends Model
{
    use HasUuids;

    const ADMIN = 'admin';
    const BUYER = 'buyer';
    const VENDOR = 'vendor';

    protected $table = 'roles';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'display_name',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            UserEloquentModel::class,
            'user_roles',
            'role_id',
            'user_id'
        )->withTimestamps();
    }
}