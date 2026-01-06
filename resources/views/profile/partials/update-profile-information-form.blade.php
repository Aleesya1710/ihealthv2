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
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 ">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 ">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
         <div>
            <x-input-label for="patient_type" :value="__('Patient Type')" />
            <select id="patient_type" name="patient_type" class="mt-1 block w-full" onchange="toggleStudentId()">
                <option value="">-- Select Type --</option>
                <option value="Student" {{ old('patient_type', $patient->patient_type) == 'Student' ? 'selected' : '' }}>Student</option>
                <option value="Staff" {{ old('patient_type', $patient->patient_type) == 'Staff' ? 'selected' : '' }}>Staff</option>
                <option value="Public" {{ old('patient_type', $patient->patient_type) == 'Public' ? 'selected' : '' }}>Public</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('patient_type')" />
        </div>

         <div>
            <x-input-label for="contact_number" :value="__('Contact Number')" />
            <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number', $patient->contact_number)" required autofocus autocomplete="contact_number" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
         <div>
            <x-input-label for="age" :value="__('Age')" />
            <x-text-input id="age" name="age" type="text" class="mt-1 block w-full" :value="old('age', $patient->age)" required autofocus autocomplete="age" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
         <div>
            <x-input-label for="ic_number" :value="__('Ic Number')" />
            <x-text-input id="ic_number" name="ic_number" type="text" class="mt-1 block w-full" :value="old('ic_number', $patient->ic_number)" required autofocus autocomplete="ic_number" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="mt-1 block w-full">
                <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>
        <div id="student_id_container">
            <x-input-label for="student_id" :value="__('Student ID')" />
            <x-text-input id="student_id" name="student_id" type="text" class="mt-1 block w-full"
                :value="old('student_id', $patient->student_id)" autocomplete="student_id" />
            <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 "
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
<script>
    function toggleStudentId() {
        const type = document.getElementById('patient_type').value;
        const studentIdField = document.getElementById('student_id');
        const studentIdContainer = document.getElementById('student_id_container');

        if (type === 'Student') {
            studentIdField.removeAttribute('disabled');
            studentIdContainer.style.display = 'block';
        } else {
            studentIdField.setAttribute('disabled', true);
            studentIdContainer.style.display = 'none';
            studentIdField.value = '';
        }
    }

    // Run on page load in case of old input
    window.addEventListener('DOMContentLoaded', toggleStudentId);
</script>

