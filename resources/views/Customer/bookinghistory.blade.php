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
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div x-data="rescheduleModal()" id="reschedule-component">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="max-w-7xl mx-auto bg-white py-8 px-5 mt-4 rounded-lg">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">
                    Appointment History
                </h2>

                @if($appointment->isEmpty())
                    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">
                        You have no Appointment yet.
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
                                        <td class="py-3 px-4 border-b align-middle">
                                            {{ $a->staff_id ? ($staff->firstWhere('staffID', $a->staff_id)?->user->name ?? 'Unavailable') : 'Pending Assignment' }}
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
                                      <div class="flex items-center gap-2 leading-none">
                                            <button
                                                @click="openModal({{ $a->id }}, '{{ $a->date }}', '{{ \Carbon\Carbon::parse($a->time)->format('H:i') }}', {{ $a->staff_id ?? 'null' }})"
                                                class="inline-flex items-center justify-center text-yellow-600 hover:text-yellow-800 leading-none"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l3 3L14 13H11v-3l7.5-7.5z" />
                                                </svg>
                                            </button>
                                            <form method="POST" action="/appointment/{{ $a->id }}/cancel" onsubmit="return confirm('Are you sure?')" class="inline-flex items-center m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center justify-center text-red-600 hover:text-red-800 leading-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block align-middle" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                            @elseif ($a->status === 'completed')
                                                <div x-data="{ showFeedback: false, rating: {{ $a->feedback->rating ?? 0 }} }" class="space-y-2">
                                                    <button
                                                        @click="showFeedback = !showFeedback"
                                                        class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1"
                                                    >
                                                        @if ($a->feedback)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.974a1 1 0 00-.364-1.118L2.047 9.4c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.285-3.974z" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.974a1 1 0 00-.364-1.118L2.047 9.4c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.285-3.974z" />
                                                            </svg>
                                                        @endif
                                                    </button>

                                                    <div x-show="showFeedback" x-transition class="space-y-2 text-sm">
                                                        @if ($a->feedback)
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
                                                            <form method="POST"
                                                            action="{{ route('createfeedback') }}"
                                                            class="space-y-2 text-sm"
                                                            x-data="{ rating: 0, error: false }"
                                                            @submit.prevent="
                                                                if (rating === 0) {
                                                                    error = true;
                                                                } else {
                                                                    error = false;
                                                                    $el.submit();
                                                                }
                                                            ">
                                                            @csrf

                                                            <input type="hidden" name="user_id" value="{{ $a->patient_id }}">
                                                            <input type="hidden" name="appointment_id" value="{{ $a->id }}">
                                                            <input type="hidden" name="rating" :value="rating">

                                                            <div>
                                                                <div class="flex space-x-1">
                                                                    <template x-for="i in 5" :key="i">
                                                                        <svg
                                                                            @click="rating = i; error = false"
                                                                            :class="i <= rating ? 'text-yellow-400' : 'text-gray-300'"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            class="h-6 w-6 cursor-pointer transition-colors duration-200"
                                                                            fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path
                                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.974c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.197-1.539-1.118l1.285-3.974a1 1 0 00-.364-1.118L2.047 9.4c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.285-3.974z"/>
                                                                        </svg>
                                                                    </template>
                                                                </div>

                                                                <p x-show="error"
                                                                x-transition
                                                                class="text-red-500 text-xs mt-1">
                                                                    Please select a rating ⭐
                                                                </p>
                                                            </div>

                                                            <textarea required
                                                                    name="feedback"
                                                                    rows="3"
                                                                    class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-200"
                                                                    placeholder="Write your feedback..."></textarea>

                                                            <button type="submit"
                                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
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

        <div x-show="show" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-[90%] max-w-4xl p-6 rounded-lg relative shadow-xl overflow-y-auto max-h-[90vh]">
                <button @click="show = false" class="absolute top-2 right-4 text-gray-500 text-xl">&times;</button>
                
                <form :action="'/appointment/' + appointmentId + '/reschedule/'" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="appointment_date" id="reschedule_date">
                    <input type="hidden" name="appointment_time" id="reschedule_time">
                    <input type="hidden" name="staff_id" x-model="selectedStaff">

                    <div class="w-full bg-[#FBF9F9] mb-8 h-[400px] p-5 rounded-lg" id="datetime-section">
                        <h3 class="font-semibold text-xl">Date & Time</h3>
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
                    <div class="text-right mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Reschedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
let rescheduleComponent = null;

