<?php

namespace Src\Crm\Forms\Domain;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSubmissionModel extends Model
{
    use HasFactory;

    protected $table = 'crm_form_submissions';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $casts = [
        'id' => 'string',
        'crm_form_id' => 'string',
        'crm_form_answers' => 'json'
    ];
    protected $fillable = [
        "id",
        "crm_form_id",
        "crm_form_answers"
    ];

    protected static function newFactory()
    {
        return \Database\Factories\FormSubmissionFactory::new();
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
