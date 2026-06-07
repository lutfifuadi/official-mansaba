<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <h4 class="mb-1" style="font-family:'Trajan Pro',serif;color:var(--mansaba-dark);">{{ __('Welcome Back!') }}</h4>
        <p class="mb-6 text-muted" style="font-size:0.9rem;">{{ __('Please sign in to your account') }}</p>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="mansaba-form-control block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            </div>

            <div class="mb-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="mansaba-form-control block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <div class="my-6">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check mb-0">
                        <x-checkbox id="remember_me" name="remember" />
                        <label class="form-check-label" for="remember_me" style="font-size:0.88rem;color:var(--mansaba-text);">{{ __('Remember me') }}</label>
                    </div>
                    @if (\Illuminate\Support\Facades\Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color:var(--mansaba-green);font-weight:600;font-size:0.85rem;text-decoration:none;">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="mansaba-btn-submit btn w-100" style="padding:0.75rem 1.5rem;">
                    <i class="ti tabler-login me-2"></i>{{ __('Log in') }}
                </button>
            </div>
        </form>

        <p class="text-center mt-4 mb-0" style="font-size:0.88rem;color:var(--mansaba-text);">
            <span>{{ __('Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}" style="color:var(--mansaba-gold);font-weight:700;text-decoration:none;">
                {{ __('Register') }}
            </a>
        </p>
    </x-authentication-card>
</x-guest-layout>
