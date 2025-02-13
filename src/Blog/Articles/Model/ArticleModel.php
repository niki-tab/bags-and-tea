<?php
namespace Src\Blog\Articles\Model;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleModel extends Model
{
    use HasFactory, HasTranslations;
    
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
        'main_image' => 'string',
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
        'main_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_visible',
        'created_at',
        'updated_at',
    ];

    public $translatable = ['title', 'slug', 'body', 'meta_title', 'meta_description', 'meta_keywords'];

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

    public function getTranslatedAttributes(string $locale = 'en'): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale),
            'slug' => $this->getTranslation('slug', $locale),
            'body' => $this->getTranslation('body', $locale),
            'state' => $this->state,
            'meta_title' => $this->getTranslation('meta_title', $locale),
            'meta_description' => $this->getTranslation('meta_description', $locale),
            'meta_keywords' => $this->getTranslation('meta_keywords', $locale),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    
}