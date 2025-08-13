<?php

namespace Src\Blog\Articles\Frontend;

use Livewire\Component;
use Livewire\WithFileUploads;
use Src\Blog\Articles\Model\ArticleModel;
use Src\Blog\Articles\Infrastructure\EloquentArticleRepository;
use Src\Blog\Categories\Infrastructure\Eloquent\BlogCategoryEloquentModel;
use Ramsey\Uuid\Uuid;

class AddEditArticle extends Component
{
    use WithFileUploads;

    public $articleId;
    public $articleUuid;
    public $article;
    public $mode;
    public $currentLocale;
    public $availableLocales = ['en', 'es'];
    
    // Form fields
    public $title = ['en' => '', 'es' => ''];
    public $slug = ['en' => '', 'es' => ''];
    public $state = 'draft';
    public $body = ['en' => '', 'es' => ''];
    public $main_image = '';
    public $meta_title = ['en' => '', 'es' => ''];
    public $meta_description = ['en' => '', 'es' => ''];
    public $meta_keywords = ['en' => '', 'es' => ''];
    public $selectedCategories = [];
    
    // UI state
    public $isEditing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $showErrorMessage = false;
    public $errorMessage = '';

    protected $rules = [
        'title.en' => 'required|string|max:255',
        'title.es' => 'required|string|max:255',
        'slug.en' => 'required|string|max:255',
        'slug.es' => 'required|string|max:255',
        'state' => 'required|string|in:draft,published,archived',
        'body.en' => 'required|string',
        'body.es' => 'required|string',
        'main_image' => 'nullable|string',
        'meta_title.en' => 'nullable|string|max:255',
        'meta_title.es' => 'nullable|string|max:255',
        'meta_description.en' => 'nullable|string',
        'meta_description.es' => 'nullable|string',
        'meta_keywords.en' => 'nullable|string',
        'meta_keywords.es' => 'nullable|string',
        'selectedCategories' => 'array',
        'selectedCategories.*' => 'exists:blog_categories,id',
    ];

    protected $messages = [
        'title.en.required' => 'English title is required.',
        'title.es.required' => 'Spanish title is required.',
        'slug.en.required' => 'English slug is required.',
        'slug.es.required' => 'Spanish slug is required.',
        'body.en.required' => 'English body content is required.',
        'body.es.required' => 'Spanish body content is required.',
        'state.required' => 'State is required.',
        'state.in' => 'State must be draft, published, or archived.',
    ];

    public function mount($id = null, $uuid = null, $mode = null)
    {
        $this->currentLocale = app()->getLocale();
        $this->articleId = $id;
        $this->articleUuid = $uuid;
        $this->mode = $mode;
        
        if ($mode === 'edit' && $id) {
            $this->isEditing = true;
            $this->loadArticle($id);
        } elseif ($mode === 'create' && $uuid) {
            $this->isEditing = false;
            $this->articleUuid = $uuid;
            $this->initializeEmptyArticle();
        } else {
            // Fallback for backward compatibility
            if ($id) {
                $this->isEditing = true;
                $this->loadArticle($id);
            } else {
                $this->isEditing = false;
                $this->initializeEmptyArticle();
            }
        }
    }

    public function loadArticle($id)
    {
        $repository = new EloquentArticleRepository();
        $this->article = $repository->findById($id);
        
        if (!$this->article) {
            $this->showErrorMessage = true;
            $this->errorMessage = 'Article not found.';
            return;
        }

        // Load translatable fields
        foreach ($this->availableLocales as $locale) {
            $this->title[$locale] = $this->article->getTranslation('title', $locale) ?? '';
            $this->slug[$locale] = $this->article->getTranslation('slug', $locale) ?? '';
            $this->body[$locale] = $this->article->getTranslation('body', $locale) ?? '';
            $this->meta_title[$locale] = $this->article->getTranslation('meta_title', $locale) ?? '';
            $this->meta_description[$locale] = $this->article->getTranslation('meta_description', $locale) ?? '';
            $this->meta_keywords[$locale] = $this->article->getTranslation('meta_keywords', $locale) ?? '';
        }

        // Load non-translatable fields
        $this->state = $this->article->state ?? 'draft';
        $this->main_image = $this->article->main_image ?? '';
        
        // Load categories
        $this->selectedCategories = $this->article->categories->pluck('id')->toArray();
    }

