<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <x-text-input
                    id="password"
                    class="block w-full pr-12"
                    x-bind:type="show ? 'text' : 'password'"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-ink-muted transition hover:text-brand-blue"
                    @click="show = !show"
                    :aria-pressed="show.toString()"
                    aria-controls="password"
                    aria-label="Show password"
                    x-bind:aria-label="show ? 'Hide password' : 'Show password'"
                >
                    <span x-show="!show" class="material-symbols-outlined text-[20px]" aria-hidden="true">visibility</span>
                    <span x-show="show" x-cloak class="material-symbols-outlined text-[20px]" aria-hidden="true">visibility_off</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-brand-blue shadow-sm focus:ring-brand-blue" name="remember">
                <span class="ms-2 text-sm text-ink-muted">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-brand-blue hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center py-3">
            {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>
