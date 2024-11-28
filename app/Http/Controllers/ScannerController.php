<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScannerController extends Controller
{
    public function index($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.scanner.scanner', compact('event'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
        ]);

        // Get today's date and current time
        $currentDate = now()->toDateString();
        $currentTime = Carbon::now()->toTimeString(); // Get the current time

        // Check for an existing record with the same user, event, and date
        $attendance = AttendanceRecord::where('user_id', $validatedData['user_id'])
            ->where('event_id', $validatedData['event_id'])
            ->where('date', $currentDate)
            ->first();

        if ($attendance) {
            // If a record exists, check if the morning or afternoon time-out is already recorded
            if ($currentTime < '12:00:00' && $attendance->morning_time_out !== null) {
                return redirect()->back()->with('message', 'Morning time-out already recorded for this session.');
            } else if ($currentTime < '12:00:00' && $attendance->morning_time_out === null) {
                $attendance->update(['morning_time_out' => $currentTime]);
                return redirect()->back()->with('message', 'Morning time-out recorded successfully!');
            }
            if ($currentTime >= '12:00:00' && $attendance->afternoon_time_out !== null) {
                return redirect()->back()->with('message', 'Afternoon time-out already recorded for this session.');
            } else if ($currentTime >= '12:00:00' && $attendance->afternoon_time_out === null) {
                $attendance->update(['afternoon_time_out' => $currentTime]);
                return redirect()->back()->with('message', 'Afternoon time-out recorded successfully!');
            }
        }

        // If no record exists, create a new one with time-in
        if ($currentTime < '12:00:00') {
            // Morning session
            AttendanceRecord::create([
                'user_id' => $validatedData['user_id'],
                'event_id' => $validatedData['event_id'],
                'date' => $currentDate,
                'morning_time_in' => $currentTime,
                'morning_time_out' => null, // Leave morning time out empty
                'afternoon_time_in' => null,
                'afternoon_time_out' => null,
            ]);

            return redirect()->back()->with('message', 'Morning time-in recorded successfully!');
        } else {
            // Afternoon session
            AttendanceRecord::create([
                'user_id' => $validatedData['user_id'],
                'event_id' => $validatedData['event_id'],
                'date' => $currentDate,
                'morning_time_in' => null,
                'morning_time_out' => null,
                'afternoon_time_in' => $currentTime,
                'afternoon_time_out' => null, // Leave afternoon time out empty
            ]);

            return redirect()->back()->with('message', 'Afternoon time-in recorded successfully!');
        }
    }
}
