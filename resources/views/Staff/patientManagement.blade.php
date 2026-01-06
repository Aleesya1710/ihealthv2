@extends('layouts.layoutS')

@section('content')
<div x-data="{ editModal: false, selected: {} }">
    <div class="m-3 -p-6">
        <h2 class="text-2xl font-bold m-5">Patient Record Management</h2>
        <div class=" w-[90%] h-auto m-auto my-7 gap-3 flex flex-col justify-start items-center">
            <div class="w-full min-h-[100px] m-4 rounded-3xl bg-white  flex items-center justify-start">
               <form method="GET" action="{{ route('patientmanagement') }}" class="flex m-auto gap-2 ml-4">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search" class="border px-4 py-2 rounded-lg w-[300px]">
                     <select name="patient_type" class="border px-4 py-2 w-[300px] rounded-lg">
                        <option value="">All Types</option>
                        <option value="student" {{ request('patient_type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="fsr student" {{ request('patient_type') == 'fsr student' ? 'selected' : '' }}>FSR Student</option>
                        <option value="uitm staff" {{ request('patient_type') == 'uitm staff' ? 'selected' : '' }}>UiTM Staff</option>
                        <option value="public" {{ request('patient_type') == 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
                    <a href="{{ route('patientmanagement') }}" class="px-4 py-2 bg-gray-300 text-black rounded">Clear</a>
                </form>
            </div>
             <!-- Patient Table -->
        <div class="w-full h-auto m-4 rounded-3xl bg-white flex items-center justify-center">
            <table class="my-6 w-[90%] table-auto text-center border-separate border-spacing-y-4">
                <thead class="text-[#10859F] text-md font-semibold">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>IC no</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @foreach ($patientrecord as $patient)
                        <tr class="bg-[#F7FAFC] hover:bg-[#E2EDF0] transition-all rounded-lg">
                            <td>{{ $patient->id ?? '-' }}</td>
                            <td>{{ $patient->name ?? '-' }}</td>
                            <td>{{ $patient->ic_number ?? '-' }}</td>
                            <td>{{ $patient->patient_type ?? '-' }}</td>
                            <td class="flex justify-center gap-3">
                                <!-- Update icon -->
                               <a href="{{ route('patientRecord', $patient->user_id) }}" class="text-green-600 hover:text-green-800 ml-2" title="View Record">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                <!-- Cancel icon -->
                                <form action="{{ route('patient.delete', $patient->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this patient?');">
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
            <h2 class="text-xl font-semibold mb-4">Edit Appointment</h2>
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
                    <option value="done">Done</option>
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