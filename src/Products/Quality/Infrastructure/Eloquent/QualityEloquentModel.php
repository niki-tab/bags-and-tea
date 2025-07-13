<?php

namespace Src\Products\Quality\Infrastructure\Eloquent;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QualityEloquentModel extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'qualities';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'code',
        'display_order'
    ];


    // Define the cast attributes
    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'code' => 'string',
        'display_order' => 'integer',

    ];

    public $translatable = ['name'];

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
        return \Database\Factories\QualityEloquentModelFactory::new();
    }
}
