<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a wire:navigate href="{{ route('home') }}"><img src="assets/static/images/logo/logo.png" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Forgot Password</h1>
            <p class="auth-subtitle mb-5">Input your email and we will send you reset password link.</p>

            <form wire:submit="sendResetLink">
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email" wire:model="email">
                    <div class="form-control-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <x-ui.button type="submit" variant="primary" size="lg" class="btn-block shadow-lg mt-5">
                    Send
                </x-ui.button>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class='text-gray-600'>Remember your account? <a wire:navigate href="{{ route('login') }}" class="font-bold">Log in</a>.
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">

        </div>
    </div>
</div>
