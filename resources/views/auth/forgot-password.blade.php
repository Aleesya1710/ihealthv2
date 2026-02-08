<x-guest-layout>
    <div class="max-w-md mx-auto">
        <div class="bg-white shadow-lg ring-1 ring-black/5 rounded-2xl overflow-hidden">
            <div class="px-6 py-5 bg-[#10859F] text-white">
                <h2 class="text-xl font-semibold">Reset Your Password</h2>
                <p class="text-sm text-white/80 mt-1">We’ll email you a reset link.</p>
            </div>

            <div class="p-6 space-y-4">
                <div class="text-sm text-gray-600">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <x-auth-session-status class="mb-2" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-[#F9FAFB] border border-gray-300 focus:ring-2 focus:ring-[#10859F]" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Home</a>
                        <x-primary-button class="px-5 py-2">
                            {{ __('Email Password Reset Link') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
