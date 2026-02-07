@include('navigation.navbartop')

<x-app-layout>
    <section class=" py-12 px-6">
        <div class="max-w-5xl mx-auto bg-white p-16 rounded-xl">
            <h1 class="text-3xl font-bold text-center text-[#104F5D] mb-10">Our Services</h1>
    
            <h2 class="text-xl font-semibold text-[#104F5D] border-b pb-2 mb-4">
                Available Services
            </h2>
            
            <div class="space-y-4">
                @foreach ($services as $service)
                @php
                    $prices = is_array($service->fee) ? $service->fee : (array) $service->fee;
                    $minPrice = min($prices);
                    $maxPrice = max($prices);
                @endphp
                    <div class="flex justify-between items-center bg-[#F9FAFB] p-4 rounded-lg shadow-lg">
                        <div>
                            <p class="font-semibold">{{ $service->name }}</p>
                            <p class="text-sm text-gray-600">{{ $service->description }}</p>
                            <p class="text-sm text-gray-600">
                                RM{{ $minPrice }} - RM{{ $maxPrice }} 
                            </p>
                        </div>
                        <div><a href="{{ route('Customer.createbooking', $service->id) }}"
                            class="bg-[#104F5D] text-white px-4 py-2 rounded-3xl hover:bg-[#0c3e4a]">
                             Book Now
                         </a></div>
                    </div>
                @endforeach
            </div>
            
        </div>
    </section>
    
</x-app-layout>
<script>
     @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        });
    @endif

    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
                confirmButtonText: 'Try Again'
            });
        });
    @endif
</script>