
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>IHealthPortal</title>
        

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

       @vite(['resources/css/app.css', 'resources/js/app.js'])
       <script src="//unpkg.com/alpinejs" defer></script>

    </head>

    <body  x-data="{ showLogin: false, isLogin: true }" class="bg-[#353D3F] text-[#1b1b18] flex items-start justify-start min-h-screen flex-col">
        <header class="w-full fixed z-50">
            @include('navigation.navbartop')
        </header> 
        <div class="bg-[#353D3F] shadow-md pt-0.5 w-full flex h-[500px]">
            <div class="mt-16 flex-1 flex items-center justify-center">
                <div class=" text-gray-50 max-w-[450px]">
                    <h1 class="text-4xl font-bold">Fitness Center</h1>
                    <p class="mt-2">Discover holistic healing at the heart of FSR.
We offer the most affordable wellness and rehabilitation services specially catered to students, staff, and the public. Whether you're recovering, relaxing, or revitalizing, our centre provides a range of professional treatment. Let our certified therapists and modern facilities guide you toward better health, improved mobility, and overall well-being without breaking the bank.</p>
                  </div>
                  
            </div>
            <div class="mt-16 w-[40%] h-[450px] rounded-tl-full overflow-hidden ml-auto">
                <img src="{{ asset('image/header.webp') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
        </div>
        
      <div class="bg-[#D9D9D9] shadow-md w-full h-[600px] p-6 text-white flex flex-col items-center justify-center gap-10">
          <div class="w-[90%] h-16 rounded-full bg-[#353D3F] flex justify-center items-center">
              <h2 class="text-xl font-semibold">TREATMENT</h2>
          </div>
          <div  x-data="{ activeSlide: 0, total: 2 }"    x-init="setInterval(() => { activeSlide = (activeSlide + 1) % total }, 4000)"  class="relative w-[87%] overflow-hidden">
              <div class="flex transition-transform duration-700" :style="'transform: translateX(-' + activeSlide * 100 + '%)'"  style="width: 200%">
                  <div class="w-full flex flex-shrink-0 gap-4 justify-center">
                    <div style="background-image: url('{{ asset('image/electro.webp') }}');" class=" bg-center bg-contain text-white w-1/3 h-72 rounded-lg shadow-md p-4">
                          <h3 class="font-bold mb-2">Electrotherapy</h3>
                          <p>Uses electrical currents to relieve pain and aid recovery</p>
                    </div>
                    <div style="background-image: url('{{ asset('image/theraphy.avif') }}');" class=" bg-center  bg-contain text-white w-1/3 h-72 rounded-lg shadow-md p-4">
                          <h3 class="font-bold mb-2">Therapeutic Exercises</h3>
                          <p>Rehab-focused exercises to restore movement</p>
                    </div>
                    <div style="background-image: url('{{ asset('image/bodycomp.jpg') }}');" class=" bg-center  bg-contain text-white w-1/3 h-72 rounded-lg shadow-md p-4">
                          <h3 class="font-bold mb-2">Body Composition Analysis</h3>
                          <p>Measures fat, muscle, and body health</p>
                    </div>
                  </div>
                  <div class="w-full flex flex-shrink-0 gap-4 justify-center">
                      <div style="background-image: url('{{ asset('image/sportmassage.jpeg') }}');" class=" bg-center bg-contain  text-white w-1/3 h-72 rounded-lg shadow-md p-4">
                          <h3 class="font-bold mb-2">Sport Massage</h3>
                          <p>Relieves muscle tension and speeds up recovery</p>
                      </div>
                      <div style="background-image: url('{{ asset('image/personalconsultation.png') }}');" class=" bg-center bg-contain text-white w-1/3 h-72 rounded-lg shadow-md p-4">
                          <h3 class="font-bold mb-2">Sport And Exercise Consultation</h3>
                          <p>Personalized fitness advice and planning</p>
                      </div>
                      <div style="background-image: url('{{ asset('image/yoga.avif') }}');" class=" bg-center bg-contain text-white w-1/3 h-72 rounded-lg shadow-md p-4">
                          <h3 class="font-bold mb-2">Yoga</h3>
                          <p>Enhances flexibility, balance, and athletic performance</p>
                      </div>
                  </div>
              </div>
              <button @click="activeSlide = (activeSlide - 1 + total) % total"
                  class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-black text-white px-3 py-1 rounded-full">❮</button>
      
              <button @click="activeSlide = (activeSlide + 1) % total"
                  class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-black text-white px-3 py-1 rounded-full">❯</button>
          </div>
          <div class="w-[20%] h-16 rounded-full bg-[#353D3F] flex justify-center items-center">
            <h2>
    @auth
        <a href="{{ url('/appointment') }}" class="hover:underline">Book Your Appointment Now</a>
    @else
        <a href="#" @click="showLogin = true; isLogin = true" class="hover:underline">Book Your Appointment Now</a>
    @endauth
