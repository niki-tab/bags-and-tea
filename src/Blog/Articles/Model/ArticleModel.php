<?php
namespace Src\Blog\Articles\Model;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleModel extends Model
{
    use HasFactory;
    protected $table = 'blog_articles';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'slug' => 'string',
        'state' => 'string',
        'body' => 'string',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'meta_keywords' => 'string',
        'is_visible' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];


    protected $fillable = [
        'id' ,
        'title',
        'slug',
        'state',
        'body',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_visible',
        'created_at',
        'updated_at',
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
        return \Database\Factories\ArticleModelFactory::new();
    }
}