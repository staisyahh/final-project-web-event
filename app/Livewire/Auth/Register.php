<?php

namespace App\Livewire\Auth;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.guest')]
class Register extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|email|max:255|unique:users')]
    public string $email = '';

    #[Rule('required|string|min:8')]
    public string $password = '';

    #[Rule('required|string|min:8|same:password')]
    public string $passwordConfirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(CreateNewUser $creator)
    {
        $this->validate();

        $user = $creator->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
        ]);

        Auth::login($user);

        return redirect()->intended(config('fortify.home'));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
