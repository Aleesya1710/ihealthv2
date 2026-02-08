@forelse ($appointments as $appointment)
    <tr class="bg-[#F7FAFC] hover:bg-[#E2EDF0] transition-all rounded-lg h-14">
        <td>{{ $appointment->id ?? '-' }}</td>
        <td>{{ $appointment->patientRecord->customer->user->name ?? '-' }}</td>
        <td>{{ $appointment->service->name ?? '-' }}</td>
        <td>{{ $appointment->staff->user->name ?? '-' }}</td>
        <td>{{ $appointment->date }}</td>
        <td>{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
        <td>{{ ucfirst($appointment->status) }}</td>
        <td class="flex justify-center items-center gap-3 h-14 leading-none">
            @if($appointment->status == "upcoming")
                <a href="#"
                   @click.prevent="openModal({{ $appointment->id }}, '{{ $appointment->date }}', '{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}', {{ $appointment->staff_id }})"
                   class="text-blue-600 hover:text-blue-800 inline-flex items-center justify-center h-5 w-5 leading-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5l3 3L14 13H11v-3l7.5-7.5z" />
                    </svg>
                </a>
            @endif
            @if($appointment->status == "upcoming")
                <form action="{{ route('appointment.cancel', $appointment->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" title="Cancel" class="text-red-600 hover:text-red-800 inline-flex items-center justify-center h-5 w-5 leading-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="py-6 text-center text-gray-500">No appointments found.</td>
    </tr>
@endforelse