function rescheduleModal() {
    return {
        show: false,
        appointmentId: null,
        selectedDate: null,
        selectedTime: null,
        selectedStaff: null,
        bookedStaff: {},

        init() {
            rescheduleComponent = this;
            
            this.$nextTick(() => {
                this.setupInstructorListeners();
            });
        },

        setupInstructorListeners() {
            document.querySelectorAll(".instructor-radio").forEach(input => {
                input.addEventListener("change", function() {
                    document.querySelectorAll(".instructor-radio").forEach(radio => {
                        const card = radio.closest("label").querySelector("div");
                        card.classList.remove("bg-blue-500", "text-white", "border-blue-400", "ring-2", "ring-blue-300");
                        
                        const name = card.querySelector("h3");
                        const position = card.querySelector("p");
                        if (name) name.classList.remove("text-white");
                        if (position) {
                            position.classList.remove("text-white", "text-gray-200");
                            position.classList.add("text-gray-600");
                        }
                    });

                    if (this.checked) {
                        const selectedCard = this.closest("label").querySelector("div");
                        selectedCard.classList.add("bg-blue-500", "text-white", "border-blue-400", "ring-2", "ring-blue-300");
                        
                        const name = selectedCard.querySelector("h3");
                        const position = selectedCard.querySelector("p");
                        if (name) name.classList.add("text-white");
                        if (position) {
                            position.classList.remove("text-gray-600");
                            position.classList.add("text-gray-200");
                        }
                    }
                });
            });
        },

        openModal(id, date, time, staffId) {
            this.show = true;
            this.appointmentId = id;
            this.selectedDate = date;
            this.selectedTime = time;
            this.selectedStaff = staffId;

            document.getElementById('reschedule_date').value = date;
            document.getElementById('reschedule_time').value = time;

            this.$nextTick(() => {
                if (flatpickrInstance) {
                    flatpickrInstance.setDate(date, true);
                }
                this.fetchSlots(date);
                this.setupInstructorListeners();
                
                setTimeout(() => {
                    this.highlightSelectedInstructor(staffId);
                }, 100);
            });
        },

        highlightSelectedInstructor(staffId) {
            document.querySelectorAll(".instructor-radio").forEach(radio => {
                if (Number(radio.value) === Number(staffId)) {
                    const card = radio.closest("label").querySelector("div");
                    card.classList.add("bg-blue-500", "text-white", "border-blue-400", "ring-2", "ring-blue-300");
                    
                    const name = card.querySelector("h3");
                    const position = card.querySelector("p");
                    if (name) name.classList.add("text-white");
                    if (position) {
                        position.classList.remove("text-gray-600");
                        position.classList.add("text-gray-200");
                    }
                }
            });
        },

        fetchSlots(date) {
            fetch(`/get-slots?date=${date}`)
                .then(res => res.json())
                .then(data => {
                    console.log("Raw bookedStaff data:", data.bookedStaff);
                    
                    this.bookedStaff = {};
                    for (let key in data.bookedStaff) {
                        let normalizedKey = key.slice(0, 5); 
                        this.bookedStaff[normalizedKey] = data.bookedStaff[key];
                    }
                    
                    console.log("Normalized bookedStaff:", this.bookedStaff);
                    this.renderSlots(data.allSlots || []);
                })
                .catch(err => console.error('Error fetching slots:', err));
        },

        renderSlots(slots) {
    const container = document.getElementById("slot-container");
    container.innerHTML = "";

    slots.forEach(slot => {
        const normalizedSlot = slot.slice(0, 5);
        const staffBooked = this.bookedStaff[normalizedSlot] || [];
        const isFullyBooked = staffBooked.length >= 2;

        console.log(`Slot: ${slot}, Normalized: ${normalizedSlot}, Booked staff:`, staffBooked, `Fully booked: ${isFullyBooked}`);

        const btn = document.createElement("button");
        btn.type = "button";
        btn.innerText = slot;
        btn.disabled = isFullyBooked;
        btn.className = isFullyBooked
            ? "bg-gray-400 text-white px-14 py-2 rounded-lg cursor-not-allowed"
            : "bg-transparent text-black px-14 py-2 rounded-lg border hover:bg-gray-300";

        const normalizedSelectedTime = this.selectedTime ? this.selectedTime.slice(0, 5) : null;
        if (normalizedSlot === normalizedSelectedTime || slot === this.selectedTime || normalizedSlot === this.selectedTime) {
            btn.classList.remove("bg-transparent", "text-black", "border", "hover:bg-gray-300");
            btn.classList.add("bg-blue-500", "text-white");
            console.log(`Pre-selected time slot: ${slot}`);
        }

        btn.addEventListener("click", () => {
            if(isFullyBooked) return;
            this.selectedTime = normalizedSlot;
            document.getElementById('reschedule_time').value = normalizedSlot;
            document.getElementById('reschedule_date').value = this.selectedDate;

            container.querySelectorAll("button").forEach(b => {
                b.classList.remove("bg-blue-500", "text-white");
                b.classList.add("bg-transparent", "text-black", "border");
            });
            
            btn.classList.remove("bg-transparent", "text-black", "border");
            btn.classList.add("bg-blue-500", "text-white");

            this.updateInstructorOptions(staffBooked);
        });

        container.appendChild(btn);
    });

    if (this.selectedTime) {
        const normalizedSelectedTime = this.selectedTime.slice(0, 5);
        if (this.bookedStaff[normalizedSelectedTime]) {
            console.log(`Pre-selecting instructors for time: ${normalizedSelectedTime}`, this.bookedStaff[normalizedSelectedTime]);
            this.updateInstructorOptions(this.bookedStaff[normalizedSelectedTime]);
        }
    }
},

        updateInstructorOptions(bookedIds) {
            const booked = bookedIds.map(id => Number(id));
            console.log("Updating instructor options, booked IDs:", booked);
            
            document.querySelectorAll(".instructor-radio").forEach(input => {
                const id = Number(input.value);
                const label = input.closest("label");
                
                console.log(`Instructor ID: ${id}, Is booked: ${booked.includes(id)}`);
                
                if(booked.includes(id)) {
                    input.disabled = true;
                    label.classList.add("opacity-50","cursor-not-allowed");
                } else {
                    input.disabled = false;
                    label.classList.remove("opacity-50","cursor-not-allowed");
                }
            });
        }
    }
}

let flatpickrInstance;
document.addEventListener('DOMContentLoaded', function(){
    flatpickrInstance = flatpickr("#flat-calendar", {
        inline: true,
        minDate: "today",
        appendTo: document.getElementById("calendar-container"),
        onChange: function(selectedDates, dateStr) {
            if (rescheduleComponent) {
                rescheduleComponent.selectedDate = dateStr;
                rescheduleComponent.selectedTime = null;
                document.getElementById('reschedule_time').value = "";
                document.getElementById('reschedule_date').value = dateStr;
                rescheduleComponent.fetchSlots(dateStr);
            }
        },
    });
});
</script>
