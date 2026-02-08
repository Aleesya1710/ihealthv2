
<div x-data="{ showLogin: false, isLogin: true }">
    <nav class="bg-[#10859F] text-white p-4 flex items-center justify-between shadow-md">
        <div class="flex items-center">
            <img class="w-35 h-20" src="{{asset('image/logo.png')}}" alt="">
        <div class="text-xl font-bold">Sport & Wellness Clinic FSR</div>
        </div>
        
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

   <x-app-layout>
    <div class="flex h-auto">
        <aside class="m-5 w-48 bg-[#D9D9D9] rounded-2xl p-6 flex flex-col items-center h-auto max-h-[500px] shadow-sm ring-1 ring-black/5">
            <div class="w-full mb-6">
                <h1 class="text-xl font-bold text-center text-[#007b8a]">iHealth<span class="text-gray-500">Portal</span></h1>
            </div>

            <img src="{{ asset('image/profile.jpg') }}" class="w-20 h-20 rounded-full mb-4" alt="">

            <script src="//unpkg.com/alpinejs" defer></script>

<nav class="flex flex-col space-y-4 w-full">
    <a href="{{ url('/dashboardS') }}" 
       class="block text-center py-2 w-full border-b border-black hover:bg-gray-200 hover:rounded-md text-sm font-medium ">
        Dashboard
    </a>

    <a href="{{ route('appoinmentmanagement') }}" 
        class="block text-center py-2 w-full border-b border-black hover:bg-gray-200 hover:rounded-md text-sm font-medium ">
        Appointment Management
    </a>

    <a href="{{ route('patientmanagement') }}" 
       class="block text-center py-2 w-full border-b border-black hover:bg-gray-200 hover:rounded-md text-sm font-medium ">
        Patient Management
    </a>

        <a href="{{ route('report.index') }}" 
       class="block text-center py-2 w-full border-b border-black hover:bg-gray-200 hover:rounded-md text-sm font-medium ">
        Report Management
    </a>
</nav>
        </aside>

        <div class="flex-1 m-5 ml-0 bg-[#F4F7F8] rounded-2xl h-auto min-h-screen shadow-xl ring-1 ring-black/5 p-4">
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</x-app-layout>
    </div>
<style>
    button,
    .btn,
    input[type="submit"],
    input[type="button"],
    a.btn {
        border-radius: 0.5rem;
    }
</style>
