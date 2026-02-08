    <div x-show="showLogin" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="showLogin = false"
            class="flex w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden h-[80%]">
            <div class="w-1/2 bg-[#104F5D] text-white flex flex-col items-center justify-center p-8">
                <img src="{{ asset('image/logo.jpg') }}" alt="Logo" class="h-20 mb-4">
                <h2 class="text-xl font-bold text-center">Sport & Wellness Clinic FSR</h2>
                <p class="text-sm text-white/80 mt-2 text-center">Book appointments, manage records, and stay healthy.</p>
            </div>

            <div class="w-1/2 p-8 relative overflow-y-auto bg-[#FBFCFD]">
                <button @click="showLogin = false"
                    class="absolute top-4 right-6 text-gray-400 hover:text-gray-600 text-2xl font-bold">
                    &times;
                </button>

                <div x-show="isLogin && !isForgot" x-transition class="h-full flex items-center justify-center">
                    <div class="w-full">
                        <h2 class="text-2xl font-bold text-[#104F5D] mb-2 text-start">Login</h2>
                        <p class="text-sm mb-4 text-start">Welcome back! Please login to your account.</p>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}"
                                    class="block w-full mt-1 rounded-lg bg-white text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="password" :value="__('Password')" />
                                <input id="password" name="password" type="password" required
                                    class="block w-full mt-1 rounded-lg bg-white text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <label class="flex items-center text-sm text-gray-600">
                                    <input type="checkbox" name="remember"
                                        class="mr-2 rounded border-gray-700 text-indigo-600" />
                                    Remember me
                                </label>
                                @if (Route::has('password.request'))
                                    <button type="button"
                                        @click="isForgot = true; isLogin = true"
                                        class="text-sm text-[#104F5D] hover:underline">Forgot password?</button>
                                @endif
                            </div>

                            <div class="flex justify-center mb-4">
                                <button type="submit"
                                    class="rounded-xl w-[60%] h-10 bg-[#104F5D] hover:bg-[#0b3c45] text-white">
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

                <div x-show="!isLogin && !isForgot" x-transition class="h-full flex items-center">
                    <div class="w-full pt-4">
                    <h2 class="text-2xl font-bold text-[#104F5D] mb-2">Register</h2>
                    <p class="text-sm mb-4">Create a new account to book appointments.</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <input id="name" name="name" type="text" required value="{{ old('name') }}"
                                class="block w-full mt-1 rounded-lg bg-white text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                class="block w-full mt-1 rounded-lg bg-white text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <input id="password" name="password" type="password" required
                                class="block w-full mt-1 rounded-lg bg-white text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full mt-1 rounded-lg bg-white text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                        </div>

                        <div class="flex justify-center mb-4">
                            <button type="submit"
                                class="rounded-xl w-[60%] h-10 bg-[#104F5D] hover:bg-[#0b3c45] text-white">
                                Register
                            </button>
                        </div>
                    </form>

                    <div class="text-sm text-center">
                        Already have an account?
                        <button @click="isLogin = true"
                            class="text-[#104F5D] font-semibold hover:underline">Log in</button>
                    </div>
                    </div>
                </div>

                <div x-show="isForgot" x-transition class="h-full flex items-center">
                    <div class="w-full">
                        <h2 class="text-2xl font-bold text-[#104F5D] mb-2">Reset Password</h2>
                        <p class="text-sm mb-4">Enter your email and we’ll send a reset link.</p>

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <input type="hidden" name="auth_view" value="forgot">

                            <div class="mb-4">
                                <x-input-label for="reset_email" :value="__('Email')" />
                                <input id="reset_email" name="email" type="email" required value="{{ old('email') }}"
                                    class="block w-full mt-1 rounded-lg bg-white text-black border border-gray-300 focus:ring-2 focus:ring-[#10859F]" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="flex justify-center mb-4">
                                <button type="submit"
                                    class="rounded-xl w-[60%] h-10 bg-[#104F5D] hover:bg-[#0b3c45] text-white">
                                    Send Reset Link
                                </button>
                            </div>
                        </form>

                        <div class="text-sm text-center">
                            Remembered your password?
                            <button @click="isForgot = false; isLogin = true"
                                class="text-[#104F5D] font-semibold hover:underline">Back to login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script src="//unpkg.com/alpinejs" defer></script>
