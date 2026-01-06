@include('navigation.navbartop')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .flatpickr-current-month .numInputWrapper {
    display: none !important;
}
.flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year {
    font-size: 14px !important;
    font-family: 'Inter', sans-serif; 
    text-align: center;
}

.validation-warning {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 16px;
    display: none;
}

.validation-warning.show {
    display: block;
}

.field-error {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 1px #dc2626 !important;
}

.error-message {
    color: #dc2626;
    font-size: 12px;
    margin-top: 4px;
    display: none;
}

.error-message.show {
    display: block;
}

</style>
<x-app-layout>
    <section class=" py-12 px-6 ">
        <div id="validation-warning" class="validation-warning max-w-4xl mx-auto">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span id="validation-message">Please complete all required fields</span>
            </div>
        </div>

       <form action="{{ route('appointment.store') }}" method="POST" enctype="multipart/form-data" id="appointment-form">
            @csrf
            <input type="hidden" name="appointment_time" id="appointment_time" required>
            <input type="hidden" name="appointment_date" id="appointment_date" required>
            <input type="hidden" name="service_id" value="{{ $services->id }}" required>
            <input type="hidden" name="patient_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="patient_name" value="{{ Auth::user()->name }}">
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
            

        <div class=" flex flex-col gap-7 justify-center items-center">
            <div class="w-[85%] bg-[#FBF9F9] h-40 rounded-lg flex justify-center items-center gap-5">
                <div>
                    <p class="font-semibold">{{ $services->name }}</p>
                    <p class="text-lg text-gray-600">{{ $services->description }}</p>
                    <p class="text-sm text-gray-600">
                        RM{{ $services->price_student }} - RM{{ $services->price_public }} 
                </div>
            </div>
            
            <div class="w-[85%] bg-[#FBF9F9] h-[400px] p-5 rounded-lg" id="datetime-section">
                <h3 class="font-semibold text-xl">Date & Time <span class="text-red-500">*</span></h3>
                <div class="error-message" id="datetime-error">Please select both date and time</div>
                <div class="flex p-4">
                    <div class="w-[40%]">
                        <input id="flat-calendar" type="text" hidden>
                        <div id="calendar-container"></div>
                    </div>
                    <div class="flex items-center justify-center">
                        <div id="slot-container" class="flex flex-wrap gap-3 justify-center">
                        </div>
                    </div>
                </div>
            </div>
            
            @if ($services->id != 6)
            <div class="w-[85%] bg-[#FBF9F9] h-80 p-5 rounded-lg" id="instructor-section">
                <h3 class="font-semibold text-xl">Instructor <span class="text-red-500">*</span></h3>
                <div class="error-message" id="instructor-error">Please select an instructor</div>
                <div class="flex justify-center">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($staff as $staff)
                        @if($staff->position == "Instructor")
                            <label class="cursor-pointer h-60 rounded-lg shadow hover:shadow-lg border peer-checked:bg-gray-200 transition-all duration-200">
                                <input type="radio" name="staff_id" value="{{ $staff->id }}" class="hidden peer instructor-radio">
                                
                                <div class="flex flex-col items-center peer-checked:bg-gray-200 peer-checked:border-gray-400 peer-checked:ring-2 peer-checked:ring-gray-300 rounded-lg p-4 h-full">
                                    <img src="{{ asset('image/logo.jpg') }}" alt="" class="h-20 mb-4">
                                    <h3 class="text-lg font-semibold mt-7">{{ $staff->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $staff->specialty ?? 'Instructor' }}</p>
                                </div>
                            </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @php $selectedPatientType = old('patient_type', $patient->patient_type ?? ''); @endphp

            <div class="w-[85%] bg-[#FBF9F9] h-auto p-5 rounded-lg mb-10">
                <h3 class="font-semibold text-xl">Patient Detail</h3>
                <div class="flex flex-col gap-8 w-[90%] justify-center my-10 mx-auto">
                    <div class=" w-full gap-10 ">
                        <label for="ic_number" class="block text-sm mb-2 font-medium text-gray-700">Patient Type <span class="text-red-500">*</span></label>
                        <div class="flex w-full gap-10">
                        <label class="cursor-pointer w-1/4 ">
                            <input type="radio" name="patient_type" value="student" class="sr-only peer" {{ $selectedPatientType === 'student' ? 'checked' : '' }} required>
                            <div class="px-6 py-3 rounded-lg border text-center peer-checked:bg-gray-200  peer-checked:border-gray-400 transition">
                            Student
                            </div>
                        </label>

                        <label class="cursor-pointer w-1/4 ">
                            <input type="radio" name="patient_type" value="fsr student" class="sr-only peer" {{ $selectedPatientType === 'fsr student' ? 'checked' : '' }}>
                            <div class=" px-6 py-3 rounded-lg border text-center peer-checked:bg-gray-200  peer-checked:border-gray-400 transition">
                            FSR Student
                            </div>
                        </label>

                        <label class="cursor-pointer w-1/4 ">
                            <input type="radio" name="patient_type" value="uitm staff" class="sr-only peer" {{ $selectedPatientType === 'uitm staff' ? 'checked' : '' }}>
                            <div class="px-6 py-3 rounded-lg border text-center peer-checked:bg-gray-200  peer-checked:border-gray-400 transition">
                            UiTM Staff
                            </div>
                        </label>

                        <label class="cursor-pointer w-1/4 ">
                            <input type="radio" name="patient_type" value="public" class="sr-only peer" {{ $selectedPatientType === 'public' ? 'checked' : '' }}>
                            <div class="px-6 py-3 rounded-lg border text-center peer-checked:bg-gray-200  peer-checked:border-gray-400 transition">
                            Public
                            </div>
                        </label>
                    </div>
                    </div>
                    <div class="flex gap-10">
                        <div class="w-[50%]">
                            <label for="ic_number" class="block text-sm font-medium text-gray-700">IC Number <span class="text-red-500">*</span></label>
                            <input type="text" name="ic_number" id="ic_number" required value="{{ old('ic_number', $patient->ic_number ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-xl h-14 shadow-sm focus:ring-[#10859F] focus:border-[#10859F]">
                        </div>
                        <div class="w-[50%]" id="student-id-container">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">
                                Staff / Student ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $patient->student_id ?? '') }}"
                                class="mt-1 block w-full border-gray-300 rounded-xl h-14 shadow-sm focus:ring-[#10859F] focus:border-[#10859F] disabled:bg-gray-100 disabled:cursor-not-allowed">
                        </div>
                    </div>
                    <div class="flex gap-10">
                        <div class="w-[50%]">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Gender <span class="text-red-500">*</span></label>
                             <select name="gender" id="gender" required
                                    class="mt-1 block w-full border-gray-300 rounded-xl h-14 shadow-sm focus:ring-[#10859F] focus:border-[#10859F]">
                                 <option value="" disabled {{ !$patient ? 'selected' : '' }}>Select gender</option>
                                <option value="male" {{ old('gender', $patient->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $patient->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="w-[50%]">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone_number" id="phone_number" required  value="{{ old('phone_number', $patient->contact_number ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-xl h-14 shadow-sm focus:ring-[#10859F] focus:border-[#10859F]">
                        </div>
                    </div>
                    <div class="flex gap-10">
                       <div class="w-[20%]">
                            <label for="ic_number" class="block text-sm font-medium text-gray-700">Age <span class="text-red-500">*</span></label>
                            <input type="text" name="age" id="age" required  value="{{ old('age', $patient->age ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-xl h-14 shadow-sm focus:ring-[#10859F] focus:border-[#10859F]">
                        </div>
                    </div>
                    @if ($services->id != 6)
                    <div>
                        <label for="referral_letter" class="block text-sm font-medium text-gray-700">Referral Letter <span class="text-red-500">*</span></label>
                        <input type="file" name="referral_letter" id="referral_letter" required
                                accept="application/pdf"
                                class="mt-1 block w-full border-gray-300 rounded-xl h-14 shadow-sm focus:ring-[#10859F] focus:border-[#10859F]">
                    </div>
                    @endif
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="5"
                                    class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-[#10859F] focus:border-[#10859F]"></textarea>
                    </div>
                    <div class="w-full h-52 flex flex-col justify-center bg-gray-300 shadow-3xl mt-8 p-4 rounded-3xl shadow-sm">
                        <h3 class="mt-1 font-semibold text-lg">Booking policy:</h3>
                        <p> UNTUK MAKLUMAT LANJUT SILA BERHUBUNG DI TALIAN: 03-55442964. UNTUK TEMUJANJI BAHARU SILA PASTIKAN ANDA MEMPUNYAI SURAT RUJUKAN SEBELUM MEMULAKAN TEMPAHAN RAWATAN</p>
                        <h3 class="mt-2 font-semibold text-lg">Additional information:</h3>
                        <p>When booking with Sports & Wellness Clinic FSR, you may receive appointment-specific communication from iHealth portal. This includes confirmations via email.</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit"
                   class="bg-[#FBF9F9] text-black p-8 px-40 rounded-3xl hover:bg-[#0c3e4a] hover:text-white">
                   Book Now
                </button>
            </div>
        </div>
    </form>
    </section>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unavailableDates = @json($unavailableDates);
        flatpickr("#flat-calendar", {
            inline: true,
            minDate: "today",
            appendTo: document.getElementById("calendar-container"),
            disable: [
                ...unavailableDates, 
                function(date) {
                    return (date.getDay() === 0 || date.getDay() === 6); 
                }
            ]
        });
        let selectedSlot = null;
        let bookedStaff = {};
        document.getElementById('flat-calendar').addEventListener('change', function () {
            const selectedDate = this.value;

            fetch(`/get-slots?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    const slotContainer = document.getElementById("slot-container");
                    slotContainer.innerHTML = ""; 
                    bookedStaff = data.bookedStaff;

                    data.allSlots.forEach(slot => {
                        const staffBooked = bookedStaff[slot] || [];
                const isFullyBooked = staffBooked.length >= 2;
                        const btn = document.createElement("button");
                        btn.type = "button";
                        btn.innerText = slot;
                        
                        if (isFullyBooked) {
                            btn.className = "bg-gray-400 bg-opacity-50 text-white px-14 py-2 rounded-lg cursor-not-allowed";
                            btn.disabled = true;
                        } else {
                            btn.className = "bg-transparent text-black px-14 py-2 rounded-lg border-2 hover:bg-gray-300";
                        }
                        
                        btn.addEventListener("click", () => {
                              if (!isFullyBooked) {
                        selectedSlot = slot;
                        document.getElementById("appointment_time").value = slot;
                        document.getElementById("appointment_date").value = selectedDate;

                        document.getElementById('datetime-section').classList.remove('field-error');
                        document.getElementById('datetime-error').classList.remove('show');

                        document.querySelectorAll("#slot-container button:not(:disabled)").forEach(b => {
                            b.classList.remove("bg-[#353D3F]", "text-white");
                            b.classList.add("bg-transparent", "text-black");
                        });

                        btn.classList.remove("bg-transparent", "text-black");
                        btn.classList.add("bg-[#353D3F]", "text-white");

                        // Disable already-booked instructors
                        updateInstructorOptions(staffBooked);
                    }
                        });

                        slotContainer.appendChild(btn);
                    });
                })
                .catch(error => {
                    console.error('Error fetching slots:', error);
                });
        });
    });
    function updateInstructorOptions(bookedStaffIds) {
    document.querySelectorAll('.instructor-radio').forEach(input => {
        const label = input.closest("label");
        if (bookedStaffIds.includes(parseInt(input.value))) {
            input.disabled = true;
            label.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            input.disabled = false;
            label.classList.remove("opacity-50", "cursor-not-allowed");
        }
    });
}
    document.getElementById('appointment-form').addEventListener('submit', function (e) {
        const date = document.getElementById('appointment_date').value;
        const time = document.getElementById('appointment_time').value;
        const instructorSelected = document.querySelector('input[name="staff_id"]:checked');
        const serviceId = {{ $services->id }};
        
        let isValid = true;
        let errorMessages = [];
        
        clearValidationErrors();
        
        console.log('Form validation:', { date, time, instructorSelected, serviceId });

        if (!date || !time) {
            isValid = false;
            errorMessages.push('Please select both appointment date and time');
            showFieldError('datetime-section', 'datetime-error');
        }
        
        const instructorSection = document.getElementById('instructor-section');
        if (serviceId != 6 && instructorSection && !instructorSelected) {
            isValid = false;
            errorMessages.push('Please select an instructor');
            showFieldError('instructor-section', 'instructor-error');
        }
        
        // Show validation warnings if form is invalid
        if (!isValid) {
            e.preventDefault();
            showValidationWarning(errorMessages);
            scrollToFirstError();
            const form = this;
            form.classList.add('was-validated');
            
            return false;
        }
        hideValidationWarning();
    });

    function showValidationWarning(messages) {
        const warningDiv = document.getElementById('validation-warning');
        const messageSpan = document.getElementById('validation-message');
        
        if (messages.length === 1) {
            messageSpan.textContent = messages[0];
        } else {
            messageSpan.textContent = 'Please complete the following: ' + messages.join(', ');
        }
        
        warningDiv.classList.add('show');
        setTimeout(() => {
            hideValidationWarning();
        }, 5000);
    }
    
    function hideValidationWarning() {
        const warningDiv = document.getElementById('validation-warning');
        warningDiv.classList.remove('show');
    }
    
    function showFieldError(sectionId, errorId) {
        const section = document.getElementById(sectionId);
        const errorMsg = document.getElementById(errorId);
        
        if (section) section.classList.add('field-error');
        if (errorMsg) errorMsg.classList.add('show');
    }
    
    function clearValidationErrors() {
        document.querySelectorAll('.field-error').forEach(el => {
            el.classList.remove('field-error');
        });
        
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.remove('show');
        });
    }
    
    function scrollToFirstError() {
        const firstError = document.querySelector('.field-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Real-time validation feedback
    document.addEventListener('change', function(e) {
        if (e.target.name === 'staff_id') {
            const instructorSection = document.getElementById('instructor-section');
            const instructorError = document.getElementById('instructor-error');
            if (instructorSection) instructorSection.classList.remove('field-error');
            if (instructorError) instructorError.classList.remove('show');
            hideValidationWarning();
        }
    });

       function toggleStudentIdField() {
        const selected = document.querySelector('input[name="patient_type"]:checked');
        const studentIdInput = document.getElementById('student_id');

        if (selected && (selected.value === 'student' || selected.value === 'fsr student' || selected.value === 'uitm staff' )) {
            studentIdInput.disabled = false;
            studentIdInput.setAttribute('required', 'required');
            studentIdInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        } else {
            studentIdInput.disabled = true;
            studentIdInput.removeAttribute('required');
            studentIdInput.value = ''; // optional: clear field
        }
    }

    // Listen for change
    document.querySelectorAll('input[name="patient_type"]').forEach(radio => {
        radio.addEventListener('change', toggleStudentIdField);
    });

    // Run on page load
    window.addEventListener('DOMContentLoaded', toggleStudentIdField);
</script>
