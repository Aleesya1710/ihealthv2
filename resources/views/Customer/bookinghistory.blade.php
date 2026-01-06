@include('navigation.navbartop')

<x-app-layout>
    @if (session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50 shadow-lg"
        role="alert"
    >
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 shadow-lg"
        role="alert"
    >
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div x-data="appointmentModal()" x-init>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto bg-white py-8 px-5 mt-4 rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Booking History
        </h2>

        @if($appointment->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">
                You have no booking history yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse bg-white shadow rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-left text-sm h-20 font-semibold text-gray-700 uppercase">
                            <th class="py-3 px-4 border-b">#</th>
                            <th class="py-3 px-4 border-b">Service</th>
                            <th class="py-3 px-4 border-b">Date</th>
                            <th class="py-3 px-4 border-b">Time</th>
                            <th class="py-3 px-4 border-b">Staff</th>
                            <th class="py-3 px-4 border-b">Status</th>
                            <th class="py-3 px-4 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointment as $index => $a)
                            <tr class="hover:bg-gray-50 text-sm h-20">
                                <td class="py-3 px-4 border-b">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 border-b">{{ $services->firstWhere('id', $a->service_id)?->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 border-b">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                                <td class="py-3 px-4 border-b">{{ \Carbon\Carbon::parse($a->time)->format('h:i A') }}</td>
                                <td class="py-3 px-4 border-b">
                                    {{ $a->staff_id ? ($staff->firstWhere('id', $a->staff_id)?->name ?? 'Unavailable') : 'Pending Assignment' }}
                                </td>
                                <td class="py-3 px-4 border-b">
                                    @php
                                        $statusColors = ['upcoming' => 'text-blue-600 bg-blue-100', 'cancelled' => 'text-red-600 bg-red-100', 'completed' => 'text-green-600 bg-green-100'];
                                    @endphp
                                    <span class="px-2 py-1 rounded {{ $statusColors[$a->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                                 <td class="py-3 px-4 border-b">
                                    @if ($a->status === 'upcoming')
                                    <div class="flex gap-1">
                                              <button 
                                            @click="openModal({{ $a->id }}, '{{ $a->date }}', '{{ \Carbon\Carbon::parse($a->time)->format('H:i') }}')" 
                                            class="text-yellow-600 hover:text-yellow-800 text-sm flex items-center gap-1"
                                        >
                                            <!-- Pencil SVG -->
                                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l3 3L14 13H11v-3l7.5-7.5z" />
                                            </svg>
                                        </button>
                                         <form method="POST" :action="'/appointment/' + {{ $a->id }} + '/cancel'" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800"> <svg xmlns="http://www.w3.org/2000/svg" class="mt-4 h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12" />
                                        </svg></button>
                                        </form>
                                    </div>
                                  
                                   @elseif ($a->status === 'completed')
    <div x-data="{ showFeedback{{ $a->id }}: false, rating{{ $a->id }}: {{ $a->feedback->rating ?? 0 }} }" class="space-y-2">
        <button 
            @click="showFeedback{{ $a->id }} = !showFeedback{{ $a->id }}" 
            class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1"
        >
            @if ($a->feedback)<!-- Star Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.974a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.285-3.974z" />
            </svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.974a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.285-3.974z" />
            </svg>
            @endif
        </button>

        <!-- Feedback Section -->
        <div x-show="showFeedback{{ $a->id }}" x-transition class="space-y-2 text-sm">
            @if ($a->feedback)
                <!-- Read-only View -->
                <div class="flex space-x-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg 
                            class="h-6 w-6 {{ $i <= $a->feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.974a1 1 0 00-.364-1.118L2.047 9.4c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.285-3.974z" />
                        </svg>
                    @endfor
                </div>
                <div class="border border-gray-300 rounded p-2 bg-gray-50">
                    <p class="text-gray-700">{{ $a->feedback->message }}</p>
                </div>
            @else
                <!-- Editable Form -->
                <form method="POST" action="{{ route('createfeedback') }}" class="space-y-2 text-sm">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $a->patient_id }}">
                    <input type="hidden" name="appointment_id" value="{{ $a->id }}">
                    <input type="hidden" name="rating" :value="rating{{ $a->id }}">

                    <div class="flex space-x-1">
                        <template x-for="i in 5">
                            <svg 
                                :class="i <= rating{{ $a->id }} ? 'text-yellow-400' : 'text-gray-300'" 
                                @click.prevent="rating{{ $a->id }} = i"
                                xmlns="http://www.w3.org/2000/svg" 
                                class="h-6 w-6 cursor-pointer transition-colors" 
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.974a1 1 0 00-.364-1.118L2.047 9.4c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.285-3.974z" />
                            </svg>
                        </template>
                    </div>

                    <textarea name="feedback" rows="3" class="w-full border border-gray-300 rounded p-2" placeholder="Write your feedback..."></textarea>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                        Submit
                    </button>
                </form>
            @endif
        </div>
    </div>
