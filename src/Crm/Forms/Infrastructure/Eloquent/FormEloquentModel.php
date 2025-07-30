<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class FormEloquentModel extends Model
{
    protected $table = 'crm_forms';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    
    protected $casts = [
        'id' => 'string',
        'form_fields' => 'json',
        'is_active' => 'boolean'
    ];
    
    protected $fillable = [
        'id',
        'form_identifier',
        'form_name',
        'form_description',
        'form_fields',
        'is_active'
    ];

    protected static function boot()
    {
        parent::boot();
  
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
            }
        });
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmissionEloquentModel::class, 'crm_form_id');
    }
}