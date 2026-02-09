<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category',
        'tags',
        'locations',
        'read_time',
        'views',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'locations' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            
            if (empty($article->read_time)) {
                $article->read_time = ceil(str_word_count(strip_tags($article->content)) / 200);
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForLocation($query, $location)
    {
        return $query->whereJsonContains('locations', $location);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
