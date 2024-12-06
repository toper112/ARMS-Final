<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function dashboard(Request $request)
    {
        // Fetch all users excluding 'admin' role
        $users = User::query()->whereNot('first_name', 'admin')->get();

        // Get the selected month and year from query parameters or default to the current month and year
        $currentMonth = $request->query('month', Carbon::now()->month);
        $currentYear = $request->query('year', Carbon::now()->year);

        // Get all events for the selected month and year
        $events = Event::whereMonth('date', $currentMonth)
                       ->whereYear('date', $currentYear)
                       ->get();

        // Get the latest event
        $latestEvent = $events->sortByDesc('date')->first();

        // Initialize attendanceCounts
        $attendanceCounts = collect();

        // If there is a latest event, get the attendance records for that event
        if ($latestEvent) {
            $attendanceCounts = AttendanceRecord::where('event_id', $latestEvent->id)
                ->with('user')  // Assuming you have a relationship defined in AttendanceRecord
                ->get()
                ->groupBy(function ($attendance) {
                    return $attendance->user->year;  // Use 'year' instead of 'year_level'
                })
                ->map(function ($attendance) {
                    return $attendance->count();  // Count attendance records per year level
                });
        }

        // Prepare the data for the Blade template
        $startOfMonth = Carbon::create($currentYear, $currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $startDayOfWeek = $startOfMonth->dayOfWeek;
        $totalDaysInMonth = $endOfMonth->day;

        // Get the previous and next month/year for navigation
        $previousMonth = $startOfMonth->copy()->subMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();

        // Group events by date
        $eventsByDate = $events->groupBy(function ($event) {
            return Carbon::parse($event->date)->format('Y-m-d');
        });

        // Dynamically generate labels based on year from users
        $yearLevels = $users->pluck('year')->unique(); // Get unique years from users

        // Prepare chart data based on year levels
        $labels = $yearLevels->toArray();  // Convert the collection to an array
        $values = [];

        // For each year level, get the attendance count
        foreach ($yearLevels as $yearLevel) {
            $values[] = $attendanceCounts->get($yearLevel, 0);  // Get attendance count or default to 0
        }

        // Prepare chart data
        $chartData = [
            'labels' => $labels,
            'values' => $values
        ];

        // Pass all necessary data to the view
        return view('dashboard', compact(
            'currentYear',
            'currentMonth',
            'startOfMonth',
            'endOfMonth',
            'startDayOfWeek',
            'totalDaysInMonth',
            'eventsByDate',
            'previousMonth',
            'nextMonth',
            'attendanceCounts',  // Pass attendance counts to the view
            'chartData'  // Pass chart data to the view
        ));
    }
}

