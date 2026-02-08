@php
    $authErrors = $errors->has('email')
        || $errors->has('password')
        || $errors->has('name')
        || $errors->has('password_confirmation');
    $showLogin = ($authErrors || session('status')) ? 'true' : 'false';
    $isForgot = (old('auth_view') === 'forgot' || session('status')) ? 'true' : 'false';
    $isLogin = (old('name') || old('password_confirmation')) ? 'false' : 'true';
@endphp
<div x-data="{ showLogin: {{ $showLogin }}, isLogin: {{ $isLogin }}, isForgot: {{ $isForgot }} }">
    <nav class="bg-[#10859F] text-white p-4 flex items-center justify-between shadow-md">
        <img class="w-35 h-20" src="{{asset('image/logo.png')}}" alt="">
        <div class="text-xl font-bold">Sport & Wellness Clinic FSR</div>

        <ul class="flex flex-1 justify-center gap-8">
            <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
            <li>@auth <a href="{{ url('/appointment') }}" class="hover:underline">@else<a href="#" @click="showLogin = true; isLogin = true" class="hover:underline">@endauth Book Appointment</a></li>
            @auth<li><a href="{{ route('appointmenthistory', Auth::user()->id) }}" class="hover:underline">Appointment History</a></li>@endauth
        </ul>

        @auth
             <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent leading-4 text-base font-medium rounded-md text-gray-300 bg-[#10859F] hover:text-gray-700  focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        @else
        <button @click="showLogin = true; isLogin = true"
        class="w-40 px-5 py-1.5 bg-[#104F5D] text-white border rounded-xl text-sm">
        Log in
    </button>
        @endauth
    </nav>

@include ('auth.login')
