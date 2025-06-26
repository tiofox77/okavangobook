<?php

namespace App\Livewire;

use Livewire\Component;

class Contact extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;
    public $success = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required|min:5',
        'message' => 'required|min:10',
    ];

    public function render()
    {
        return view('livewire.contact')
            ->layout('layouts.app', [
                'title' => 'Entre em Contacto - Okavango Book',
                'metaDescription' => 'Entre em contacto conosco para dúvidas, sugestões ou para planejar sua viagem em Angola.'
            ]);
    }

    public function submitForm()
    {
        $this->validate();
        
        // Aqui você pode adicionar a lógica para enviar o email ou salvar no banco de dados
        // Por enquanto, apenas simularemos o sucesso do envio
        
        $this->success = true;
        
        // Limpar formulário
        $this->reset(['name', 'email', 'subject', 'message']);
    }
}
