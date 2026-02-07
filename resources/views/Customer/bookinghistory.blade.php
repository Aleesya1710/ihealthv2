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
                                                <div class="flex gap-1">
                                                    <button 
                                                        @click="openModal({{ $a->id }}, '{{ $a->date }}', '{{ \Carbon\Carbon::parse($a->time)->format('H:i') }}', {{ $a->staff_id }})" 
                                                        class="text-yellow-600 hover:text-yellow-800 text-sm flex items-center gap-1"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l3 3L14 13H11v-3l7.5-7.5z" />
                                                        </svg>
                                                    </button>
                                                    <form method="POST" action="/appointment/{{ $a->id }}/cancel" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-4 h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </form>
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

        <!-- Reschedule Modal -->
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

                    <div class="mb-4">
                        <h3 class="font-semibold text-xl">Instructor</h3>
                        <div class="grid grid-cols-2 gap-4" id="instructor-container">
                            @foreach ($staff as $s)
                                @if($s->position == "Instructor")
                                    <label class="cursor-pointer h-60 rounded-lg shadow hover:shadow-lg border transition-all duration-200 instructor-card">
                                        <input type="radio" value="{{ $s->staffID }}" class="hidden peer instructor-radio" x-model="selectedStaff">
                                        <div class="flex flex-col items-center peer-checked:text-white peer-checked:bg-blue-500 peer-checked:border-blue-400 peer-checked:ring-2 peer-checked:ring-blue-300 rounded-lg p-4 h-full">
                                            <img src="{{ asset('image/logo.jpg') }}" alt="" class="h-20 mb-4">
                                            <h3 class="text-lg font-semibold mt-7">{{ $s->user->name }}</h3>
                                            <p class="text-sm peer-checked:text-gray-200 text-gray-600">{{ $s->position ?? 'Instructor' }}</p>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
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
// Store Alpine component reference globally
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
            // Store reference to this component
            rescheduleComponent = this;
            
            // Add event listener for instructor selection
            this.$nextTick(() => {
                this.setupInstructorListeners();
            });
        },

        setupInstructorListeners() {
            document.querySelectorAll(".instructor-radio").forEach(input => {
                input.addEventListener("change", function() {
                    // Reset all instructor cards to default state
                    document.querySelectorAll(".instructor-radio").forEach(radio => {
                        const card = radio.closest("label").querySelector("div");
                        card.classList.remove("bg-blue-500", "text-white", "border-blue-400", "ring-2", "ring-blue-300");
                        
                        // Reset text colors
                        const name = card.querySelector("h3");
                        const position = card.querySelector("p");
                        if (name) name.classList.remove("text-white");
                        if (position) {
                            position.classList.remove("text-white", "text-gray-200");
                            position.classList.add("text-gray-600");
                        }
                    });

                    // Highlight selected instructor card
                    if (this.checked) {
                        const selectedCard = this.closest("label").querySelector("div");
                        selectedCard.classList.add("bg-blue-500", "text-white", "border-blue-400", "ring-2", "ring-blue-300");
                        
                        // Update text colors for selected card
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
                
                // Highlight pre-selected instructor
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
                    
                    // Normalize bookedStaff keys to HH:mm format
                    this.bookedStaff = {};
                    for (let key in data.bookedStaff) {
                        let normalizedKey = key.slice(0, 5); // "13:00"
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
        // Normalize slot to HH:mm format
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

        // Pre-select the current time slot - check both normalized and original
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

            // Reset all buttons to default state
            container.querySelectorAll("button").forEach(b => {
                b.classList.remove("bg-blue-500", "text-white");
                b.classList.add("bg-transparent", "text-black", "border");
            });
            
            // Highlight selected button
            btn.classList.remove("bg-transparent", "text-black", "border");
            btn.classList.add("bg-blue-500", "text-white");

            this.updateInstructorOptions(staffBooked);
        });

        container.appendChild(btn);
    });

    // Update instructor options for pre-selected time
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
            // Use the global reference to the Alpine component
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