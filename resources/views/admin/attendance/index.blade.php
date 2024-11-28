<x-admin-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">My Attendance Records</h1>

        @if (session('message'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-6">
            <table class="w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left">Event Name</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Morning Time In</th>
                        <th class="px-4 py-2 text-left">Morning Time Out</th>
                        <th class="px-4 py-2 text-left">Afternoon Time In</th>
                        <th class="px-4 py-2 text-left">Afternoon Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        @php
                            $eventDate = \Carbon\Carbon::parse($event->date);
                            $attendance = $event->attendanceRecords->first(); // Get the first attendance record
                        @endphp
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $event->name }}</td>
                            <td class="px-4 py-2">{{ $event->date }}</td>

                            <!-- Morning Time In -->
                            <td class="px-4 py-2">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->morning_time_in !== null)
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>

                            <!-- Morning Time Out -->
                            <td class="px-4 py-2">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->morning_time_out !== null)
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>

                            <!-- Afternoon Time In -->
                            <td class="px-4 py-2">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->afternoon_time_in !== null)
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>

                            <!-- Afternoon Time Out -->
                            <td class="px-4 py-2">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->afternoon_time_out !== null)
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
