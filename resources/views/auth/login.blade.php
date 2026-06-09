<x-guest-layout split title="Masuk">

  {{-- Mobile Logo (only visible on small screens) --}}
  <div class="text-center mb-4 d-lg-none">
    <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:rgba(27,94,66,0.1);overflow:hidden;">
      @php $logoSetting = $globalSettings['school_logo'] ?? ''; @endphp
      @if (!empty($logoSetting))
        @php $logoUrl = str_starts_with($logoSetting, 'http') ? $logoSetting : \App\Helpers\StorageHelper::url($logoSetting); @endphp
        <img src="{{ $logoUrl }}" alt="Logo" style="max-height:40px;max-width:40px;object-fit:contain;">
      @else
        @include('_partials.macros', ['width' => '32', 'height' => '22'])
      @endif
    </span>
  </div>

  {{-- Heading --}}
  <h4 class="mb-1" style="font-family:'Trajan Pro',serif;color:var(--mansaba-dark);">{{ __('Selamat Datang') }}</h4>
  <p class="mb-6 text-muted" style="font-size:0.9rem;">{{ __('Silakan masuk ke akun Anda') }}</p>

  {{-- Validation Errors --}}
  <x-validation-errors class="mb-4" />

  {{-- Login Form --}}
  <form id="formAuthentication" method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Email --}}
    <div class="mb-4">
      <x-label for="email" value="{{ __('Email') }}" />
      <x-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
    </div>

    {{-- Password with Show/Hide Toggle --}}
    <div class="mb-4 form-password-toggle">
      <x-label for="password" value="{{ __('Password') }}" />
      <div class="input-group input-group-merge">
        <x-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
        <span class="input-group-text cursor-pointer">
          <i class="ti tabler-eye-off"></i>
        </span>
      </div>
    </div>

    {{-- Remember Me & Forgot Password --}}
    <div class="my-6">
      <div class="d-flex justify-content-between align-items-center">
        <div class="form-check mb-0">
          <x-checkbox id="remember_me" name="remember" />
          <label class="form-check-label" for="remember_me" style="font-size:0.88rem;color:var(--mansaba-text);">{{ __('Ingat saya') }}</label>
        </div>
        @if (\Illuminate\Support\Facades\Route::has('password.request'))
          <a href="{{ route('password.request') }}" style="color:var(--mansaba-green);font-weight:600;font-size:0.85rem;text-decoration:none;">
            {{ __('Lupa password?') }}
          </a>
        @endif
      </div>
    </div>

    {{-- Submit Button with Loading State --}}
    <div class="mb-4">
      <button type="submit" class="btn btn-primary w-100" id="btn-login" style="padding:0.75rem 1.5rem;font-weight:600;border-radius:8px;">
        <span id="btn-login-text">
          <i class="ti tabler-login me-2"></i>{{ __('Masuk') }}
        </span>
        <span id="btn-login-loading" class="d-none">
          <span class="spinner-border spinner-border-sm me-2" role="status"></span>
          {{ __('Memproses...') }}
        </span>
      </button>
    </div>
  </form>

  {{-- Register Link --}}
  <p class="text-center mt-4 mb-0" style="font-size:0.88rem;color:var(--mansaba-text);">
    <span>{{ __('Belum punya akun?') }}</span>
    <a href="{{ route('register') }}" style="color:var(--mansaba-gold);font-weight:700;text-decoration:none;">
      {{ __('Daftar') }}
    </a>
  </p>



</x-guest-layout>
