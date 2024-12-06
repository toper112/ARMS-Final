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
            'attendance_time' => 'required|string',
        ]);

        // Get today's date and current time
        $currentDate = now()->toDateString();
        $currentTime = Carbon::now()->toTimeString(); // Get the current time

        // Extract the attendance time field name
        $attendanceTime = $validatedData['attendance_time'];

        // Map the attendance time to the correct database column
        $attendanceColumn = null;
        switch ($attendanceTime) {
            case 'morning_in':
                $attendanceColumn = 'morning_time_in';
                break;
            case 'morning_out':
                $attendanceColumn = 'morning_time_out';
                break;
            case 'afternoon_in':
                $attendanceColumn = 'afternoon_time_in';
                break;
            case 'afternoon_out':
                $attendanceColumn = 'afternoon_time_out';
                break;
            default:
                return redirect()->back()->with('message', 'Invalid attendance time.');
        }

        // Check for an existing record for the same user and event
        $attendance = AttendanceRecord::where('user_id', $validatedData['user_id'])
            ->where('event_id', $validatedData['event_id'])
            ->where('date', $currentDate)
            ->first();

        // If attendance exists, update the specific time column
        if ($attendance) {
            if ($attendance->$attendanceColumn !== null) {
                return redirect()->back()->with('message', ucfirst(str_replace('_', ' ', $attendanceTime)) . ' already recorded for this session.');
            } else {
                $attendance->update([$attendanceColumn => $currentTime]);
                return redirect()->back()->with('message', ucfirst(str_replace('_', ' ', $attendanceTime)) . ' recorded successfully!');
            }
        } else {
            // If no attendance record exists, create a new one
            $newRecord = [
                'user_id' => $validatedData['user_id'],
                'event_id' => $validatedData['event_id'],
                'date' => $currentDate,
                $attendanceColumn => $currentTime,
            ];

            AttendanceRecord::create($newRecord);
            return redirect()->back()->with('message', ucfirst(str_replace('_', ' ', $attendanceTime)) . ' recorded successfully!');
        }
    }

}
