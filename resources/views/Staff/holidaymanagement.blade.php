@extends('layouts.layoutS')

@section('content')
<div class="max-w-5xl mx-auto mt-10" x-data="holidayCalendar()" x-init="$nextTick(() => init())">
    <div id="calendar" class="rounded-lg border border-white shadow-sm p-4 "></div>


    <div 
        x-show="showModal" 
        x-transition
        style="z-index: 9999;" 
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative z-50">
            <h2 class="text-xl font-bold mb-4">Manage Holiday - <span x-text="form.date"></span></h2>

            <template x-if="form.exists && !editing">
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Existing Holiday Reason:</label>
                    <div class="bg-gray-100 p-2 rounded" x-text="form.reason"></div>
                    <div class="flex justify-end mt-2">
                         <button type="button" class="mr-2 px-4 py-2 bg-gray-300 rounded" @click="closeModal">Cancel</button>
                        <button 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mr-2"
                            @click="editing = true"
                        >Edit</button>
                        <button 
                            class="bg-red-500 text-white px-3 py-1 rounded" 
                            @click="deleteHoliday"
                        >Delete</button>
                    </div>
                </div>
            </template>

            <template x-if="!form.exists || editing">
                <form @submit.prevent="form.exists ? updateHoliday() : submitForm()">
                    <div class="mb-4">
                        <label class="block mb-1">Reason</label>
                        <input type="text" x-model="form.reason" class="w-full border px-3 py-2 rounded">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="mr-2 px-4 py-2 bg-gray-300 rounded" @click="closeModal">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                            <span x-text="form.exists ? 'Update' : 'Add'"></span>
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>

<script>
function holidayCalendar() {
    return {
        showModal: false,
        editing: false,
        form: {
            date: '',
            reason: '',
            exists: false,
            id: null
        },
        calendar: null,
        events: [],

        async fetchEvents() {
            try {
                const res = await fetch('{{ route("holiday.events") }}');
                const data = await res.json();
                this.events = data.map(event => ({
                    id: event.id.toString(),
                    title: event.name,
                    start: event.date,
                    allDay: true,
                    extendedProps: {
                        reason: event.name
                    }
                }));
            } catch (error) {
                console.error('Failed to fetch events', error);
            }
        },

        async initCalendar() {
            await this.fetchEvents();

            this.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                selectable: true,
                dateClick: (info) => {
    const today = new Date().toISOString().split('T')[0];
    const clicked = info.dateStr;

    if (clicked < today) {
        alert('You cannot add holidays on past dates.');
        return;
    }

    this.openModal(clicked);
},
                events: this.events
            });

            this.calendar.render();
        },

        async openModal(dateStr) {
            this.resetForm();
            this.form.date = dateStr;

            const matchDate = date => new Date(date).toISOString().split('T')[0];
            const existing = this.events.find(ev => matchDate(ev.start) === dateStr);

            if (existing) {
                this.form.exists = true;
                this.form.reason = existing.extendedProps.reason;
                this.form.id = existing.id;
            }

            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editing = false;
        },

        resetForm() {
            this.form = {
                date: '',
                reason: '',
                exists: false,
                id: null
            };
            this.editing = false;
        },

     async submitForm() {
        try {
            const response = await fetch('{{ route("holiday.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.form)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                const newEvent = {
                    id: result.id.toString(),
                    title: this.form.reason,
                    start: this.form.date,
                    allDay: true,
                    extendedProps: {
                        reason: this.form.reason
                    }
                };

                this.calendar.addEvent(newEvent);
                this.events.push(newEvent); // 💡 manually sync Alpine state

                this.closeModal();
            } else {
                alert('Failed to add holiday.');
            }
        } catch (error) {
            console.error('Submit error:', error);
            alert('Unexpected error occurred.');
        }
    },



        async updateHoliday() {
            try {
                const response = await fetch(`/holidays/${this.form.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ reason: this.form.reason })
                });

                const result = await response.json();

                if (result?.success || response.ok) {
                    const event = this.calendar.getEventById(this.form.id.toString());
                    event.setProp('title', this.form.reason);
                    event.setExtendedProp('reason', this.form.reason);
                    this.closeModal();
                } else {
                    alert('Failed to update.');
                }
            } catch (error) {
                console.error(error);
                alert('Error updating.');
            }
        },

        async deleteHoliday() {
            if (!confirm('Are you sure you want to delete this holiday?')) return;

            try {
                const response = await fetch(`/holidays/${this.form.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    const event = this.calendar.getEventById(this.form.id.toString());
                    if (event) event.remove();
                    this.closeModal();
                } else {
                    alert('Failed to delete.');
                }
            } catch (error) {
                console.error(error);
                alert('Error deleting holiday.');
            }
        },

        async init() {
            await this.initCalendar();
        }
    }
}
</script>
@endsection
