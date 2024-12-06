<x-admin-layout>
    <div class="container mx-auto px-4 py-6 pb-20">

        <h1 class="text-4xl font-bold mb-4">Attendance Records</h1>
        <h1 class="text-1xl font-bold mb-4">
            {{ $user->last_name }},
            {{ $user->first_name }}
            | Grade: {{ $user->year }}
            | Section: {{ $user->section }}
        </h1>

        <div class="mb-6 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-4 py-2 text-left text-sm sm:text-base">Event Name</th>
                        <th class="px-4 py-2 text-left text-sm sm:text-base">Date</th>
                        <th class="px-4 py-2 text-left text-sm sm:text-base">Morning Time In</th>
                        <th class="px-4 py-2 text-left text-sm sm:text-base">Morning Time Out</th>
                        <th class="px-4 py-2 text-left text-sm sm:text-base">Afternoon Time In</th>
                        <th class="px-4 py-2 text-left text-sm sm:text-base">Afternoon Time Out</th>
                        <th class="px-4 py-2 text-left text-sm sm:text-base">Fines</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $overallTotalFines = 0;
                    @endphp
                    @forelse ($events as $event)
                        @php
                            $eventDate = \Carbon\Carbon::parse($event->date);
                            $attendance = $event->attendanceRecords->first();
                            $absences = 0;
                            $finePerAbsence = $event->fines;

                            // Count absences
                            if ($attendance) {
                                $absences += empty($attendance->morning_time_in) ? 1 : 0;
                                $absences += empty($attendance->morning_time_out) ? 1 : 0;
                                $absences += empty($attendance->afternoon_time_in) ? 1 : 0;
                                $absences += empty($attendance->afternoon_time_out) ? 1 : 0;
                            } else {
                                $absences = 4;
                            }

                            $totalFine = $absences * $finePerAbsence;
                            if (!$eventDate->isFuture()) {
                                $overallTotalFines += $totalFine;
                            }

                        @endphp
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm sm:text-base">{{ $event->name }}</td>
                            <td class="px-4 py-2 text-sm sm:text-base">{{ $event->date }}</td>

                            <td class="px-4 py-2 text-sm sm:text-base">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->remarks === 'paid')
                                    <span class="text-blue-300 font-bold">Paid</span>
                                @elseif ($attendance && !empty($attendance->morning_time_in))
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-sm sm:text-base">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->remarks === 'paid')
                                    <span class="text-blue-300 font-bold">Paid</span>
                                @elseif ($attendance && !empty($attendance->morning_time_out))
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-sm sm:text-base">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->remarks === 'paid')
                                    <span class="text-blue-300 font-bold">Paid</span>
                                @elseif ($attendance && !empty($attendance->afternoon_time_in))
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-sm sm:text-base">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->remarks === 'paid')
                                    <span class="text-blue-300 font-bold">Paid</span>
                                @elseif ($attendance && !empty($attendance->afternoon_time_out))
                                    <span class="text-green-500 font-bold">Present</span>
                                @else
                                    <span class="text-red-500 font-bold">Absent</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-sm sm:text-base">
                                @if ($eventDate->isFuture())
                                    <span class="text-gray-500">N/A</span>
                                @elseif ($attendance && $attendance->remarks === 'paid')
                                    <span class="text-blue-300 font-bold">Paid</span>
                                @else
                                    <span class="text-red-500 font-bold">{{ $totalFine }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-500 text-sm sm:text-base">
                                No events found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 border-t">
                        <td colspan="6" class="px-4 py-2 text-right text-sm sm:text-base font-bold">Overall Total
                            Fines:</td>
                        <td class="px-4 py-2 text-sm sm:text-base font-bold text-red-500">
                            @php
                                $paid = false; // Flag to check if the attendance is marked as paid
                                foreach ($events as $event) {
                                    $attendance = $event->attendanceRecords->first();
                                    if ($attendance && $attendance->remarks === 'paid') {
                                        $paid = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if ($paid)
                                <span class="text-blue-300 font-bold">Paid</span>
                            @else
                                {{ $overallTotalFines }}
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @role('admin')
            <!-- Paid Button -->
            <div class="flex justify-end mt-4">
                <form method="POST" action="{{ route('admin.attendance.paid', $user->id) }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        onclick="return confirm('Are you sure you want to mark all fines as paid?');">
                        Mark as Paid
                    </button>
                </form>
            </div>
        @endrole
    </div>
</x-admin-layout>
