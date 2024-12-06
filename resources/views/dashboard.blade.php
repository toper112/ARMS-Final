<x-admin-layout>
    <div class="container mx-auto px-4 py-6 pb-20">
        <h1 class="text-2xl font-bold mb-4 text-center">Dashboard - Calendar</h1>

        <!-- Month and Year Display with Navigation -->
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('dashboard', ['month' => $previousMonth->month, 'year' => $previousMonth->year]) }}"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                <i class='fas fa-angle-left' style='font-size:36px'></i>
            </a>
            <h2 class="text-xl font-bold text-center">
                {{ $startOfMonth->format('F Y') }}
            </h2>
            <a href="{{ route('dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                <i class='fas fa-angle-right' style='font-size:36px'></i>
            </a>
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-2 border border-gray-300 rounded-lg overflow-hidden">
            <!-- Calendar Headers -->
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="bg-gray-100 text-center font-bold py-2">
                    {{ $day }}
                </div>
            @endforeach

            @php
                // Fill initial empty cells for days before the first of the month
                $daysInMonth = $totalDaysInMonth;
                $emptyCells = $startDayOfWeek;
            @endphp

            @for ($i = 0; $i < $emptyCells; $i++)
                <div class="p-2 border border-gray-200"></div>
            @endfor

            <!-- Calendar Days -->
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDate = $startOfMonth
                        ->copy()
                        ->addDays($day - 1)
                        ->format('Y-m-d');
                @endphp
                <div
                    class="flex flex-col items-center p-2 border border-gray-200 min-h-[100px]
                    {{ $currentDate == \Carbon\Carbon::today()->format('Y-m-d') ? 'bg-blue-100 border-blue-600' : '' }}">
                    <span class="font-bold">{{ $day }}</span>

                    <!-- Display Events -->
                    @if (isset($eventsByDate[$currentDate]))
                        @foreach ($eventsByDate[$currentDate] as $event)
                            @php
                                $eventDate = \Carbon\Carbon::parse($event->date);
                            @endphp
                            <div
                                class="text-xs text-white rounded mt-1 px-2 py-1
                                {{ $eventDate->isFuture() ? 'bg-blue-500' : ($eventDate->isToday() ? 'bg-green-500' : 'bg-red-500') }}">
                                {{ $event->name }}
                            </div>
                        @endforeach
                    @endif
                </div>
            @endfor

            @php
                // Fill trailing empty cells for days after the last day of the month
                $remainingCells = (7 - (($daysInMonth + $emptyCells) % 7)) % 7;
            @endphp

            @for ($i = 0; $i < $remainingCells; $i++)
                <div class="p-2 border border-gray-200"></div>
            @endfor
        </div>
    </div>

</x-admin-layout>
