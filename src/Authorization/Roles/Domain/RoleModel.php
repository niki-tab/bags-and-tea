<?php
namespace Src\Authorization\Roles\Domain;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Src\Authorization\Users\Domain\UserModel;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Src\Metadata\Infrastructure\Eloquent\MetadataModel;

class RoleModel extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $casts = [
        'id' => 'string',
    ];
    protected $fillable = [
        "id",
        "name",
        "display_name",
    ];

    protected static function boot()
    {
        parent::boot();
  
        static::creating(function ($model) {
            // Set the UUIDs if not already set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
            }
  
        });
    }

    protected static function newFactory()
    {
        return \Database\Factories\RoleModelFactory::new();
    }
    
}