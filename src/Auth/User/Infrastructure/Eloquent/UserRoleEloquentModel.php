<?php
namespace Src\Auth\User\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Src\Metadata\Infrastructure\Eloquent\MetadataModel;
use App\Auth\Role\Infrastructure\Eloquent\RoleEloquentModel;

class UserRoleEloquentModel extends Model
{
    use HasFactory;
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'role_id' => 'string',
    ];
    protected $fillable = [
        "id",
        "user_id",
        "role_id",
        "entity_type",
        "entity_id",
    ];
    
}