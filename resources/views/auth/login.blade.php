    <!-- Modal -->
    <div x-show="showLogin" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.outside="showLogin = false"
            class="flex w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-hidden h-[80%]">
            <!-- Left side: Logo -->
            <div class="w-1/2 bg-[#104F5D] text-white flex flex-col items-center justify-center p-8">
                <img src="{{ asset('image/logo.jpg') }}" alt="Logo" class="h-20 mb-4">
                <h2 class="text-xl font-bold text-center">Sport & Wellness Clinic FSR</h2>
            </div>

            <!-- Right side: Forms -->
            <div class="w-1/2 p-8 relative overflow-y-auto">
                <button @click="showLogin = false"
                    class="absolute top-4 right-6 text-gray-400 hover:text-gray-600 text-2xl font-bold">
                    &times;
                </button>

                <!-- LOGIN FORM -->
                <div x-show="isLogin" x-transition class="h-full flex items-center justify-center">
                    <div class="w-full">
                        <h2 class="text-2xl font-bold text-[#104F5D] mb-2 text-start">Login</h2>
                        <p class="text-sm mb-4 text-start">Welcome back! Please login to your account.</p>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <input id="email" name="email" type="email" required autofocus
                                    class="block w-full mt-1 bg-[#F9FAFB] text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <x-input-label for="password" :value="__('Password')" />
                                <input id="password" name="password" type="password" required
                                    class="block w-full mt-1 bg-[#F9FAFB] text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Remember / Forgot -->
                            <div class="flex items-center justify-between mb-4">
                                <label class="flex items-center text-sm text-gray-600">
                                    <input type="checkbox" name="remember"
                                        class="mr-2 rounded border-gray-700 text-indigo-600" />
                                    Remember me
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm text-[#104F5D] hover:underline">Forgot password?</a>
                                @endif
                            </div>

                            <div class="flex justify-center mb-4">
                                <button type="submit"
                                    class="rounded-xl w-[60%] h-10 bg-[#104F5D] hover:bg-[#104f5d81] text-white">
                                    Log in
                                </button>
                            </div>
                        </form>

                        <div class="text-sm text-center">
                            Don’t have an account?
                            <button @click="isLogin = false"
                                class="text-[#104F5D] font-semibold hover:underline">Sign up</button>
                        </div>
                    </div>
                </div>


                <!-- REGISTER FORM -->
                <div x-show="!isLogin" x-transition>
                    <h2 class="text-2xl font-bold text-[#104F5D] mb-2">Register</h2>
                    <p class="text-sm mb-4">Create a new account to book appointments.</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <input id="name" name="name" type="text" required
                                class="block w-full mt-1 bg-[#F9FAFB] text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <input id="email" name="email" type="email" required
                                class="block w-full mt-1 bg-[#F9FAFB] text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <input id="password" name="password" type="password" required
                                class="block w-full mt-1 bg-[#F9FAFB] text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full mt-1 bg-[#F9FAFB] text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                        </div>

                        <div class="flex justify-center mb-4">
                            <button type="submit"
                                class="rounded-xl w-[60%] h-10 bg-[#104F5D] hover:bg-[#104f5d81] text-white">
                                Register
                            </button>
                        </div>
                    </form>

                    <!-- Log in link -->
                    <div class="text-sm text-center">
                        Already have an account?
                        <button @click="isLogin = true"
                            class="text-[#104F5D] font-semibold hover:underline">Log in</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- AlpineJS -->
<script src="//unpkg.com/alpinejs" defer></script>
