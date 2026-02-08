@extends('layouts.layoutS')

@section('content')
<div x-data="{ editModal: false, selected: {} }">
    <div class="m-3 -p-6">
        <h2 class="text-2xl font-bold m-5">Patient Record Management</h2>
        <div class=" w-[90%] h-auto m-auto my-7 gap-3 flex flex-col justify-start items-center">
            <div class="w-full min-h-[100px] m-4 rounded-3xl bg-white  flex items-center justify-start">
               <form method="GET" action="{{ route('patientmanagement') }}" class="flex m-auto gap-2 ml-4" id="patient-filter-form">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search" class="border px-4 py-2 rounded-lg w-[300px]">
                     <select name="patient_type" class="border px-4 py-2 w-[300px] rounded-lg">
                        <option value="">All Types</option>
                        <option value="student" {{ request('patient_type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="staff" {{ request('patient_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="public" {{ request('patient_type') == 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
                    <a href="{{ route('patientmanagement') }}" class="px-4 py-2 bg-gray-300 text-black rounded" id="patient-clear">Clear</a>
                </form>
            </div>
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
                <tbody id="patients-tbody" class="text-sm text-gray-700">
                    @include('Staff.partials.patient_table_rows', ['patientrecord' => $patientrecord])
                </tbody>
            </table>
        </div>
    </div>
    <div x-show="editModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 ">
        <div class="bg-white p-6 shadow-md w-full max-w-md rounded-xl">
            <h2 class="text-xl font-semibold mb-4">Edit Appointment</h2>
            <form :action="`/appointments/${selected.id}/update`" method="POST">
                @csrf
                @method('POST')

            

               
                <label class="block mb-2">Date:</label>
                <input type="date" name="date" x-model="selected.date" class="w-full border p-2 rounded mb-4">

                <label class="block mb-2">Time:</label>
                <input type="time" name="time" x-model="selected.time" class="w-full border p-2 rounded mb-4">

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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('patient-filter-form');
    if (!form) return;

    const tbody = document.getElementById('patients-tbody');
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

    const clearLink = document.getElementById('patient-clear');
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