    public function initializeEmptyArticle()
    {
        foreach ($this->availableLocales as $locale) {
            $this->title[$locale] = '';
            $this->slug[$locale] = '';
            $this->body[$locale] = '';
            $this->meta_title[$locale] = '';
            $this->meta_description[$locale] = '';
            $this->meta_keywords[$locale] = '';
        }
        
        $this->state = 'draft';
        $this->main_image = '';
        $this->selectedCategories = [];
    }

    public function switchLocale($locale)
    {
        if (in_array($locale, $this->availableLocales)) {
            $this->currentLocale = $locale;
            $this->clearMessages();
            $this->dispatch('locale-changed', locale: $locale);
        }
    }

    public function generateSlug($locale)
    {
        if (!empty($this->title[$locale])) {
            $this->slug[$locale] = \Str::slug($this->title[$locale]);
        }
    }

    public function updatedTitle($value, $name)
    {
        // Auto-generate slug when title changes
        $locale = str_replace('title.', '', $name);
        if (empty($this->slug[$locale])) {
            $this->generateSlug($locale);
        }
    }

    public function save()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show validation errors in global message area
            $this->showErrorMessage = true;
            $errors = [];
            foreach ($e->validator->errors()->all() as $error) {
                $errors[] = $error;
            }
            $this->errorMessage = 'Please fix the following errors: ' . implode(' ', $errors);
            return;
        }

        try {
            $repository = new EloquentArticleRepository();
            
            if ($this->isEditing && $this->article) {
                // Update existing article
                $article = $this->article;
            } else {
                // Create new article
                $article = new ArticleModel();
                $article->id = $this->articleUuid ?: Uuid::uuid4()->toString();
            }

            // Set translatable fields
            foreach ($this->availableLocales as $locale) {
                $article->setTranslation('title', $locale, $this->title[$locale]);
                $article->setTranslation('slug', $locale, $this->slug[$locale]);
                $article->setTranslation('body', $locale, $this->body[$locale]);
                $article->setTranslation('meta_title', $locale, $this->meta_title[$locale]);
                $article->setTranslation('meta_description', $locale, $this->meta_description[$locale]);
                $article->setTranslation('meta_keywords', $locale, $this->meta_keywords[$locale]);
            }

            // Set non-translatable fields
            $article->state = $this->state;
            $article->main_image = $this->main_image;
            $article->is_visible = true;

            $repository->save($article);

            // Sync categories
            $article->categories()->sync($this->selectedCategories);

            $this->showSuccessMessage = true;
            $this->successMessage = $this->isEditing ? 'Article updated successfully!' : 'Article created successfully!';
            $this->showErrorMessage = false;

            if (!$this->isEditing) {
                // Redirect to edit mode after creation
                $this->articleId = $article->id;
                $this->isEditing = true;
                $this->article = $article;
            }

        } catch (\Exception $e) {
            $this->showErrorMessage = true;
            $this->errorMessage = 'Error saving article: ' . $e->getMessage();
            $this->showSuccessMessage = false;
        }
    }

    public function cancel()
    {
        return $this->redirect('/admin-panel/blog');
    }

    public function clearMessages()
    {
        $this->showSuccessMessage = false;
        $this->showErrorMessage = false;
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function updated($propertyName)
    {
        // Clear messages when user starts typing or switches tabs
        $this->clearMessages();
        
        // Auto-generate slug when title changes
        if (str_contains($propertyName, 'title.')) {
            $locale = str_replace('title.', '', $propertyName);
            $this->generateSlug($locale);
        }
    }


    public function getStateOptions()
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived'
        ];
    }

    public function getAvailableCategories()
    {
        return BlogCategoryEloquentModel::active()->ordered()->get();
    }

    public function render()
    {
        return view('livewire.admin.blog.articles.add-edit');
    }
}