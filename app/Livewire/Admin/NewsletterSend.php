<?php

namespace App\Livewire\Admin;

use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class NewsletterSend extends Component
{
    public $subject = '';
    public $message = '';
    public $preview = false;
    
    protected $rules = [
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ];

    public function togglePreview()
    {
        $this->preview = !$this->preview;
    }

    public function send()
    {
        $this->validate();

        $subscribers = NewsletterSubscriber::active()->pluck('email')->toArray();
        
        if (empty($subscribers)) {
            session()->flash('error', 'Nenhum assinante ativo encontrado.');
            return;
        }

        $subject = $this->subject;
        $messageContent = $this->message;
        
        try {
            foreach ($subscribers as $email) {
                Mail::raw($messageContent, function ($mail) use ($email, $subject) {
                    $mail->to($email)
                        ->subject($subject);
                });
            }
            
            session()->flash('message', 'Email enviado para ' . count($subscribers) . ' assinantes!');
            
            $this->subject = '';
            $this->message = '';
            $this->preview = false;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao enviar emails: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $subscribersCount = NewsletterSubscriber::active()->count();
        
        return view('livewire.admin.newsletter-send', [
            'subscribersCount' => $subscribersCount,
        ])->layout('layouts.admin');
    }
}
