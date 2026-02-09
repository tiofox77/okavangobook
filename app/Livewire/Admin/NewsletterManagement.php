<?php

namespace App\Livewire\Admin;

use App\Models\NewsletterSubscriber;
use Livewire\Component;
use Livewire\WithPagination;

class NewsletterManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $subscriber = NewsletterSubscriber::find($id);
        $subscriber->update(['is_active' => !$subscriber->is_active]);
        session()->flash('message', 'Status atualizado!');
    }

    public function delete($id)
    {
        NewsletterSubscriber::find($id)->delete();
        session()->flash('message', 'Assinante removido com sucesso!');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::active()
            ->select('email', 'name', 'subscribed_at')
            ->orderBy('subscribed_at', 'desc')
            ->get();

        $filename = 'newsletter_subscribers_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Email', 'Nome', 'Data de Inscrição']);
            
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->name ?? '-',
                    $subscriber->subscribed_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $subscribers = NewsletterSubscriber::query()
            ->when($this->search, function ($query) {
                $query->where('email', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus !== 'all', function ($query) {
                $query->where('is_active', $this->filterStatus === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::active()->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
        ];

        return view('livewire.admin.newsletter-management', [
            'subscribers' => $subscribers,
            'stats' => $stats,
        ])->layout('layouts.admin');
    }
}
