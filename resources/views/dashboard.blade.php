<x-admin-layout>
    <div class="container mx-auto px-4 py-6 pb-20">
        <h1 class="text-2xl font-bold mb-4 text-center">Dashboard - Calendar</h1>

        <!-- Month and Year Display with Navigation -->
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('dashboard', ['month' => $previousMonth->month, 'year' => $previousMonth->year]) }}"
                hx-get="{{ route('dashboard', ['month' => $previousMonth->month, 'year' => $previousMonth->year]) }}"
                hx-target="#calendar-container" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                <i class='fas fa-angle-left' style='font-size:36px'></i>
            </a>
            <h2 class="text-xl font-bold text-center">
                {{ $startOfMonth->format('F Y') }}
            </h2>
            <a href="{{ route('dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                hx-get="{{ route('dashboard', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                hx-target="#calendar-container" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                <i class='fas fa-angle-right' style='font-size:36px'></i>
            </a>
        </div>

        <!-- Calendar Grid and Event Display -->
        <div id="calendar-container">
            <!-- Day Labels -->
            <div class="grid grid-cols-7 gap-2 mb-2 text-center font-bold">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="text-gray-600">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2">
                @php
                    $counter = 1; // Initialize counter to loop through the days of the month
                    $today = \Carbon\Carbon::today()->format('Y-m-d'); // Define today's date
                @endphp

                @for ($i = 0; $i < 6; $i++) <!-- Iterate through rows (weeks) -->
                    @for ($j = 0; $j < 7; $j++)
                        @php
                            $day = $counter - $startDayOfWeek;
                            $currentDate = $startOfMonth
                                ->copy()
                                ->addDays($counter - 1)
                                ->format('Y-m-d');
                        @endphp

                        @if ($day > 0 && $day <= $totalDaysInMonth)
                            <div
                                class="flex flex-col items-center p-2 rounded min-h-[80px]
                                @if ($today == $currentDate) border-2 border-blue-800 @else border border-gray-200 @endif">
                                <span class="block font-bold">{{ $day }}</span>

                                <!-- Display events -->
                                @if (isset($eventsByDate[$currentDate]))
                                    @foreach ($eventsByDate[$currentDate] as $event)
                                        @php
                                            $eventDate = \Carbon\Carbon::parse($event->date);
                                        @endphp
                                        <div class="text-xs text-white rounded mt-1 px-2 py-1">
                                            @if ($eventDate->isFuture())
                                                <span class="bg-blue-500">{{ $event->name }}</span>
                                            @elseif ($eventDate->isToday())
                                                <span class="bg-green-500">{{ $event->name }}</span>
                                            @else
                                                <span class="bg-red-500">{{ $event->name }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <div class="flex flex-col items-center p-2 border border-gray-200 min-h-[80px]"></div>
                        @endif

                        @php
                            $counter++; // Increment the counter to move to the next day
                        @endphp
                    @endfor
                @endfor
            </div>
        </div>

        <!-- Chart -->
        <div class="flex flex-col items-center mt-8">
            <h2 class="font-bold text-lg mb-4">Attendance by Year Level</h2>
            <canvas id="attendanceChart" width="400" height="200"></canvas>
        </div>

        <script>
            // Chart.js Initialization
            let attendanceChart;

            function initializeChart(data) {
                const ctx = document.getElementById('attendanceChart').getContext('2d');
                if (attendanceChart) attendanceChart.destroy(); // Destroy existing chart
                attendanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels, // Year levels
                        datasets: [{
                            label: 'Attendance Count',
                            data: data.values, // Attendance values
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Initialize chart with initial data
            const initialChartData = @json($chartData); // This will inject the chartData from the controller
            initializeChart(initialChartData);
        </script>
    </div>
</x-admin-layout>
