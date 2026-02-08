<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="patient_type" :value="__('Patient Type')" />
            <select
                id="patient_type"
                name="patient_type"
                class="mt-1 block w-full"
                onchange="toggleCategoryFields()"
            >
                <option value="">-- Select Type --</option>
                <option value="student" {{ old('patient_type', optional($user->customer)->category) === 'student' ? 'selected' : '' }}>Student</option>
                <option value="staff" {{ old('patient_type', optional($user->customer)->category) === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="public" {{ old('patient_type', optional($user->customer)->category) === 'public' ? 'selected' : '' }}>Public</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('patient_type')" />
        </div>

        <div>
            <x-input-label for="contact_number" :value="__('Contact Number')" />
            <x-text-input
                id="contact_number"
                name="contact_number"
                type="text"
                class="mt-1 block w-full"
                :value="old('contact_number', optional($user->customer)->phoneNumber)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
        </div>

        <div>
            <x-input-label for="ic_number" :value="__('IC Number')" />
            <x-text-input
                id="ic_number"
                name="ic_number"
                type="text"
                class="mt-1 block w-full"
                :value="old('ic_number', optional($user->customer)->ICNumber)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('ic_number')" />
        </div>

        <div
            id="student_id_container"
            style="display: {{ old('patient_type', optional($user->customer)->category) === 'student' ? 'block' : 'none' }};"
        >
            <x-input-label for="student_id" :value="__('Student ID')" />
            <x-text-input
                id="student_id"
                name="student_id"
                type="text"
                class="mt-1 block w-full"
                :value="old('student_id', optional($user->customer)->studentID)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
        </div>

        <div
            id="staff_id_container"
            style="display: {{ old('patient_type', optional($user->customer)->category) === 'staff' ? 'block' : 'none' }};"
        >
            <x-input-label for="staff_id" :value="__('Staff ID')" />
            <x-text-input
                id="staff_id"
                name="staff_id"
                type="text"
                class="mt-1 block w-full"
                :value="old('staff_id', optional($user->customer)->staffID)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('staff_id')" />
        </div>

        <div
            id="faculty_container"
            style="display: {{ in_array(old('patient_type', optional($user->customer)->category), ['student','staff']) ? 'block' : 'none' }};"
        >
            <x-input-label for="faculty" :value="__('Faculty')" />
            <x-text-input
                id="faculty"
                name="faculty"
                type="text"
                class="mt-1 block w-full"
                :value="old('faculty', optional($user->customer)->faculty)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('faculty')" />
        </div>

        <div
            id="program_container"
            style="display: {{ in_array(old('patient_type', optional($user->customer)->category), ['student','staff']) ? 'block' : 'none' }};"
        >
            <x-input-label for="program" :value="__('Program')" />
            <x-text-input
                id="program"
                name="program"
                type="text"
                class="mt-1 block w-full"
                :value="old('program', optional($user->customer)->program)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('program')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
<script>
function toggleCategoryFields() {
    const type = document.getElementById('patient_type').value;

    const studentContainer = document.getElementById('student_id_container');
    const staffContainer = document.getElementById('staff_id_container');
    const facultyContainer = document.getElementById('faculty_container');
    const programContainer = document.getElementById('program_container');

    const studentInput = document.getElementById('student_id');
    const staffInput = document.getElementById('staff_id');
    const facultyInput = document.getElementById('faculty');
    const programInput = document.getElementById('program');

    if (type === 'student') {
        studentContainer.style.display = 'block';
        staffContainer.style.display = 'none';

        facultyContainer.style.display = 'block';
        programContainer.style.display = 'block';

        studentInput?.removeAttribute('disabled');
        facultyInput?.removeAttribute('disabled');
        programInput?.removeAttribute('disabled');
        staffInput?.setAttribute('disabled', true);
        staffInput && (staffInput.value = '');

    } else if (type === 'staff') {
        studentContainer.style.display = 'none';
        staffContainer.style.display = 'block';

        facultyContainer.style.display = 'block';
        programContainer.style.display = 'block';

        staffInput?.removeAttribute('disabled');
        facultyInput?.removeAttribute('disabled');
        programInput?.removeAttribute('disabled');
        studentInput?.setAttribute('disabled', true);
        studentInput && (studentInput.value = '');

    } else {
        studentContainer.style.display = 'none';
        staffContainer.style.display = 'none';
        facultyContainer.style.display = 'none';
        programContainer.style.display = 'none';

        [studentInput, staffInput, facultyInput, programInput].forEach(input => {
            if (input) {
                input.setAttribute('disabled', true);
                input.value = '';
            }
        });
    }
}

window.addEventListener('DOMContentLoaded', toggleCategoryFields);
</script>

