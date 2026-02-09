<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ArticleManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $articleId;
    public $title;
    public $excerpt;
    public $content;
    public $category;
    public $tags = [];
    public $locations = [];
    public $is_published = false;
    public $featured_image;
    public $search = '';
    public $filterCategory = 'all';

    protected $rules = [
        'title' => 'required|string|max:255',
        'excerpt' => 'nullable|string',
        'content' => 'required|string',
        'category' => 'required|string',
        'tags' => 'nullable|array',
        'locations' => 'nullable|array',
        'is_published' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $this->articleId = $article->id;
        $this->title = $article->title;
        $this->excerpt = $article->excerpt;
        $this->content = $article->content;
        $this->category = $article->category;
        $this->tags = $article->tags ?? [];
        $this->locations = $article->locations ?? [];
        $this->is_published = $article->is_published;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'author_id' => auth()->id(),
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category' => $this->category,
            'tags' => $this->tags,
            'locations' => $this->locations,
            'is_published' => $this->is_published,
            'published_at' => $this->is_published ? now() : null,
        ];

        if ($this->articleId) {
            Article::find($this->articleId)->update($data);
            session()->flash('message', 'Artigo atualizado com sucesso!');
        } else {
            Article::create($data);
            session()->flash('message', 'Artigo criado com sucesso!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        Article::find($id)->delete();
        session()->flash('message', 'Artigo removido!');
    }

    public function togglePublished($id)
    {
        $article = Article::find($id);
        $article->update([
            'is_published' => !$article->is_published,
            'published_at' => !$article->is_published ? now() : null,
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->articleId = null;
        $this->title = '';
        $this->excerpt = '';
        $this->content = '';
        $this->category = '';
        $this->tags = [];
        $this->locations = [];
        $this->is_published = false;
        $this->resetValidation();
    }

    public function render()
    {
        $articles = Article::with('author')
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterCategory !== 'all', function($query) {
                $query->where('category', $this->filterCategory);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.article-management', [
            'articles' => $articles,
        ])->layout('layouts.admin');
    }
}