@endif


                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    </div>

    <!-- Modal -->
    <div x-show="show" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak>
        <div class="bg-white w-[90%] max-w-4xl p-6 rounded-lg relative shadow-xl overflow-y-auto max-h-[90vh]">
            <button @click="show = false" class="absolute top-2 right-4 text-gray-500 text-xl">&times;</button>
            <div id="reschedule-form-container" x-show="appointmentId !== null">
                <form method="POST" :action=" '/appointment/' + appointmentId + '/reschedule/' ">

                    @csrf
                    @method('PUT')

                    <!-- Hidden Inputs -->
                    <input type="hidden" id="appointment_date" name="date">
                    <input type="hidden" id="appointment_time" name="time">


                    <!-- Date and Time -->
                    <div class="w-full bg-[#FBF9F9] mb-8 h-[400px] p-5 rounded-lg" id="datetime-section">
                        <h3 class="font-semibold text-xl">Date & Time</span></h3>
                        <div class="flex p-4">
                            <div class="w-[40%]">
                                <input id="flat-calendar" type="text" hidden>
                                <div id="calendar-container"></div>
                            </div>
                            <div class="flex items-center justify-center">
                                <div id="slot-container" class="flex flex-wrap gap-3 justify-center"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Choose Instructor</label>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($staff as $s)
                            @if($s->position == "Instructor")
                                <label class="flex items-center space-x-2 border p-2 rounded cursor-pointer">
                                    <input type="radio" name="staff_id" value="{{ $s->id }}" :checked="selectedStaff == {{ $s->id }} ? true : false">
                                    <span>{{ $s->name }}</span>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-right mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Reschedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    let previouslySelectedBtn = null;

   function appointmentModal() {
    return {

        show: false,
        appointmentId: null,
        selectedDate: '',
        selectedTime: '',
        selectedStaff: null,

        openModal(id) {
            const appointment = @json($appointment).find(a => a.id === id);
            this.show = true;
            this.appointmentId = id;
            this.selectedDate = appointment.date;
            this.selectedTime = appointment.time;
            this.selectedStaff = appointment.staff_id;

            document.getElementById('appointment_date').value = this.selectedDate;
            document.getElementById('appointment_time').value = this.selectedTime;

            // Set date in calendar
            flatpickrInstance.setDate(this.selectedDate, true);

            // Manually trigger slot fetch ONCE
            this.fetchSlots(this.selectedDate);
        },
        
fetchSlots(date) {
    const selectedFetchDate = date; // ✅ store the date locally

    fetch(`/get-slots?date=${selectedFetchDate}`)
        .then(response => response.json())
        .then(data => {
            const slotContainer = document.getElementById("slot-container");
            slotContainer.innerHTML = "";
            let selectedTime = document.getElementById('appointment_time').value.slice(0, 5);
            let activeBtn = null;

            data.allSlots.forEach(slot => {
                const isBooked = data.bookedSlots.includes(slot);
                const btn = document.createElement("button");
                btn.type = "button";
                btn.innerText = slot;
                btn.classList.add("px-14", "py-2", "rounded-lg");

                const isExistingTime = slot === selectedTime;

                if (isBooked) {
                    if (isExistingTime) {
                        btn.className = "bg-blue-500  text-white px-14 py-2 rounded-lg";
                        activeBtn = btn;
                    } else {
                        btn.className = "bg-gray-400 bg-opacity-50 text-white px-14 py-2 rounded-lg cursor-not-allowed";
                        btn.disabled = true;
                    }
                } else {
                     if (isExistingTime) {
                        btn.className = "bg-blue-500 text-white px-14 py-2 rounded-lg";
                        activeBtn = btn;
                    } else{
                    btn.className = "bg-transparent text-black px-14 py-2 rounded-lg border-2 hover:bg-gray-300";
                    }
                    btn.addEventListener("click", () => {
                        document.getElementById("appointment_time").value = slot;
                        document.getElementById("appointment_date").value = selectedFetchDate; 

                        if (activeBtn) {
                            activeBtn.classList.remove("bg-blue-500", "text-white");
                            activeBtn.classList.add("bg-transparent", "text-black", "border-2");
                        }

                        btn.classList.remove("bg-transparent", "text-black", "border-2");
                        btn.classList.add("bg-blue-500", "text-white");
                        activeBtn = btn;
                    });
                }

                slotContainer.appendChild(btn);
            });
        })
        .catch(error => {
            console.error('Error fetching slots:', error);
        });
}

    };
}


    let flatpickrInstance;
    document.addEventListener('DOMContentLoaded', function () {
        const unavailableDates = @json($unavailableDates);
        let activeBtn = null;
        flatpickrInstance = flatpickr("#flat-calendar", {
            inline: true,
            minDate: "today",
            appendTo: document.getElementById("calendar-container"),
            disable: [
                ...unavailableDates,
                function (date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ]
        });

        document.getElementById('flat-calendar').addEventListener('change', function () {
            const selectedDate = this.value;

            fetch(`/get-slots?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    const slotContainer = document.getElementById("slot-container");
                   slotContainer.innerHTML = ""; 
                    console.log("Generating slots for:", selectedDate);
                    data.allSlots.forEach(slot => {
                        const isBooked = data.bookedSlots.includes(slot);
                        const btn = document.createElement("button");
                        btn.type = "button";
                        btn.innerText = slot;
                        console.log(slot);

                        const selected = document.getElementById('appointment_time').value.slice(0, 5); 

                        if (isBooked) {

                            if (slot === selected) {
                                btn.className = "bg-blue-500 text-white px-14 py-2 rounded-lg";
                                activeBtn = btn;
                            } else {
                                btn.className = "bg-gray-400 bg-opacity-50 text-white px-14 py-2 rounded-lg cursor-not-allowed";
                                btn.disabled = true;
                            }
                        } else {
                             if (slot === selected) {
                                btn.className = "bg-blue-500 text-white px-14 py-2 rounded-lg";
                                activeBtn = btn;
                            }else{
                            btn.className = "bg-transparent text-black px-14 py-2 rounded-lg border-2 hover:bg-gray-300";
                            }
                           btn.addEventListener("click", () => {
                            console.log('hi');
                                document.getElementById("appointment_time").value = slot;
                                document.getElementById("appointment_date").value = selectedDate;

                                if (activeBtn) {
                                    activeBtn.classList.remove("bg-blue-500", "text-white", );
                                    activeBtn.classList.add("bg-transparent", "text-black", "border-2");
                                }

                                btn.classList.remove("bg-transparent", "text-black", "border-2");
                                btn.classList.add("bg-blue-500", "text-white");
                                activeBtn = btn;
                            });

                        }

                        slotContainer.appendChild(btn);
                    });
                })
                .catch(error => {
                    console.error('Error fetching slots:', error);
                });
        });
    });
</script>

</x-app-layout>
