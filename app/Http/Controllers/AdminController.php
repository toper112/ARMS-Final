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


            // Calendar calculations
            $startOfMonth = Carbon::create($currentYear, $currentMonth, 1);
            $endOfMonth = $startOfMonth->copy()->endOfMonth();
            $startDayOfWeek = $startOfMonth->dayOfWeek;
            $totalDaysInMonth = $endOfMonth->day;

            // Navigation
            $previousMonth = $startOfMonth->copy()->subMonth();
            $nextMonth = $startOfMonth->copy()->addMonth();

            // Group events by date
            $eventsByDate = $events->groupBy(fn($event) => Carbon::parse($event->date)->format('Y-m-d'));


            return view('dashboard', compact(
                'currentYear',
                'currentMonth',
                'startOfMonth',
                'endOfMonth',
                'startDayOfWeek',
                'totalDaysInMonth',
                'eventsByDate',
                'previousMonth',
                'nextMonth'
            ));
        }
    }

