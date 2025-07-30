<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class FormSubmissionEloquentModel extends Model
{
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
        'id',
        'crm_form_id',
        'crm_form_answers'
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

    public function form(): BelongsTo
    {
        return $this->belongsTo(FormEloquentModel::class, 'crm_form_id');
    }
}