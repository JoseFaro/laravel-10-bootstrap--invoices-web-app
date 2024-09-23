<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Si olvidaste tu contraseña ingresa tu correo electrónico y te enviaremos un correo para que la reestrablescas.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="app-form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mx-3" href="{{ route('login') }}">
                {{ __('Iniciar sesión') }}
            </a>

            <x-primary-button>
                {{ __('Enviar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
