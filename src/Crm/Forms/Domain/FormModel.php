<?php

namespace Src\Crm\Forms\Domain;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormModel extends Model
{
    use HasFactory;

    protected $table = 'crm_forms';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $casts = [
        'id' => 'string',
        'form_name' => 'string',
        'form_identifier' => 'string',
        'form_description' => 'string',
        'form_fields' => 'json',
        'is_active' => 'boolean'
    ];
    protected $fillable = [
        "id",
        "form_name",
        "form_identifier",
        "form_description",
        "form_fields",
        "is_active"
    ];

    protected static function newFactory()
    {
        return \Database\Factories\FormFactory::new();
    }
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

}
