<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public $email = '';
    public $showSuccess = false;

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers,email',
    ];

    protected $messages = [
        'email.required' => 'Por favor, insira seu email.',
        'email.email' => 'Email inválido.',
        'email.unique' => 'Este email já está inscrito na nossa newsletter.',
    ];

    public function subscribe()
    {
        $this->validate();

        NewsletterSubscriber::create([
            'email' => $this->email,
            'is_active' => true,
        ]);

        $this->showSuccess = true;
        $this->email = '';
        $this->resetValidation();

        // Auto-hide success message after 5 seconds
        $this->dispatch('newsletter-subscribed');
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}
