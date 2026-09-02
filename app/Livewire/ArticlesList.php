<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\UserPreference;
use Livewire\Component;
use Livewire\WithPagination;

class ArticlesList extends Component
{
    use WithPagination;

    public $category = 'all';
    public $search = '';

    /** Repõe a pesquisa e a categoria (botão do estado vazio e da barra). */
    public function limparFiltros(): void
    {
        $this->reset('search', 'category');
        $this->category = 'all';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $articles = $this->getArticles();

        return view('livewire.articles-list', [
            'articles' => $articles,
        ])->layout('layouts.app');
    }

    private function getArticles()
    {
        $query = Article::published()->with('author');

        if (auth()->check()) {
            $preferences = UserPreference::where('user_id', auth()->id())->first();
            
            if ($preferences && $preferences->preferred_locations) {
                $query->where(function($q) use ($preferences) {
                    foreach ($preferences->preferred_locations as $location) {
                        $q->orWhereJsonContains('locations', $location);
                    }
                });
            }
        }

        if ($this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        return $query->latest('published_at')->paginate(12);
    }
}
