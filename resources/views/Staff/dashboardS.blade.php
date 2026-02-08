@extends('layouts.layoutS')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="//unpkg.com/alpinejs" defer></script>

@section('content')
<div class="p-6">
    <div class="mx-auto max-w-7xl space-y-6">
        
        <!-- Header Section -->
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h1 class="text-2xl font-semibold text-[#0E6E83]">Dashboard Overview</h1>
            <p class="mt-1 text-sm text-gray-600">Welcome back! Here's what's happening today</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            
            <!-- Completed Appointments Card -->
            <div class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 transition hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-green-100 text-green-700">
                        <svg class="h-8 w-8 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Completed Today</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $CompletedAppointment }}</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments Card -->
            <div class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 transition hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                        <svg class="h-8 w-8 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Upcoming</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $UpcomingAppointment }}</p>
                    </div>
                </div>
            </div>

            <!-- Cancelled Appointments Card -->
            <div class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 transition hover:shadow-md">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <svg class="h-8 w-8 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Cancelled</p>
                        <p class="mt-1 text-3xl font-bold text-gray-900">{{ $CancelledAppointment }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <!-- Appointments Trend Chart -->
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Appointments Trend</h3>
                    <p class="mt-1 text-sm text-gray-500">Last 7 days overview</p>
                </div>
                <div class="relative h-64">
                    <canvas id="appointmentByDateChart"></canvas>
                </div>
            </div>

            <!-- Feedback Rating Chart -->
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Feedback Ratings</h3>
                    <p class="mt-1 text-sm text-gray-500">Customer satisfaction</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="relative h-48 w-48">
                        <canvas id="feedbackRatingChart"></canvas>
                    </div>
                    <div id="customLegend" class="mt-6 space-y-2 w-full"></div>
                </div>
            </div>
        </div>

        <!-- Customer Feedback Carousel -->
        <div x-data='{ 
            activeSlide: 0, 
            feedbacks: @json($feedback->map(fn($f) => ["name" => $f->user->name ?? "Anonymous", "text" => $f->message ?? "No message"])), 
            autoSlide() { 
                setInterval(() => { 
                    this.activeSlide = (this.activeSlide + 1) % this.feedbacks.length; 
                }, 5000); 
            }, 
            init() { 
                this.autoSlide(); 
            } 
        }'
        x-init="init()"
        class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-black/5">
            <div>
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#10859F]/10 text-[#10859F]">
                        <svg class="h-6 w-6 text-[#10859F]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Customer Feedback</h3>
                </div>

                <div class="relative min-h-[120px]">
                    <template x-for="(item, index) in feedbacks" :key="index">
                        <div x-show="activeSlide === index" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-x-8"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-8"
                             class="absolute inset-0">
                            <div class="flex gap-4">
                                <img src="{{ asset('image/profile.jpg') }}" 
                                     class="h-14 w-14 rounded-full border-4 border-white shadow-lg" 
                                     alt="User Profile" />
                                <div class="flex-1">
                                    <div class="rounded-2xl bg-white p-6 shadow-md">
                                        <p class="text-lg leading-relaxed text-gray-700" x-text="item.text"></p>
                                        <p class="mt-3 text-sm font-semibold text-[#10859F]" x-text="'— ' + item.name"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Slide Indicators -->
                <div class="mt-8 flex justify-center gap-2">
                    <template x-for="(item, index) in feedbacks" :key="index">
                        <button @click="activeSlide = index"
                                :class="activeSlide === index ? 'bg-[#10859F] w-8' : 'bg-gray-300 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Today's Appointments Table -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
            <div class="border-b border-gray-200 bg-gray-50 px-8 py-6">
                <h3 class="text-2xl font-bold text-gray-900">Today's Appointments</h3>
                <p class="mt-1 text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Staff</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($AppointmentsToday->count())
                            @foreach($AppointmentsToday as $app)
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        @if($app->patientrecord->customer)
                                            <a href="{{ route('patientRecord', ['id' => $app->patientrecord->customer->user->id]) }}" 
                                               class="font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                                {{ $app->patientrecord->customer->user->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $app->time ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $app->service->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $app->staff->user->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $status = ucfirst($app->status);
                                            $statusConfig = [
                                                'Completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'ring' => 'ring-green-600/20'],
                                                'Upcoming' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'ring' => 'ring-blue-600/20'],
                                                'Cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'ring' => 'ring-red-600/20'],
                                            ];
                                            $config = $statusConfig[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-600/20'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }} ring-1 ring-inset {{ $config['ring'] }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-500">No appointments scheduled for today</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Appointments Trend Chart
const dateCtx = document.getElementById('appointmentByDateChart').getContext('2d');
new Chart(dateCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($totalAppointmentsByDate->pluck('appointment_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!},
        datasets: [{
            label: 'Appointments',
            data: {!! json_encode($totalAppointmentsByDate->pluck('total')) !!},
            backgroundColor: 'rgba(16, 133, 159, 0.1)',
            borderColor: 'rgba(16, 133, 159, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: 'rgba(16, 133, 159, 1)',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 13 },
            }
        },
        scales: { 
            x: { 
                grid: { display: false },
                ticks: { color: '#6B7280' }
            }, 
            y: { 
                grid: { color: '#F3F4F6' },
                ticks: { 
                    color: '#6B7280',
                    precision: 0 
                }
            } 
        }
    }
});

// Feedback Rating Chart
const ratingCtx = document.getElementById('feedbackRatingChart').getContext('2d');
const chart = new Chart(ratingCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($feedbackByRating->pluck('rating')->map(fn($r) => "$r ★")) !!},
        datasets: [{
            data: {!! json_encode($feedbackByRating->pluck('total')) !!},
            backgroundColor: [
                'rgba(249, 115, 22, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(34, 197, 94, 0.8)',
            ],
            borderWidth: 0,
            hoverOffset: 10,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' reviews';
                    }
                }
            }
        },
        cutout: '65%',
    }
});

// Custom Legend
const legendContainer = document.getElementById('customLegend');
const labels = chart.data.labels;
const colors = chart.data.datasets[0].backgroundColor;
const data = chart.data.datasets[0].data;

labels.forEach((label, i) => {
    const legendItem = document.createElement('div');
    legendItem.className = 'flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 transition-all hover:bg-gray-100';

    const leftSide = document.createElement('div');
    leftSide.className = 'flex items-center gap-3';

    const box = document.createElement('div');
    box.className = 'h-3 w-3 rounded-full';
    box.style.backgroundColor = colors[i];

    const text = document.createElement('span');
    text.className = 'text-sm font-medium text-gray-700';
    text.textContent = label;

    const count = document.createElement('span');
    count.className = 'text-sm font-bold text-gray-900';
    count.textContent = data[i];

    leftSide.appendChild(box);
    leftSide.appendChild(text);
    legendItem.appendChild(leftSide);
    legendItem.appendChild(count);
    legendContainer.appendChild(legendItem);
});
</script>
@endsection
