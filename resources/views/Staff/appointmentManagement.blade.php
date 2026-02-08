@extends('layouts.layoutS')

@section('content')
<div x-data="rescheduleModal()">
    <div class="m-3 -p-6">
        <h2 class="text-2xl font-bold m-5">Appointment Management</h2>
        <div class=" w-[90%] h-auto m-auto my-10 gap-3 flex flex-col justify-start items-center">
            <div class="w-full min-h-[120px] m-4 rounded-3xl bg-white flex items-center justify-center">
                <form method="GET" action="{{ route('appoinmentmanagement') }}" class="m-auto flex flex-wrap justify-center items-center gap-4" id="appointment-filter-form">
                    <input type="text" name="appointment_id" value="{{ request('appointment_id') }}" class="p-2 border rounded" placeholder="Search by Appointment ID">
                    <input type="date" name="date" value="{{ request('date') }}" class="p-2 border rounded">

                    <select name="service_id" class="p-2 border rounded">
                        <option value="">-- All Services --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="staff_id" class="p-2 border rounded">
                        <option value="">-- All Staff --</option>
                        @foreach ($staff as $staff)
                        @if($staff->position == "Instructor")
                            <option value="{{ $staff->staffID }}" {{ request('staff_id') == $staff->staffID ? 'selected' : '' }}>
                                {{ $staff->user->name }}
                            </option>
                            @endif
                        @endforeach
                    </select>

                    <select name="status" class="p-2 border rounded">
                        <option value="">-- All Status --</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
                    <a href="{{ route('appoinmentmanagement') }}" class="px-4 py-2 bg-gray-300 text-black rounded" id="appointment-clear">Clear</a>
                </form>
            </div>
        <div class="w-full h-auto m-4 rounded-3xl bg-white flex items-center justify-center">
            <table class="my-6 w-[90%] table-auto text-center border-separate border-spacing-y-4">
                <thead class="text-[#10859F] text-md font-semibold">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Service</th>
                        <th>Staff</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="appointments-tbody" class="text-sm text-gray-700">
                    @include('Staff.partials.appointment_table_rows', ['appointments' => $appointments])
                </tbody>
            </table>
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

                    <div class="w-full bg-[#FBF9F9] mb-8 h-[450px] p-5 rounded-lg" id="datetime-section">
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
                        <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Status</label>
                        <select name="status" class="w-full border rounded-lg p-2">
                            <option value="upcoming">Upcoming</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    </div>          
                    <div class="text-right mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Reschedule</button>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection

@push('scripts')
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
const unavailableDates = @json($unavailableDates ?? []);
document.addEventListener('DOMContentLoaded', function(){
    flatpickrInstance = flatpickr("#flat-calendar", {
        inline: true,
        minDate: "today",
        disable: unavailableDates,
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
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('appointment-filter-form');
    if (!form) return;

    const tbody = document.getElementById('appointments-tbody');
    const debounceMs = 400;
    let timer = null;
    let controller = null;

    const fetchResults = () => {
        const params = new URLSearchParams(new FormData(form));
        const url = form.action + '?' + params.toString();
        history.replaceState(null, '', url);

        if (controller) controller.abort();
        controller = new AbortController();

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal })
            .then(res => res.text())
            .then(html => {
                tbody.innerHTML = html;
                if (window.Alpine && typeof Alpine.initTree === 'function') {
                    Alpine.initTree(tbody);
                }
            })
            .catch(err => {
                if (err.name !== 'AbortError') console.error(err);
            });
    };

    const scheduleFetch = (immediate = false) => {
        clearTimeout(timer);
        timer = setTimeout(fetchResults, immediate ? 0 : debounceMs);
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        scheduleFetch(true);
    });

    form.querySelectorAll('input, select, textarea').forEach((el) => {
        const type = (el.getAttribute('type') || '').toLowerCase();
        if (type === 'text' || type === 'search' || el.tagName === 'TEXTAREA') {
            el.addEventListener('input', () => scheduleFetch(false));
        } else {
            el.addEventListener('change', () => scheduleFetch(true));
        }
    });

    const clearLink = document.getElementById('appointment-clear');
    if (clearLink) {
        clearLink.addEventListener('click', (e) => {
            e.preventDefault();
            form.reset();
            scheduleFetch(true);
        });
    }
});
</script>
@endpush
