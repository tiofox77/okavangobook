<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleDetails extends Component
{
    public $article;
    public $relatedArticles;

    public function mount($slug)
    {
        $this->article = Article::published()
            ->where('slug', $slug)
            ->with('author')
            ->firstOrFail();
        
        $this->article->incrementViews();
        
        $this->relatedArticles = Article::published()
            ->where('category', $this->article->category)
            ->where('id', '!=', $this->article->id)
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.article-details')
            ->layout('layouts.app');
    }
}
