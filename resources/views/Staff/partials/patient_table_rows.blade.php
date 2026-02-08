@forelse ($patientrecord as $patient)
    <tr class="bg-[#F7FAFC] hover:bg-[#E2EDF0] transition-all rounded-lg">
        <td>{{ $patient->id ?? '-' }}</td>
        <td>{{ $patient->user->name ?? '-' }}</td>
        <td>{{ $patient->ICNumber ?? '-' }}</td>
        <td>{{ $patient->category?? '-' }}</td>
        <td class="flex justify-center gap-3">
            <a href="{{ route('patientRecord', $patient->user_id) }}" class="text-green-600 hover:text-green-800 ml-2" title="View Record">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                </svg>
            </a>
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
@empty
    <tr>
        <td colspan="5" class="py-6 text-gray-500 italic">No records found.</td>
    </tr>
@endforelse
