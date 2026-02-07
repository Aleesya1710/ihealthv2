@extends('layouts.layoutS')

@section('content')
<div x-data="{ editModal: false, selected: {} }">
    <div class="m-3 -p-6">
        <h2 class="text-2xl font-bold m-5">Appointment Management</h2>
        <div class=" w-[90%] h-auto m-auto my-10 gap-3 flex flex-col justify-start items-center">
            <div class="w-full min-h-[120px] m-4 rounded-3xl bg-white flex items-center justify-center">
                <form method="GET" action="{{ route('appoinmentmanagement') }}" class="m-auto flex flex-wrap justify-center items-center gap-4">
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
                            <option value="{{ $staff->staffID }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
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
                    <a href="{{ route('appoinmentmanagement') }}" class="px-4 py-2 bg-gray-300 text-black rounded">Clear</a>
                </form>
            </div>
             <!-- Appointment Table -->
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
                <tbody class="text-sm text-gray-700">
                    @foreach ($appointments as $appointment)
  
                        <tr class="bg-[#F7FAFC] hover:bg-[#E2EDF0] transition-all rounded-lg">
                            <td>{{ $appointment->id ?? '-' }}</td>
                            <td>{{ $appointment->patientRecord->customer->user->name ?? '-' }}</td>
                            <td>{{ $appointment->service->name ?? '-' }}</td>
                            <td>{{ $appointment->staff->user->name ?? '-' }}</td>
                            <td>{{ $appointment->date }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                            <td>{{ ucfirst($appointment->status) }}</td>
                            <td class="flex justify-center gap-3">
                                <!-- Update icon -->
                                <a href="#" @click.prevent="editModal = true; selected = {{ $appointment->toJson() }}" class="text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l3 3L14 13H11v-3l7.5-7.5z" />
                                    </svg>
                                </a>

                                <!-- Cancel icon -->
                                <form action="{{ route('appointment.cancel', $appointment->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Cancel" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 ">
        <div class="bg-white p-6 shadow-md w-full max-w-md rounded-xl">
            <h2 class="text-xl font-semibold mb-4">Reschedule Appointment</h2>
            <form :action="`/appointments/${selected.id}/update`" method="POST">
                @csrf
                @method('POST')


               
                <!-- Date -->
                <label class="block mb-2">Date:</label>
                <input type="date" name="date" x-model="selected.date" class="w-full border p-2 rounded mb-4">

                <!-- Time -->
                <label class="block mb-2">Time:</label>
                <input type="time" name="time" x-model="selected.time" class="w-full border p-2 rounded mb-4">

                <!-- Status -->
                <label class="block mb-2">Status:</label>
                <select name="status" x-model="selected.status" class="w-full border p-2 rounded mb-4">
                    <option value="upcoming">Upcoming</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>

                </select>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush