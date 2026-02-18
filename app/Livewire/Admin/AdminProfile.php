<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminProfile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public ?string $phone = '';
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public $avatar;
    public ?string $currentAvatar = null;

    // Tab state
    public string $activeTab = 'profile';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->currentAvatar = $user->avatar ?? null;
    }

    public function changeTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // Check if phone column exists
        if (\Schema::hasColumn('users', 'phone')) {
            $data['phone'] = $this->phone;
        }

        $user->update($data);

        session()->flash('profile_success', 'Perfil actualizado com sucesso!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'A senha actual é obrigatória.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'new_password.confirmed' => 'A confirmação da senha não corresponde.',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'A senha actual está incorrecta.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        session()->flash('password_success', 'Senha alterada com sucesso!');
    }

    public function render()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription;
        $plan = $subscription?->plan;

        return view('livewire.admin.admin-profile', [
            'user' => $user,
            'subscription' => $subscription,
            'plan' => $plan,
        ])->layout('layouts.admin');
    }
}
