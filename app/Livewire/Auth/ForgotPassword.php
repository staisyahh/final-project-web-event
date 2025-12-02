<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.guest')]
class ForgotPassword extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    public function sendResetLink()
    {
        $this->validate();

        // Fortify password reset logic will be added here later
        // For now, we'll just log the attempt or show a success message
        session()->flash('status', 'Password reset link sent to ' . $this->email);
        $this->redirectRoute('login'); // Redirect to login for now
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
