<x-app-layout>
    <x-slot name="header">
        <h1 class="font-heading text-2xl font-bold text-ink">My Account</h1>
    </x-slot>

    <div class="space-y-6">
        <div class="card p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