</h2>

        </div>
      </div>
      
      <div class="bg-[#353D3F] w-full py-20 px-4 flex justify-center text-[#1b1b18]">
        <div x-data="{
                activeSlide: 0,
                feedbacks: [
                    { name: 'Cik B', text: 'Great service and friendly staff!' },
                    { name: 'Zul Aiman', text: 'Very professional and helpful consultation.' },
                    { name: 'Fatin Izzah', text: 'The facilities are clean and comfortable!' }
                ],
                init() {
                    setInterval(() => {
                        this.activeSlide = (this.activeSlide + 1) % this.feedbacks.length;
                    }, 3000);
                }
            }" 
            x-init="init"
            class="w-full max-w-4xl bg-[#E8F0F2] rounded-2xl p-10 shadow-2xl text-center relative overflow-hidden">
    
            <template x-for="(item, index) in feedbacks" :key="index">
                <div x-show="activeSlide === index" 
                     x-transition 
                     class="absolute inset-0 flex flex-col justify-center items-center px-6">
                    <p class="text-xl italic mb-6" x-text="item.text"></p>
                    <span class="text-base font-semibold text-[#10859F]" x-text="item.name"></span>
                </div>
            </template>
            
            <div class=" mt-28 flex justify-center space-x-3">
                <template x-for="(item, index) in feedbacks" :key="'dot-' + index">
                    <div :class="activeSlide === index ? 'bg-[#10859F]' : 'bg-gray-400'" 
                         class="w-3 h-3 rounded-full transition-all duration-300"></div>
                </template>
            </div>
        </div>
    </div>
    
    
    <div class="bg-[#D9D9D9] shadow-md w-full py-10 px-6">
        <h2 class="text-3xl font-bold text-center text-[#1b1b18] mb-10">About Us</h2>
    
        <div class="flex flex-col lg:flex-row gap-8 items-center justify-between w-full max-w-7xl mx-auto">
            
            <section class="w-full lg:w-1/2 rounded-xl overflow-hidden shadow-lg border border-gray-300">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7968.1932510897195!2d101.49707194999999!3d3.06884765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc52f50f0d8f51%3A0x1839b92afc81adc1!2sSports%20%26%20Wellness%20Clinic%20FSR!5e0!3m2!1sen!2smy!4v1752405215949!5m2!1sen!2smy" 
                    width="100%" 
                    height="400" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </section>
    
            <section class="w-full lg:w-1/2 text-[#1b1b18]">
                <div class="grid gap-6">
                    <div>
                        <h3 class="text-xl font-bold mb-2">Business Information</h3>
                        <p>Sports Injuries Rehabilitation & Sports Massage</p>
                    </div>
    
                    <div>
                        <h3 class="text-xl font-bold mb-2">Contact Us</h3>
                        <ul class="space-y-1">
                            <li>📞 03-5544 2964</li>
                            <li>📧 klinikkecederaansukanfsr@gmail.com</li>
                            <li>🌐 <a href="https://sports.uitm.edu.my" class="text-blue-600 underline hover:text-blue-800" target="_blank">sports.uitm.edu.my</a></li>
                            <li><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-black-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.656 0 3-1.343 3-3S13.656 5 12 5 9 6.343 9 8s1.344 3 3 3zm0 9c-3.333-3.333-5-6.667-5-10a5 5 0 1110 0c0 3.333-1.667 6.667-5 10z"/>
                              </svg>Jalan Siswazah 1/2 Makmal Pembangunan Atlet Shah Alam, Selangor 40450</li>
                        </ul>
                    </div>
    
                    <div>
                        <h3 class="text-xl font-bold mb-2">Good to Know</h3>
                        <ul class="space-y-1">
                            <li>📅 Booking Policy</li>
                            <li>📱 Social Media</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
    </body>
</html>    
@include ('auth.login')